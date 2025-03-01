<?php

namespace App\Filament\Karyawan\Widgets;

use App\Models\KaryawanTugas;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TugasWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(KaryawanTugas::query()->with('karyawan')->whereKaryawanId(auth()->user()->id)->orderBy('created_at', 'desc')->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('tugas.jenis')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('is_selesai')
                    ->label('Status Tugas')
                    ->badge()
                    ->getStateUsing(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->is_selesai ? 'Selesei' : 'Belum')
                    ->color(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->is_selesai ? 'success' : 'danger'),
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
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->action(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->update(['is_selesai' => true]))
                    ->visible(fn (KaryawanTugas $karyawanTugas) => !$karyawanTugas->is_selesai),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
