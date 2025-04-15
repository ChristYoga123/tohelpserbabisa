<?php

namespace App\Filament\Admin\Pages;

use App\Models\AbsensiBase;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class AbsensiBasePage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Waktu Absensi';

    protected static string $view = 'filament.admin.pages.absensi-base-page';

    public ?array $data = [];
    public function mount()
    {
        $data = AbsensiBase::first();
        if ($data) {
            $this->data = [
                'jam_masuk' => $data->jam_masuk,
                'jam_keluar' => $data->jam_keluar,
            ];
        } else {
            $this->data = [
                'jam_masuk' => now()->format('H:i'),
                'jam_keluar' => now()->format('H:i'),
            ];
        }
        
        $this->form->fill([
            'jam_masuk' => $this->data['jam_masuk'],
            'jam_keluar' => $this->data['jam_keluar'],
        ]);
    }

    public function getTitle(): string|Htmlable
    {
        return 'Master Data Absensi';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TimePicker::make('jam_masuk')
                    ->label('Jam Masuk')
                    ->required()
                    ->default(now()->format('H:i')),
                TimePicker::make('jam_keluar')
                    ->label('Jam Keluar')
                    ->required()
                    ->default(now()->format('H:i')),
            ])
            ->statePath('data');
    }

    public function save()
    {
        AbsensiBase::updateOrCreate([
            'id' => 1,
        ], [
            'jam_masuk' => $this->data['jam_masuk'],
            'jam_keluar' => $this->data['jam_keluar'],
        ]);

        Notification::make()
            ->title('Berhasil')
            ->body('Data Absensi Berhasil Disimpan')
            ->success()
            ->send();
    }
}
