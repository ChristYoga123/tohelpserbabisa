<?php

namespace App\Filament\Karyawan\Resources\WithdrawRequestResource\Pages;

use App\Filament\Karyawan\Resources\WithdrawRequestResource;
use App\Models\WithdrawRequest;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageWithdrawRequests extends ManageRecords
{
    protected static string $resource = WithdrawRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->action(function(array $data)
                {
                    if(Auth::user()->balance < $data['request_ammount'])
                    {
                        Notification::make()
                            ->title('Gagal')
                            ->body('Saldo tidak mencukupi untuk melakukan withdraw')
                            ->danger()
                            ->send();
                        return;
                    }

                    DB::beginTransaction();
                    try {
                        WithdrawRequest::create([
                            'karyawan_id' => Auth::user()->id,
                            'request_ammount' => $data['request_ammount'],
                        ]);

                        DB::commit();

                        Notification::make()
                            ->title('Berhasil')
                            ->body('Permintaan withdraw berhasil dibuat')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Notification::make()
                            ->title('Gagal')
                            ->body('Terjadi kesalahan saat membuat permintaan withdraw')
                            ->danger()
                            ->send();
                    }
                })
                ->createAnother(false),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return __('Permintaan Withdraw');
    }
}
