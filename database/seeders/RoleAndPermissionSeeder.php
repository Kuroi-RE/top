<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Define all roles
        $roles = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin / Kemahasiswaan',
            'kemahasiswaan' => 'Kemahasiswaan',
            'dpmbem' => 'DPMBEM',
            'ormawa' => 'Ormawa',
            'ormawa_institusi' => 'Ormawa Institusi',
            'ormawa_prodi' => 'Ormawa Prodi',
            'mahasiswa' => 'Mahasiswa',
        ];

        // Create roles
        foreach ($roles as $slug => $name) {
            Role::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['name' => $name, 'guard_name' => 'web']
            );
        }

        // Dynamically gather all unique permissions from config/permissions.php
        $roleDefaults = config('permissions.role_defaults', []);
        $allPermissions = collect($roleDefaults)->flatten()->unique()->values();

        // Create permissions
        foreach ($allPermissions as $name) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['name' => $name, 'guard_name' => 'web']
            );
        }

        // Clear cache after creating permissions
        app()['cache']->forget('spatie.permission.cache');

        // Assign permissions to roles
        $this->assignPermissionsToRoles();

        // Migrate existing users to new role system
        $this->migrateExistingUsers();
    }

    private function assignPermissionsToRoles(): void
    {
        // KITA TIDAK LAGI ASSIGN PERMISSION KE ROLE.
        // Role dibiarkan kosong dari permission.
        // Semua permission akan di-assign langsung ke User (Direct Permissions).
        // Ini agar Kemahasiswaan bisa me-revoke permission spesifik dari user tertentu.
    }

    private function migrateExistingUsers(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $roleName = null;
            $configKey = null;
            if ($user->role === 'Super Admin') {
                $roleName = 'Super Admin';
                $configKey = 'Super Admin';
            } elseif ($user->role === 'Kemahasiswaan') {
                $roleName = 'Admin / Kemahasiswaan';
                $configKey = 'Admin / Kemahasiswaan';
            } elseif ($user->role === 'DPMBEM') {
                $roleName = 'DPMBEM';
                $configKey = 'DPMBEM';
            } elseif ($user->role === 'Ormawa') {
                $roleName = 'Ormawa';
                $configKey = 'Ormawa Institusi';
            } elseif ($user->role === 'Ormawa Institusi') {
                $roleName = 'Ormawa Institusi';
                $configKey = 'Ormawa Institusi';
            } elseif ($user->role === 'Ormawa Prodi') {
                $roleName = 'Ormawa Prodi';
                $configKey = 'Ormawa Prodi';
            } elseif ($user->role === 'Mahasiswa') {
                $roleName = 'Mahasiswa';
                $configKey = 'Mahasiswa';
            }

            if ($roleName) {
                // Assign role (sebagai label)
                $user->assignRole($roleName);

                // Assign direct permissions berdasarkan config
                $defaultPerms = config('permissions.role_defaults.' . $configKey, []);
                $user->syncPermissions($defaultPerms);
            }
        }
    }
}
