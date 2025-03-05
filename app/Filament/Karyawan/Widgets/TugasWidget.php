<?php

namespace App\Filament\Karyawan\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\KaryawanTugas;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Widgets\TableWidget as BaseWidget;
use CodeWithDennis\SimpleMap\Components\Tables\SimpleMap;

class TugasWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(KaryawanTugas::query()->with(['karyawan', 'tugas'])->whereKaryawanId(auth()->user()->id)->whereIsSelesai(false)->latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('tugas.order_id')
                    ->label('Order ID'),
                Tables\Columns\TextColumn::make('tugas.jenis')
                    ->label('Jenis Tugas'),
                Tables\Columns\TextColumn::make('tugas.jasa')
                    ->getStateUsing(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->tugas->jasa ?? '-'),
                Tables\Columns\TextColumn::make('is_selesai')
                    ->label('Status Tugas')
                    ->badge()
                    ->getStateUsing(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->is_selesai ? 'Selesai' : 'Belum')
                    ->color(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->is_selesai ? 'success' : 'warning'),
                // SimpleMap::make('showMap')
                //     ->directions()
                //     ->origin(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->tugas->titik_jemput)
                //     ->destination(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->tugas->titik_tujuan)
                //     ->driving()
                //     ->imperial()
                //     ->satellite()
                //     ->language('id'),
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
                Tables\Actions\Action::make('tugasSelesai')
                    ->label('Selesai')
                    ->button()
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Selesaikan Tugas')
                    ->modalDescription('Apakah anda yakin ingin menyelesaikan tugas ini?')
                    ->modalSubmitActionLabel('Ya, Selesaikan')
                    ->action(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->update(['is_selesai' => true]))
                    ->visible(fn (KaryawanTugas $karyawanTugas) => !$karyawanTugas->is_selesai),
                SimpleMap::make('showMap')
                    ->button()
                    ->icon('heroicon-o-map')
                    ->label('Lihat Peta')
                    ->color('info')
                    ->viewing()
                    ->directions()
                    ->origin(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->tugas->titik_jemput)
                    ->destination(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->tugas->titik_tujuan)
                    // ->walking()
                    // ->satellite()
                    ->zoom(13)
                    ->language('id')
                    ->region('id')
                    ->visible(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->tugas->titik_jemput && $karyawanTugas->tugas->titik_tujuan),
                Tables\Actions\Action::make('lihatTugas')
                    ->button()
                    ->label('Lihat Tugas')
                    ->icon('heroicon-o-eye')
                    ->infolist([
                        Grid::make()
                            ->columns(1)
                            ->schema([
                                TextEntry::make('jarak')
                                    ->getStateUsing(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->tugas->jarak . ' KM' ?? '-'),
                                TextEntry::make('titik_jemput')
                                    ->getStateUsing(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->tugas->titik_jemput ?? '-'),
                                TextEntry::make('titik_tujuan')
                                    ->getStateUsing(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->tugas->titik_tujuan ?? '-'),
                                ]),
                                
                        ])
                    ->modalSubmitAction(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
