<?php

namespace App\Filament\Karyawan\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SaldoWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
            Stat::make('Saldo Saya', 'Rp' . number_format(auth()->user()->balanceInt, 0, ',', '.'))
                ->icon('heroicon-o-currency-dollar')
                ->color('success')
                ->description('Saldo Anda saat ini'),
        ];
    }
}
