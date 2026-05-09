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
            'mahasiswa' => 'Mahasiswa',
        ];

        // Create roles
        foreach ($roles as $slug => $name) {
            Role::firstOrCreate(
                ['name' => $name, 'guard_name' => 'api'],
                ['name' => $name, 'guard_name' => 'api']
            );
        }

        // Define permissions
        $permissions = [
            // Proposal Kegiatan permissions
            'create-proposal' => 'Create Proposal Kegiatan',
            'view-proposal' => 'View Proposal Kegiatan',
            'edit-proposal' => 'Edit Proposal Kegiatan',
            'delete-proposal' => 'Delete Proposal Kegiatan',
            'approve-proposal' => 'Approve Proposal Kegiatan',
            'reject-proposal' => 'Reject Proposal Kegiatan',

            // Revisi Proposal permissions
            'view-revisi-proposal' => 'View Revisi Proposal',
            'edit-revisi-proposal' => 'Edit Revisi Proposal',
            'approve-revisi-proposal' => 'Approve Revisi Proposal',

            // LPJ Kegiatan permissions
            'create-lpj' => 'Create LPJ Kegiatan',
            'view-lpj' => 'View LPJ Kegiatan',
            'edit-lpj' => 'Edit LPJ Kegiatan',
            'delete-lpj' => 'Delete LPJ Kegiatan',
            'approve-lpj' => 'Approve LPJ Kegiatan',
            'reject-lpj' => 'Reject LPJ Kegiatan',

            // Prestasi permissions
            'create-prestasi' => 'Create Prestasi',
            'view-prestasi' => 'View Prestasi',
            'edit-prestasi' => 'Edit Prestasi',
            'delete-prestasi' => 'Delete Prestasi',
            'approve-prestasi' => 'Approve Prestasi',
            'reject-prestasi' => 'Reject Prestasi',

            // User Management permissions
            'view-users' => 'View Users',
            'create-users' => 'Create Users',
            'edit-users' => 'Edit Users',
            'delete-users' => 'Delete Users',

            // Template Dokumen permissions
            'manage-templates' => 'Manage Template Dokumen',
            'view-templates' => 'View Template Dokumen',

            // Reports
            'view-reports' => 'View Reports',
            'export-reports' => 'Export Reports',
        ];

        // Create permissions
        foreach ($permissions as $slug => $name) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'api'],
                ['name' => $name, 'guard_name' => 'api']
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
            if ($user->role === 'Super Admin') {
                $roleName = 'Super Admin';
            } elseif ($user->role === 'Kemahasiswaan') {
                $roleName = 'Admin / Kemahasiswaan';
            } elseif ($user->role === 'DPMBEM') {
                $roleName = 'DPMBEM';
            } elseif ($user->role === 'Ormawa') {
                $roleName = 'Ormawa';
            } elseif ($user->role === 'Mahasiswa') {
                $roleName = 'Mahasiswa';
            }

            if ($roleName) {
                // Assign role (sebagai label)
                $user->assignRole($roleName);

                // Assign direct permissions berdasarkan config
                $defaultPerms = config('permissions.role_defaults.' . $roleName, []);
                $user->syncPermissions($defaultPerms);
            }
        }
    }
}
