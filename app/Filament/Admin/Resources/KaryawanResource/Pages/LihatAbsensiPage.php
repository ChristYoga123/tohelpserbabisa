<?php

namespace App\Filament\Admin\Resources\KaryawanResource\Pages;

use App\Filament\Admin\Resources\KaryawanResource;
use App\Models\Absensi;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class LihatAbsensiPage extends Page implements HasTable
{
    use InteractsWithTable, InteractsWithRecord;

    protected static string $resource = KaryawanResource::class;

    protected static string $view = 'filament.admin.resources.karyawan-resource.pages.lihat-absensi-page';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getTitle(): string|Htmlable
    {
        return 'Lihat Absensi ' . ucwords($this->record->name);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Absensi::with('karyawan')->where('karyawan_id', $this->record->id))
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu Stand By')
                    ->dateTime('l, j F Y H:i:s'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color('success')
                    ->getStateUsing(fn() => 'Stand By')
            ])
            ->actions([
                Action::make('lihatBuktiAbsensi')
                    ->button()
                    ->label('Lihat Bukti Absensi')
                    ->color('info')
                    ->icon('heroicon-o-document')
                    ->infolist([
                        Grid::make()
                            ->columns(1)
                            ->schema([
                                SpatieMediaLibraryImageEntry::make('absen')
                                    ->collection('bukti-absensi')
                                    ->columnSpanFull()
                                    ->width('xl')
                            ])
                ])
                ->modalSubmitAction(false)
                ->modalCancelAction(false),
            ]);
    }
}
