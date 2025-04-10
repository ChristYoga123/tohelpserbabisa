<?php

namespace App\Livewire\TarifTransportasiPage;

use Livewire\Component;
use App\Models\TarifDasar;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\EditAction;

class TarifDasarComponent extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithTable, InteractsWithForms, InteractsWithActions;
    public function render()
    {
        return view('livewire.tarif-transportasi-page.tarif-dasar-component');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(TarifDasar::query())
            ->columns([
                TextColumn::make('jenis')
                    ->label('Tipe Transportasi'),
                TextColumn::make('harga')
                    ->money('IDR')
                    ->weight(FontWeight::Bold)
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        TextInput::make('harga')
                            ->numeric()
                            ->prefix('Rp')
                            ->suffix(',00')
                            ->required()
                            ->minValue(1)
                    ])
            ]);
    }
}
