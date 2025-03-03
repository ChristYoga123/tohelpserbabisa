<?php

namespace App\Filament\Karyawan\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\KaryawanTugas;
use Filament\Resources\Resource;
use Filament\Infolists\Components\Grid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use CodeWithDennis\SimpleMap\Components\Tables\SimpleMap;
use App\Filament\Karyawan\Resources\KaryawanTugasResource\Pages;
use App\Filament\Karyawan\Resources\KaryawanTugasResource\RelationManagers;

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
            ->query(KaryawanTugas::query()->with(['karyawan', 'tugas'])->whereKaryawanId(auth()->user()->id)->latest())
            ->columns([
                Tables\Columns\TextColumn::make('tugas.order_id')
                    ->label('Order ID'),
                Tables\Columns\TextColumn::make('tugas.jenis')
                    ->label('Jenis Tugas'),
                Tables\Columns\TextColumn::make('is_selesai')
                    ->label('Status Tugas')
                    ->badge()
                    ->getStateUsing(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->is_selesai ? 'Selesai' : 'Belum')
                    ->color(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->is_selesai ? 'success' : 'warning'),
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
                                    ])
                        ])
                    ->modalSubmitAction(false),
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
            'index' => Pages\ManageKaryawanTugas::route('/'),
        ];
    }
}
