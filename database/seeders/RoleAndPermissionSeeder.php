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
            // Proposal permissions
            'create-proposal' => 'Create Proposal',
            'view-proposal' => 'View Proposal',
            'edit-proposal' => 'Edit Proposal',
            'delete-proposal' => 'Delete Proposal',
            'approve-proposal' => 'Approve Proposal',
            'reject-proposal' => 'Reject Proposal',

            // Revision Proposal permissions
            'view-revisi-proposal' => 'View Revision Proposal',
            'edit-revisi-proposal' => 'Edit Revision Proposal',
            'approve-revisi-proposal' => 'Approve Revision Proposal',

            // LPJ permissions
            'create-lpj' => 'Create LPJ',
            'view-lpj' => 'View LPJ',
            'edit-lpj' => 'Edit LPJ',
            'delete-lpj' => 'Delete LPJ',
            'approve-lpj' => 'Approve LPJ',
            'reject-lpj' => 'Reject LPJ',

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

            // Template Document permissions
            'manage-templates' => 'Manage Template Documents',
            'view-templates' => 'View Template Documents',

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
