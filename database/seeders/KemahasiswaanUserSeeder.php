<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KemahasiswaanUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()
            ->where('username', 'kemahasiswaan')
            ->orWhere('email', 'kemahasiswaan@top.test')
            ->first();

        if (!$user) {
            $user = new User();
        }

        $user->fill([
            'username' => 'kemahasiswaan',
            'email' => 'kemahasiswaan@top.test',
            'nim' => null,
            'nama_depan' => 'Admin',
            'nama_belakang' => 'Kemahasiswaan',
            'prodi' => null,
            'password' => Hash::make('password123'),
            'role' => 'Kemahasiswaan',
            'is_active' => true,
        ]);

        $user->save();

        $user->assignRole('Admin / Kemahasiswaan');
        $user->syncPermissions(config('permissions.role_defaults.Admin / Kemahasiswaan', []));
    }
}