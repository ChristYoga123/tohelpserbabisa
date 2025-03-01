<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\VoucherResource\Pages;
use App\Filament\Admin\Resources\VoucherResource\RelationManagers;
use App\Models\Voucher;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(191),
                        Forms\Components\TextInput::make('persentase')
                            ->required()
                            ->numeric()
                            ->suffix('%')
                            ->minValue(1)
                            ->maxValue(100),
                        Forms\Components\DatePicker::make('tanggal_berakhir')
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('persentase')
                    ->sortable()
                    ->getStateUsing(fn(Voucher $voucher) => $voucher->persentase . '%'),
                Tables\Columns\TextColumn::make('tanggal_berakhir')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->getStateUsing(fn(Voucher $voucher) => Carbon::now()->lessThan(Carbon::parse($voucher->tanggal_berakhir)) ? 'Aktif' : 'Non-Aktif')
                    ->color(fn(string $state) => $state === 'Aktif' ? 'success' : 'danger'),
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
            'index' => Pages\ManageVouchers::route('/'),
        ];
    }
}
