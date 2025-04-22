<?php

namespace App\Filament\Admin\Resources;

use Exception;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\WithdrawRequest;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\WithdrawRequestResource\Pages;
use App\Filament\Admin\Resources\WithdrawRequestResource\RelationManagers;

class WithdrawRequestResource extends Resource
{
    protected static ?string $model = WithdrawRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Permintaan Withdraw';

    public static function canCreate(): bool
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
        return $form
            ->schema([
                // Forms\Components\TextInput::make('karyawan_id')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\TextInput::make('request_ammount')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\TextInput::make('status')
                //     ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('karyawan.name')
                    ->label('Karyawan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('request_ammount')
                    ->label('Jumlah Permintaan')
                    ->money('IDR')
                    ->weight(FontWeight::Bold)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($record) => match ($record->status) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->getStateUsing(
                        fn (WithdrawRequest $record): string => match ($record->status) {
                            'pending' => 'Menunggu',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                        }
                    )
                    ->sortable(),
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
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Menunggu',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                            ])
                            ->required(),
                    ])
                    ->action(function(WithdrawRequest $record, array $data)
                    {
                        DB::beginTransaction();
                        try
                        {
                            $record->update([
                                'status' => $data['status'],
                            ]);

                            if($record->status == 'approved')
                            {
                                $record->karyawan->withdraw($record->request_ammount);
                            }

                            DB::commit();
                            Notification::make()
                                ->title('Berhasil')
                                ->body('Berhasil mengupdate status withdraw')
                                ->success()
                                ->send();
                        } catch(Exception $e)
                        {
                            DB::rollBack();
                            Notification::make()
                                ->title('Gagal')
                                ->body('Gagal mengupdate status withdraw')
                                ->danger()
                                ->send();
                        }
                    })
                    ->hidden(fn (WithdrawRequest $record): bool => $record->status !== 'pending'),
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
            'index' => Pages\ManageWithdrawRequests::route('/'),
        ];
    }
}
