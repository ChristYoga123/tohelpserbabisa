<?php

namespace App\Filament\Admin\Resources\KaryawanResource\Pages;

use Exception;
use App\Models\User;
use Filament\Actions;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Admin\Resources\KaryawanResource;
use Filament\Notifications\Notification;

class ManageKaryawans extends ManageRecords
{
    protected static string $resource = KaryawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->using(function(array $data)
                {
                    DB::beginTransaction();
                    try
                    {
                        $karyawan = User::create([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'password' => bcrypt($data['password']),
                            'custom_fields' => [
                                'tanggal_lahir' => $data['tanggal_lahir'],
                            ],
                            'avatar_url' => $data['avatar_url'],
                        ]);
    
                        $karyawan->assignRole('karyawan');

                        DB::commit();

                        Notification::make()
                            ->title('Sukses!')
                            ->body('Tambah karyawan berhasil!')
                            ->success()
                            ->send();
                    } catch(Exception $e)
                    {
                        DB::rollBack();

                        Notification::make()
                            ->title('Gagal!')
                            ->body('Tambah karyawan gagal!')
                            ->error()
                            ->send();
                    }
                }),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Karyawan';
    }
}
