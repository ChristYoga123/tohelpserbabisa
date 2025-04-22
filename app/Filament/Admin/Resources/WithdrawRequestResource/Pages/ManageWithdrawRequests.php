<?php

namespace App\Filament\Admin\Resources\WithdrawRequestResource\Pages;

use App\Filament\Admin\Resources\WithdrawRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageWithdrawRequests extends ManageRecords
{
    protected static string $resource = WithdrawRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
