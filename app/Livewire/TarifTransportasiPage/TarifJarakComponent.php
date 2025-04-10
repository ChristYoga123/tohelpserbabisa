<?php

namespace App\Livewire\TarifTransportasiPage;

use Livewire\Component;
use App\Models\TarifJarak;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteAction;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;

class TarifJarakComponent extends Component implements HasTable, HasActions, HasForms
{
    use InteractsWithTable, InteractsWithForms, InteractsWithActions;

    public function render()
    {
        return view('livewire.tarif-transportasi-page.tarif-jarak-component');
    }

    public function createAction(): CreateAction
    {
        return CreateAction::make()
            ->label('Buat Tarif Jarak')
            ->form([
                Select::make('jenis')
                    ->label('Tipe Transportasi')
                    ->options([
                        'Motor' => 'Motor',
                        'Mobil' => 'Mobil',
                    ])
                    ->required(),
                Grid::make()
                    ->schema([
                        TextInput::make('jarak_min')
                            ->label('Jarak Minimum')
                            ->required()
                            ->suffix('km')
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('jarak_max')
                            ->label('Jarak Maksimum (Biarkan Kosong Jika Tidak Ada)')
                            ->suffix('km')
                            ->numeric()
                            ->minValue(0)
                            ->nullable(),
                    ]),
                TextInput::make('harga')
                    ->label('Harga')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp')
                    ->suffix(',-'),
            ])
            ->action(function(array $data)
            {
                TarifJarak::create([
                    'jenis' => $data['jenis'],
                    'jarak_min' => $data['jarak_min'],
                    'jarak_max' => $data['jarak_max'],
                    'harga' => $data['harga'],
                ]);

                Notification::make()
                    ->title('Sukses')
                    ->body('Tarif Jarak Berhasil Dibuat')
                    ->success()
                    ->send();
            });
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(TarifJarak::query())
            ->columns([
                TextColumn::make('jenis')
                    ->label('Tipe Transportasi'),
                TextColumn::make('jarak_min')
                    ->label('Jarak Minimum')
                    ->suffix('km'),
                TextColumn::make('jarak_max')
                    ->label('Jarak Maksimum')
                    ->suffix(fn(TarifJarak $record) => $record->jarak_max ? 'km' : null)
                    ->getStateUsing(fn(TarifJarak $record) => $record->jarak_max ?? 'Seterusnya'),
                TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->weight(FontWeight::Bold),
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        Select::make('jenis')
                            ->label('Tipe Transportasi')
                            ->options([
                                'Motor' => 'Motor',
                                'Mobil' => 'Mobil',
                            ])
                            ->required(),
                        Grid::make()
                            ->schema([
                                TextInput::make('jarak_min')
                                    ->label('Jarak Minimum')
                                    ->required()
                                    ->suffix('km')
                                    ->numeric()
                                    ->minValue(0),
                                TextInput::make('jarak_max')
                                    ->label('Jarak Maksimum (Biarkan Kosong Jika Tidak Ada)')
                                    ->suffix('km')
                                    ->numeric()
                                    ->minValue(0)
                                    ->nullable(),
                            ]),
                        TextInput::make('harga')
                            ->label('Harga')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->suffix('IDR'),
                    ]),
                DeleteAction::make(),
        ]);
    }
}
