<?php

namespace App\Filament\Admin\Resources\KaryawanResource\Widgets;

use App\Models\Absensi;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class AbsensiWidget extends BaseWidget
{
    public $karyawanId;

    protected function getStats(): array
    {
        $totalStandBy = Absensi::whereKaryawanId($this->karyawanId)
            ->count();
        return [
            Stat::make('Total Stand By', $totalStandBy),
        ];
    }
}
