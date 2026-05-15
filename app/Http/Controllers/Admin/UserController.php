<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
            return redirect()->route('login');
        }

        $query = User::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_depan', 'like', "%{$search}%")
                  ->orWhere('nama_belakang', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        $users = $query->latest('id_user')->paginate($request->per_page ?? 10);

        return view('admin.manajemen_user', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $currentUser = auth()->user();
        if (!$currentUser || (!$currentUser->isAdmin() && !$currentUser->isSuperAdmin())) {
            return back()->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'role' => 'required|string|in:Kemahasiswaan,DPMBEM,Ormawa,Ormawa Institusi,Ormawa Prodi,Mahasiswa,Super Admin',
            'ormawa_type' => 'nullable|required_if:role,Ormawa|in:institusi,prodi',
            'ormawa_name' => 'nullable|required_if:role,Ormawa,Ormawa Institusi,Ormawa Prodi|string|max:255',
        ], [
            'ormawa_type.required_if' => 'Tipe ormawa harus dipilih jika role adalah Ormawa.',
            'ormawa_name.required_if' => 'Nama ormawa harus diisi jika role adalah Ormawa.',
        ]);

        try {
            $role = trim($request->role);
            $ormawaType = $request->ormawa_type;

            if ($role === 'Ormawa Institusi') {
                $role = 'Ormawa Institusi';
                $ormawaType = 'institusi';
            } elseif ($role === 'Ormawa Prodi') {
                $role = 'Ormawa Prodi';
                $ormawaType = 'prodi';
            }

            $data = [
                'role' => $role,
                'ormawa_type' => in_array($role, ['Ormawa', 'Ormawa Institusi', 'Ormawa Prodi']) ? ($ormawaType ?: $request->ormawa_type) : null,
                'ormawa_name' => in_array($role, ['Ormawa', 'Ormawa Institusi', 'Ormawa Prodi']) ? $request->ormawa_name : null,
            ];

            $user->update($data);

            // Sync Spatie Role
            $user->syncRoles([$role]);

            // Sync Default Permissions from config
            $defaultPerms = config('permissions.role_defaults.' . $role, []);
            $user->syncPermissions($defaultPerms);

            return back()->with('success', "Role user {$user->username} berhasil diperbarui ke {$role}.");
        } catch (\Exception $e) {
            Log::error('Error updating user role: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui role.');
        }
    }
}
