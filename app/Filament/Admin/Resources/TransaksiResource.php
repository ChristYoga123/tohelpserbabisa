<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Transaksi;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\TransaksiResource\Pages;
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
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('voucher.nama')
                    ->numeric()
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
            'index' => Pages\ManageTransaksis::route('/'),
        ];
    }
}
