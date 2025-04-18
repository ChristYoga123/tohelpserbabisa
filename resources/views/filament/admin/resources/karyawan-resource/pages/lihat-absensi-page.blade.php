<x-filament-panels::page>
    @livewire(\App\Filament\Admin\Resources\KaryawanResource\Widgets\AbsensiWidget::class, ['karyawanId' => $record->id])
    {{ $this->table }}
</x-filament-panels::page>
