<?php

namespace App\Filament\Karyawan\Resources;

use App\Filament\Karyawan\Resources\KaryawanTugasResource\Pages;
use App\Filament\Karyawan\Resources\KaryawanTugasResource\RelationManagers;
use App\Models\KaryawanTugas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KaryawanTugasResource extends Resource
{
    protected static ?string $model = KaryawanTugas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Tugas Karyawan';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $model): bool
    {
        return false;
    }

    public static function canEdit(Model $model): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tugas_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('karyawan_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('is_selesai')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(KaryawanTugas::query()->with('karyawan')->whereKaryawanId(auth()->user()->id))
            ->columns([
                Tables\Columns\TextColumn::make('tugas.jenis')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_selesai')
                    ->label('Status Tugas')
                    ->boolean(),
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
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->action(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->update(['is_selesai' => true]))
                    ->visible(fn (KaryawanTugas $karyawanTugas) => !$karyawanTugas->is_selesai),
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
            'index' => Pages\ManageKaryawanTugas::route('/'),
        ];
    }
}
