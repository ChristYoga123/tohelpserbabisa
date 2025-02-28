<?php

namespace App\Filament\Admin\Resources\KaryawanResource\Pages;

use App\Filament\Admin\Resources\KaryawanResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageKaryawans extends ManageRecords
{
    protected static string $resource = KaryawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->using(function(array $data)
                {
                    $karyawan = User::create([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'password' => bcrypt($data['password']),
                    ]);

                    $karyawan->assignRole('karyawan');
                }),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Karyawan';
    }
}
