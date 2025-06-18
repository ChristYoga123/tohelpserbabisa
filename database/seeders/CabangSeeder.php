<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cabang::firstOrCreate([
            'nama' => 'Jember',
            'lat' => -8.171121371857444,
            'lng' => 113.7233082269837,
        ]);
    }
}
