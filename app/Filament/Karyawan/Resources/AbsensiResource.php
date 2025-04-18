<?php

namespace App\Filament\Karyawan\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Absensi;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Karyawan\Resources\AbsensiResource\Pages;
use App\Filament\Karyawan\Resources\AbsensiResource\RelationManagers;

class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Riwayat Kehadiran';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form([
            // 
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Absensi::query()->whereKaryawanId(auth()->user()->id))
            ->columns([
                Tables\Columns\TextColumn::make('karyawan.name')
                    ->label('Karyawan'),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('l, d F Y'),
                Tables\Columns\TextColumn::make('jam_masuk')
                    ->label('Jam Masuk')
                    ->dateTime('H:i:s'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color('success')
                    ->getStateUsing(fn() => 'Stand By'),
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAbsensis::route('/'),
        ];
    }
}
