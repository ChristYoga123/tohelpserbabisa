<?php

namespace App\Filament\Karyawan\Resources\KaryawanTugasResource\Pages;

use App\Filament\Karyawan\Resources\KaryawanTugasResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageKaryawanTugas extends ManageRecords
{
    protected static string $resource = KaryawanTugasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
