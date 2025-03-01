<?php

namespace App\Filament\Admin\Resources\TransaksiResource\Pages;

use App\Models\Transaksi;
use Filament\Tables\Table;
use Filament\Resources\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions\Contracts\HasActions;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Filament\Admin\Resources\TransaksiResource;
use App\Models\KaryawanTugas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Notifications\Notification;

class TugasPage extends Page implements HasTable
{
    use InteractsWithTable;
    protected static string $resource = TransaksiResource::class;

    protected static string $view = 'filament.admin.resources.transaksi-resource.pages.tugas-page';

    public $transaksi;

    public function mount()
    {
        if(!Transaksi::find(request()->route('record'))){
            Notification::make()
                ->title('Error')
                ->body('Data tidak ditemukan')
                ->danger()
                ->send();
            return redirect()->route('filament.admin.resources.transaksis.index');
        }

        $this->transaksi = Transaksi::find(request()->route('record'));
    }

    public function getTitle(): string|Htmlable
    {
        return 'Pembagian Tugas';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(KaryawanTugas::with('karyawan')->where('tugas_id', $this->transaksi->id))
            ->columns([
                TextColumn::make('karyawan.name')
                    ->label('Nama Karyawan'),
                TextColumn::make('is_selesai')
                    ->label('Status')
                    ->getStateUsing(function (KaryawanTugas $karyawanTugas) {
                        return $karyawanTugas->is_selesai ? 'Selesai' : 'Belum Selesai';
                    })
                    ->badge()
                    ->color(fn (KaryawanTugas $karyawanTugas) => $karyawanTugas->is_selesai ? 'success' : 'warning'),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ]);
    }


}
