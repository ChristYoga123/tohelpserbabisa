<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Filament\Tables;
use App\Models\Transaksi;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\FontWeight;
use Filament\Notifications\Notification;
use Filament\Widgets\TableWidget as BaseWidget;
use CodeWithDennis\SimpleMap\Components\Tables\SimpleMap;
use App\Filament\Admin\Resources\TransaksiResource\Pages\TugasPage;

class TransaksiWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

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
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('selesaiTugas')
                    ->label('Selesai')
                    ->button()
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Selesaikan Tugas')
                    ->modalDescription('Apakah anda yakin ingin menyelesaikan tugas ini?')
                    ->modalSubmitActionLabel('Ya, Selesaikan')
                    ->action(function(Transaksi $transaksi)
                    {
                        $transaksi->karyawanTugas->each(fn($q) => $q->update(['is_selesai' => true]));

                        $transaksi->update(['status_tugas' => 'selesai']);

                        Notification::make()
                            ->title('Sukses')
                            ->body('Tugas telah selesai')
                            ->success()
                            ->send();
                    })
                    ->hidden(fn(Transaksi $transaksi) => $transaksi->status_tugas === 'selesai' || $transaksi->tugas->isEmpty()),
                Tables\Actions\Action::make('beriTugas')
                    ->label('Beri Tugas')
                    ->button()
                    ->icon('heroicon-o-briefcase')
                    ->form([
                        Select::make('karyawan')
                            ->label('Karyawan Yang Sedang Tidak Memiliki Tugas')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(User::query()->whereHas('roles', fn($q) => $q->where('name', 'karyawan'))->pluck('name', 'id'))
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
                    ->hidden(fn(Transaksi $transaksi) => $transaksi->tugas->count() != 0),
                SimpleMap::make('showMap')
                    ->button()
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
                    ->button()
                    ->color('warning')
                    ->icon('heroicon-o-briefcase')
                    ->url(fn(Transaksi $transaksi) => TugasPage::getUrl(['record' => $transaksi]))
                    ->hidden(fn(Transaksi $transaksi) => $transaksi->tugas->count() == 0),
                Tables\Actions\DeleteAction::make()
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
