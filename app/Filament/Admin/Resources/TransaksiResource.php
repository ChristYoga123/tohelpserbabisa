<?php

namespace App\Filament\Admin\Resources;

use Exception;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Transaksi;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\TransaksiResource\Pages;
use CodeWithDennis\SimpleMap\Components\Tables\SimpleMap;
use App\Filament\Admin\Resources\TransaksiResource\Pages\TugasPage;
use App\Filament\Admin\Resources\TransaksiResource\RelationManagers;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Transaksi';

    public static function canCreate(): bool
    {
        return 'false';
    }

    public static function canDelete(Model $model): bool
    {
        return 'false';
    }

    public static function canEdit(Model $record): bool
    {
        return 'false';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('order_id')
                //     ->required()
                //     ->maxLength(191),
                // Forms\Components\TextInput::make('voucher.nama')
                //     ->numeric(),
                // Forms\Components\TextInput::make('jenis')
                //     ->required(),
                // Forms\Components\TextInput::make('total_harga')
                //     ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Transaksi::query()->with('voucher')->orderBy('created_at', 'desc'))
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
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('komisi_admin')
                    ->weight(FontWeight::Bold)
                    ->suffix('%')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_tugas')
                    ->sortable()
                    ->badge(fn(Transaksi $transaksi) => match ($transaksi->status_tugas) {
                        'belum' => 'warning',
                        'proses' => 'info',
                        'selesai' => 'success',
                    })
                    ->color(fn(Transaksi $transaksi) => match ($transaksi->status_tugas) {
                        'belum' => 'warning',
                        'proses' => 'info',
                        'selesai' => 'success',
                    })
                    ->getStateUsing(function (Transaksi $transaksi) {
                        if ($transaksi->tugas->isEmpty()) {
                            return 'Belum Selesai';
                        }

                        if ($transaksi->karyawanTugas->every(fn($q) => $q->is_selesai)) {
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
                    ])
                ], layout: FiltersLayout::AboveContent)
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('selesaiTugas')
                        ->label('Selesai')
                        ->color('info')
                        ->icon('heroicon-o-check-circle')
                        ->requiresConfirmation()
                        ->modalHeading('Selesaikan Tugas')
                        ->modalDescription('Apakah anda yakin ingin menyelesaikan tugas ini?')
                        ->modalSubmitActionLabel('Ya, Selesaikan')
                        ->action(function (Transaksi $transaksi) {
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
                                // update status karyawan tugas dan wallet karyawan
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
                        ->hidden(fn(Transaksi $transaksi) => ($transaksi->status_tugas === 'selesai' || $transaksi->tugas->isEmpty()) || !$transaksi->total_harga),
                    Tables\Actions\Action::make('beriTugas')
                        ->label('Beri Tugas')
                        ->color('warning')
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
                        ->action(function (Transaksi $transaksi, array $data) {
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
                        ->color('success')
                        ->icon('heroicon-o-currency-dollar')
                        ->form([
                            Forms\Components\TextInput::make('total_harga')
                                ->label('Total Harga')
                                ->numeric()
                                ->default(fn(Transaksi $record) => $record->total_harga),
                            Forms\Components\TextInput::make('komisi_admin')
                                ->label('Komisi Admin')
                                ->numeric()
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
                        ->origin(fn(Transaksi $karyawanTugas) => $karyawanTugas->titik_jemput)
                        ->destination(fn(Transaksi $karyawanTugas) => $karyawanTugas->titik_tujuan)
                        // ->walking()
                        // ->satellite()
                        ->zoom(13)
                        ->language('id')
                        ->region('id')
                        ->visible(fn(Transaksi $karyawanTugas) => ($karyawanTugas->titik_jemput && $karyawanTugas->titik_tujuan) && $karyawanTugas->status_transaksi !== 'batal'),
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
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTransaksis::route('/'),
            'tugas' => Pages\TugasPage::route('/{record}/tugas'),
        ];
    }
}
