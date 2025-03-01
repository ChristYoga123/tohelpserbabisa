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
                Tables\Actions\Action::make('beriTugas')
                    ->label('Beri Tugas')
                    ->icon('heroicon-o-briefcase')
                    ->form([
                        Select::make('karyawan')
                            ->label('Karyawan Yang Sedang Tidak Memiliki Tugas')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(User::query()
                            ->whereHas('roles', fn($q) => $q->where('name', 'karyawan'))
                            ->where(function($query) {
                                $query->whereDoesntHave('karyawanTugas') // karyawan yang belum punya tugas
                                      ->orWhereDoesntHave('karyawanTugas', function($q) {
                                          $q->where('is_selesai', false); // karyawan yang tidak memiliki tugas yang belum selesai
                                      });
                            })
                            ->pluck('name', 'id'))
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
                Tables\Actions\Action::make('lihatTugas')
                    ->label('Lihat Tugas')
                    ->color('info')
                    ->icon('heroicon-o-briefcase')
                    ->url(fn(Transaksi $transaksi) => TugasPage::getUrl(['record' => $transaksi]))
                    ->hidden(fn(Transaksi $transaksi) => $transaksi->tugas->count() == 0),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
