<?php

namespace Database\Seeders;

use App\Models\TarifDasar;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TarifDasarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TarifDasar::create([
            'harga' => 1000,
            'jenis' => 'Motor',
        ]);

        TarifDasar::create([
            'harga' => 2000,
            'jenis' => 'Mobil',
        ]);
    }
}
