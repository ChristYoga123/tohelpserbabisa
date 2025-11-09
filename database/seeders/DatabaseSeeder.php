<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RoleSeeder::class,
            CabangSeeder::class,
            TarifDasarSeeder::class,
        ]);
        User::create([
            'name' => 'super_admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'cabang_id' => 1,
        ])->assignRole(Role::all());
    }
}
