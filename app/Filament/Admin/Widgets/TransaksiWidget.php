<?php

namespace App\Filament\Admin\Widgets;

use Exception;
use App\Models\User;
use Filament\Tables;
use App\Models\Transaksi;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Widgets\TableWidget as BaseWidget;
use CodeWithDennis\SimpleMap\Components\Tables\SimpleMap;
use App\Filament\Admin\Resources\TransaksiResource\Pages\TugasPage;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class TransaksiWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;


    public function table(Table $table): Table
    {
        return $table
            ->query(Transaksi::query()->with('voucher')->orderBy('created_at', 'desc')->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('voucher.nama')
                    ->getStateUsing(fn(Transaksi $transaksi) => $transaksi->voucher->nama ?? 'Tidak Ada Voucher')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis'),
                Tables\Columns\TextColumn::make('jasa')
                    ->getStateUsing(fn(Transaksi $transaksi) => $transaksi->jasa ?? '-'),
                Tables\Columns\TextColumn::make('total_harga')
                    ->weight(FontWeight::Bold)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('komisi_admin')
                    ->weight(FontWeight::Bold)
                    ->suffix('%')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_tugas')
                    ->sortable()
                    ->badge()
                    ->color(fn(Transaksi $transaksi) => match ($transaksi->status_tugas) {
                        'belum' => 'warning',
                        'proses' => 'info',
                        'selesai' => 'success',
                    })
                    ->getStateUsing(function(Transaksi $transaksi) {
                        if ($transaksi->tugas->isEmpty()) {
                            return 'Belum Selesai';
                        }

                        if($transaksi->karyawanTugas->every(fn($q) => $q->is_selesai)) {
                            $transaksi->update(['status_tugas' => 'selesai']);
                            return 'Selesai';
                        }
                
                        return 'Belum Selesai';

                    }),
                Tables\Columns\TextColumn::make('status_transaksi')
                    ->label('Status Transaksi')
                    ->badge()
                    ->color(fn (Transaksi $transaksi) => match ($transaksi->status_transaksi) {
                        'belum' => 'warning',
                        'sukses' => 'success',
                        'batal' => 'danger',
                    })
                    ->getStateUsing(function(Transaksi $transaksi) {
                        return match ($transaksi->status_transaksi) {
                            'belum' => 'Belum Selesai',
                            'sukses' => 'Sukses Bayar',
                            'batal' => 'Dibatalkan',
                        };
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_transaksi')
                    ->options([
                        'belum' => 'Belum Selesai',
                        'sukses' => 'Sukses Bayar',
                        'batal' => 'Dibatalkan',
                    ]),
                DateRangeFilter::make('created_at')->timezone('Asia/Jakarta')
                    ->label('Tanggal'),
                ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    // Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('selesaiTugas')
                        ->label('Selesai')
                        ->color('success')
                        ->icon('heroicon-o-check-circle')
                        ->requiresConfirmation()
                        ->modalHeading('Selesaikan Tugas')
                        ->modalDescription('Apakah anda yakin ingin menyelesaikan tugas ini?')
                        ->modalSubmitActionLabel('Ya, Selesaikan')
                        ->action(function(Transaksi $transaksi)
                        {
                            if(!$transaksi->total_harga || !$transaksi->komisi_admin)
                                {
                                    Notification::make()
                                        ->title('Gagal')
                                        ->body('Tugas tidak dapat diselesaikan, karena belum ada harga')
                                        ->danger()
                                        ->send();
                                    return;
                                }
                            DB::beginTransaction();
                            try
                            {
                                // update status transaksi
                                $transaksi->update(['status_tugas' => 'selesai']);
                                // input wallet admin
                                $admin = User::query()->role('super_admin')->first();
                                $admin->deposit($transaksi->total_harga * ($transaksi->komisi_admin / 100));
                                // update status karyawan tugas dan input wallet karyawan
                                $komisiKaryawanKeseluruhan = 100 - $transaksi->komisi_admin;
                                $komisiMasingMasingKaryawan = $komisiKaryawanKeseluruhan / $transaksi->karyawanTugas->count();
                                $transaksi->karyawanTugas->each(
                                    // fn($q) => $q->update(['is_selesai' => true])
                                    function($q) use ($komisiMasingMasingKaryawan, $transaksi) {
                                        $q->update(['is_selesai' => true]);
                                        $q->karyawan->deposit($transaksi->total_harga * ($komisiMasingMasingKaryawan / 100));
                                    }
                                );
                                DB::commit();
                                Notification::make()
                                    ->title('Sukses')
                                    ->body('Tugas telah selesai')
                                    ->success()
                                    ->send();
                            }catch(Exception $e)
                            {
                                DB::rollBack();
                                Notification::make()
                                    ->title('Gagal')
                                    ->body('Tugas gagal diselesaikan. ' . $e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->hidden(fn(Transaksi $transaksi) => $transaksi->status_tugas === 'selesai' || $transaksi->tugas->isEmpty()),
                    Tables\Actions\Action::make('beriTugas')
                        ->label('Beri Tugas')

                        ->icon('heroicon-o-briefcase')
                        ->form([
                            Select::make('karyawan')
                                ->label('Karyawan Yang Sedang Tidak Memiliki Tugas')
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->options(User::query()->whereHas('roles', fn($q) => $q->where('name', 'karyawan'))->whereHas('absensi', fn($q) => $q->whereDate('tanggal', now()))->pluck('name', 'id'))
                                ->required()
                        ])
                        ->action(function(Transaksi $transaksi, array $data)
                        {
                            $transaksi->tugas()->attach($data['karyawan']);

                            Notification::make()
                                ->title('Sukses')
                                ->body('Karyawan telah ditugaskan')
                                ->success()
                                ->send();
                        })
                        ->hidden(fn(Transaksi $transaksi) => $transaksi->tugas->count() != 0 || $transaksi->status_transaksi === 'batal'),
                    Tables\Actions\Action::make('ubahHarga')
                        ->label('Ubah Harga')
                        ->requiresConfirmation()
                        ->color('success')
                        ->icon('heroicon-o-currency-dollar')
                        ->form([
                            TextInput::make('total_harga')
                                ->label('Total Harga')
                                ->numeric()
                                ->default(fn(Transaksi $record) => $record->total_harga),
                            TextInput::make('komisi_admin')
                                ->label('Komisi Admin (Dalam Persen %)')
                                ->numeric()
                                ->suffix('%')
                                ->minValue(1)
                                ->maxValue(100)
                                ->default(fn(Transaksi $record) => $record->komisi_admin),
                        ])
                        ->action(function (Transaksi $transaksi, array $data) {
                            $transaksi->update([
                                'total_harga' => $data['total_harga'],
                                'komisi_admin' => $data['komisi_admin'],
                            ]);
                            Notification::make()
                                ->title('Sukses')
                                ->body('Harga berhasil diubah')
                                ->success()
                                ->send();
                        })
                        ->hidden(fn(Transaksi $transaksi) => $transaksi->status_transaksi === 'batal' || $transaksi->status_tugas === 'selesai' || $transaksi->komisi_admin !== null),
                    SimpleMap::make('showMap')

                        ->icon('heroicon-o-map')
                        ->label('Lihat Peta')
                        ->color('info')
                        ->viewing()
                        ->directions()
                        ->origin(fn (Transaksi $karyawanTugas) => $karyawanTugas->titik_jemput)
                        ->destination(fn (Transaksi $karyawanTugas) => $karyawanTugas->titik_tujuan)
                        // ->walking()
                        // ->satellite()
                        ->zoom(13)
                        ->language('id')
                        ->region('id')
                        ->visible(fn (Transaksi $karyawanTugas) => $karyawanTugas->titik_jemput && $karyawanTugas->titik_tujuan),
                    Tables\Actions\Action::make('lihatTugas')
                        ->label('Lihat Tugas')

                        ->color('warning')
                        ->icon('heroicon-o-briefcase')
                        ->url(fn(Transaksi $transaksi) => TugasPage::getUrl(['record' => $transaksi]))
                        ->hidden(fn(Transaksi $transaksi) => $transaksi->tugas->count() == 0),
                    Tables\Actions\DeleteAction::make()
                        ->label('Batalkan Transaksi')

                        ->action(function(Transaksi $transaksi)
                        {
                            $transaksi->update([
                                'status_transaksi' => 'batal',
                            ]);

                            Notification::make()
                                ->title('Sukses')
                                ->body('Transaksi dibatalkan')
                                ->success()
                                ->send();
                        })
                        ->hidden(fn(Transaksi $transaksi) => $transaksi->status_transaksi === 'batal' || $transaksi->status_tugas === 'selesai'),
                ]),
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
