<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed roles and permissions only. Sample users/data are intentionally omitted
        // so the application starts empty and requires real registration,
        // except for the default Kemahasiswaan login account.
        $this->call(RoleAndPermissionSeeder::class);
        $this->call(KemahasiswaanUserSeeder::class);
    }
}
