@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<style>
    .page-container {
        animation: fade-in 0.6s ease-out both;
    }

    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .premium-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        border: 1px solid rgba(2,6,23,0.05);
        overflow: hidden;
    }

    .search-input-premium {
        background: #f8fafc;
        border: 1px solid rgba(2,6,23,0.08);
        border-radius: 14px;
        padding: 10px 16px 10px 44px;
        transition: all 0.2s ease;
        outline: none;
        width: 100%;
    }

    .search-input-premium:focus {
        background: #ffffff;
        border-color: #c1121f;
        box-shadow: 0 0 0 4px rgba(193, 18, 31, 0.08);
    }

    .table-premium {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-premium th {
        background: #f8fafc;
        padding: 16px 20px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        color: #64748b;
        border-bottom: 1px solid rgba(2,6,23,0.05);
    }

    .table-premium td {
        padding: 16px 20px;
        border-bottom: 1px solid rgba(2,6,23,0.03);
        color: #334155;
        font-size: 0.875rem;
    }

    .table-premium tr:hover td {
        background-color: #fbfdff;
    }

    .role-pill {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.025em;
    }

    .role-pill-admin { background: #fef2f2; color: #991b1b; border: 1px solid #fee2e2; }
    .role-pill-ormawa { background: #eff6ff; color: #1e40af; border: 1px solid #dbeafe; }
    .role-pill-mahasiswa { background: #f0fdf4; color: #166534; border: 1px solid #dcfce7; }
    .role-pill-dpmbem { background: #faf5ff; color: #6b21a8; border: 1px solid #f3e8ff; }

    .btn-action {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
        color: #64748b;
    }

    .btn-action:hover {
        background: #f1f5f9;
        color: #c1121f;
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(4px);
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-content {
        background: #fff;
        border-radius: 24px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        transform: scale(0.95);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-overlay.active .modal-content {
        transform: scale(1);
        opacity: 1;
    }

    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 8px;
        margin-left: 4px;
    }

    .form-select-premium, .form-input-modal {
        width: 100%;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px;
        font-size: 0.9rem;
        color: #1e293b;
        outline: none;
        transition: border-color 0.2s;
    }

    .form-select-premium:focus, .form-input-modal:focus {
        border-color: #c1121f;
        box-shadow: 0 0 0 3px rgba(193, 18, 31, 0.1);
    }
</style>

<div class="page-container">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen User</h1>
            <p class="text-slate-500 mt-1">Kelola role dan hak akses pengguna dalam sistem</p>
        </div>
    </div>

    <div class="premium-card p-6 mb-8 bg-white">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama, NIM, atau Username..." class="search-input-premium">
            </div>
            <div class="w-full md:w-48">
                <select name="role" onchange="this.form.submit()" class="search-input-premium pl-4">
                    <option value="">Semua Role</option>
                    <option value="Kemahasiswaan" {{ request('role') == 'Kemahasiswaan' ? 'selected' : '' }}>Kemahasiswaan</option>
                    <option value="DPMBEM" {{ request('role') == 'DPMBEM' ? 'selected' : '' }}>DPM/BEM</option>
                    <option value="Ormawa Institusi" {{ request('role') == 'Ormawa Institusi' ? 'selected' : '' }}>Ormawa Institusi</option>
                    <option value="Ormawa Prodi" {{ request('role') == 'Ormawa Prodi' ? 'selected' : '' }}>Ormawa Prodi</option>
                    <option value="Mahasiswa" {{ request('role') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">
                Filter
            </button>
        </form>
    </div>

    <div class="premium-card">
        <div class="overflow-x-auto">
            <table class="table-premium">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Username / NIM</th>
                        <th>Role Saat Ini</th>
                        <th>Detail Ormawa</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        @php
                            $trimmedRole = trim($user->role);
                            $roleClass = match($trimmedRole) {
                                'Super Admin', 'Kemahasiswaan' => 'role-pill-admin',
                                'Ormawa', 'Ormawa Institusi', 'Ormawa Prodi' => 'role-pill-ormawa',
                                'DPMBEM' => 'role-pill-dpmbem',
                                default => 'role-pill-mahasiswa',
                            };
                        @endphp
                        <tr>
                            <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800">{{ $user->nama_depan }} {{ $user->nama_belakang }}</span>
                                    <span class="text-xs text-slate-500">{{ $user->email }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="font-semibold">{{ $user->username }}</span>
                                    <span class="text-xs text-slate-500">{{ $user->nim ?: '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="role-pill {{ $roleClass }}">{{ $user->role }}</span>
                            </td>
                            <td>
                                @if(in_array($trimmedRole, ['Ormawa', 'Ormawa Institusi', 'Ormawa Prodi']))
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-600">{{ strtoupper($user->ormawa_type ?: ($trimmedRole === 'Ormawa Institusi' ? 'institusi' : 'prodi')) }}</span>
                                        <span class="text-sm">{{ $user->ormawa_name ?: '-' }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-300">&mdash;</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($user->is_active ?? true)
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700">Aktif</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-700">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" 
                                        onclick="openRoleModal({{ json_encode($user) }})"
                                        class="btn-action" title="Ubah Role">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                     <form id="toggle-form-{{ $user->id_user }}" action="{{ route('admin.users.toggle_active', $user->id_user) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="button"
                                            data-toggle-akses="{{ $user->id_user }}"
                                            data-is-active="{{ ($user->is_active ?? true) ? '1' : '0' }}"
                                            class="btn-action {{ ($user->is_active ?? true) ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50' }}"
                                            title="{{ ($user->is_active ?? true) ? 'Nonaktifkan' : 'Aktifkan' }} Akun">
                                            @if($user->is_active ?? true)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400 italic">Data user tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="p-6 bg-slate-50/50 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Assign Role Modal -->
<div id="roleModal" class="modal-overlay" onclick="if(event.target === this) closeRoleModal()">
    <div class="modal-content">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-xl font-bold text-slate-900">Assign Role User</h3>
            <button onclick="closeRoleModal()" class="text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="roleForm" method="POST" class="p-6 space-y-6">
            @csrf
            <div>
                <p class="text-sm text-slate-600 mb-2">Mengubah role untuk user: <span id="modalUserName" class="font-bold text-slate-900"></span></p>
                <div class="p-3 bg-red-50 rounded-xl border border-red-100 text-xs text-red-700 leading-relaxed">
                    <strong>Penting:</strong> Mengubah role akan memperbarui hak akses (permissions) user tersebut sesuai standar yang ditetapkan.
                </div>
            </div>

            <div>
                <label class="form-label">Pilih Role Baru</label>
                <select name="role" id="roleSelect" class="form-select-premium" required onchange="toggleOrmawaFields()">
                    <option value="Mahasiswa">Mahasiswa</option>
                    <option value="Ormawa Institusi">Ormawa Institusi (UKM/Institusi)</option>
                    <option value="Ormawa Prodi">Ormawa Prodi (Himpunan/Prodi)</option>
                    <option value="DPMBEM">DPM / BEM</option>
                    <option value="Kemahasiswaan">Kemahasiswaan</option>
                </select>
            </div>

            <div id="ormawaFields" style="display: none;" class="space-y-4 pt-2 border-t border-slate-50">
                <div id="ormawaTypeContainer">
                    <label class="form-label">Tipe Ormawa</label>
                    <select name="ormawa_type" id="ormawaTypeSelect" class="form-select-premium" onchange="toggleOrmawaFields()">
                        <option value="institusi">Institusi (UKM)</option>
                        <option value="prodi">Program Studi (Himpunan)</option>
                    </select>
                </div>
                <div id="ormawaNameSelectContainer" style="display: none;">
                    <label class="form-label">Nama Himpunan</label>
                    <select id="ormawaNameSelect" class="form-select-premium">
                        <option value="">-- Pilih Himpunan --</option>
                        <option value="HMIF">HMIF</option>
                        <option value="HMTI">HMTI</option>
                        <option value="HMDKV">HMDKV</option>
                        <option value="HMSE">HMSE</option>
                        <option value="HMTB">HMTB</option>
                        <option value="HMTT">HMTT</option>
                        <option value="HMTE">HMTE</option>
                        <option value="HMTP">HMTP</option>
                        <option value="HMDP">HMDP</option>
                        <option value="HMSD">HMSD</option>
                        <option value="HMTL">HMTL</option>
                        <option value="HMBD">HMBD</option>
                        <option value="HMDT">HMDT</option>
                    </select>
                </div>
                <div id="ormawaUkmSelectContainer" style="display: none;">
                    <label class="form-label">Nama UKM</label>
                    <select id="ormawaUkmSelect" class="form-select-premium">
                        <option value="">-- Pilih UKM --</option>
                        <option value="GDGOC">GDGOC</option>
                        <option value="KSPM">KSPM</option>
                        <option value="SRE">SRE</option>
                        <option value="KSR">KSR</option>
                        <option value="HIPMI">HIPMI</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeRoleModal()" class="flex-1 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-2 px-8 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-all shadow-lg shadow-red-200">
                    Simpan Role
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ── State ────────────────────────────────────────────────────────────────
    let _currentUserId = null;

    // ── Modal: Open / Close ──────────────────────────────────────────────────
    function openRoleModal(user) {
        const modal = document.getElementById('roleModal');
        const form = document.getElementById('roleForm');
        const nameSpan = document.getElementById('modalUserName');

        _currentUserId = user.id_user;

        nameSpan.textContent = user.nama_depan + ' ' + (user.nama_belakang || '') + ' (' + user.username + ')';
        // Simpan juga sebagai fallback web route
        form.action = `/admin/users/${user.id_user}/role`;

        document.getElementById('roleSelect').value = user.role || 'Mahasiswa';
        document.getElementById('ormawaTypeSelect').value = user.ormawa_type || 'institusi';

        const ormawaName = user.ormawa_name || '';

        // Himpunan matching
        const himpunanSelect = document.getElementById('ormawaNameSelect');
        let himpunanFound = false;
        for (let i = 0; i < himpunanSelect.options.length; i++) {
            if (himpunanSelect.options[i].value.toLowerCase() === ormawaName.toLowerCase()) {
                himpunanSelect.selectedIndex = i;
                himpunanFound = true;
                break;
            }
        }
        if (!himpunanFound) himpunanSelect.value = '';

        // UKM matching
        const ukmSelect = document.getElementById('ormawaUkmSelect');
        let ukmFound = false;
        for (let i = 0; i < ukmSelect.options.length; i++) {
            if (ukmSelect.options[i].value.toLowerCase() === ormawaName.toLowerCase()) {
                ukmSelect.selectedIndex = i;
                ukmFound = true;
                break;
            }
        }
        if (!ukmFound) ukmSelect.value = '';

        toggleOrmawaFields();
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeRoleModal() {
        const modal = document.getElementById('roleModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
        _currentUserId = null;
    }

    function toggleOrmawaFields() {
        const role = document.getElementById('roleSelect').value;
        const fields = document.getElementById('ormawaFields');
        const typeContainer = document.getElementById('ormawaTypeContainer');
        const typeSelect = document.getElementById('ormawaTypeSelect');

        const himpunanContainer = document.getElementById('ormawaNameSelectContainer');
        const himpunanEl = document.getElementById('ormawaNameSelect');
        const ukmContainer = document.getElementById('ormawaUkmSelectContainer');
        const ukmEl = document.getElementById('ormawaUkmSelect');

        const isAnyOrmawa = ['Ormawa', 'Ormawa Institusi', 'Ormawa Prodi'].includes(role);

        if (isAnyOrmawa) {
            fields.style.display = 'block';

            let currentType = '';
            if (role === 'Ormawa Institusi') {
                typeContainer.style.display = 'none';
                typeSelect.required = false;
                currentType = 'institusi';
            } else if (role === 'Ormawa Prodi') {
                typeContainer.style.display = 'none';
                typeSelect.required = false;
                currentType = 'prodi';
            } else {
                typeContainer.style.display = 'block';
                typeSelect.required = true;
                currentType = typeSelect.value;
            }

            if (currentType === 'prodi') {
                himpunanContainer.style.display = 'block';
                himpunanEl.name = 'ormawa_name';
                himpunanEl.required = true;
                ukmContainer.style.display = 'none';
                ukmEl.removeAttribute('name');
                ukmEl.required = false;
            } else {
                ukmContainer.style.display = 'block';
                ukmEl.name = 'ormawa_name';
                ukmEl.required = true;
                himpunanContainer.style.display = 'none';
                himpunanEl.removeAttribute('name');
                himpunanEl.required = false;
            }
        } else {
            fields.style.display = 'none';
            typeSelect.required = false;
            himpunanEl.removeAttribute('name');
            himpunanEl.required = false;
            ukmEl.removeAttribute('name');
            ukmEl.required = false;
        }
    }

    // ── API: Submit Role via PATCH /api/v1/users/{id}/assign-role ────────────
    document.addEventListener('DOMContentLoaded', function () {
        const roleForm = document.getElementById('roleForm');
        if (!roleForm) return;

        roleForm.addEventListener('submit', function (e) {
            const token = localStorage.getItem('topkema_api_token');
            const userId = _currentUserId;

            // Jika tidak ada token, fallback ke form POST web (default action)
            if (!token || !userId || !window.axios) {
                return; // biarkan form submit normal
            }

            e.preventDefault(); // prevent default form submission

            const role = document.getElementById('roleSelect').value;
            const ormawaNameEl = roleForm.querySelector('[name="ormawa_name"]');
            const ormawaName = ormawaNameEl ? ormawaNameEl.value : null;
            const ormawaType = document.getElementById('ormawaTypeSelect').value;

            const payload = { role };
            if (['Ormawa', 'Ormawa Institusi', 'Ormawa Prodi'].includes(role)) {
                // Map role ke format API
                const apiRole = role === 'Ormawa Institusi' ? 'Ormawa Institusi'
                              : role === 'Ormawa Prodi'   ? 'Ormawa Prodi'
                              : 'Ormawa';
                payload.role = apiRole;
                if (ormawaName) payload.ormawa_name = ormawaName;
                if (ormawaType) payload.ormawa_type = role === 'Ormawa' ? ormawaType : (role === 'Ormawa Institusi' ? 'institusi' : 'prodi');
            }

            const submitBtn = roleForm.querySelector('[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';

            window.axios.patch(`users/${userId}/assign-role`, payload)
                .then(function (response) {
                    closeRoleModal();
                    // Tampilkan notifikasi sukses lalu reload
                    showApiToast('success', 'Role berhasil diperbarui.');
                    setTimeout(() => window.location.reload(), 1200);
                })
                .catch(function (error) {
                    const msg = error?.response?.data?.message || 'Gagal memperbarui role.';
                    showApiToast('error', msg);
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
        });

        // ── API: Toggle Akses via PATCH /api/v1/users/{id}/toggle-akses ──────
        document.querySelectorAll('[data-toggle-akses]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                const token = localStorage.getItem('topkema_api_token');
                const userId = btn.getAttribute('data-toggle-akses');
                const isActive = btn.getAttribute('data-is-active') === '1';
                const confirmMsg = isActive
                    ? 'Nonaktifkan akun ini?'
                    : 'Aktifkan kembali akun ini?';

                if (!confirm(confirmMsg)) return;

                if (!token || !window.axios) {
                    // Fallback: submit form web
                    const fallbackForm = document.getElementById(`toggle-form-${userId}`);
                    if (fallbackForm) fallbackForm.submit();
                    return;
                }

                e.preventDefault();

                window.axios.patch(`users/${userId}/toggle-akses`)
                    .then(function () {
                        showApiToast('success', isActive ? 'Akun berhasil dinonaktifkan.' : 'Akun berhasil diaktifkan.');
                        setTimeout(() => window.location.reload(), 1200);
                    })
                    .catch(function (error) {
                        const msg = error?.response?.data?.message || 'Gagal mengubah status akun.';
                        showApiToast('error', msg);
                    });
            });
        });
    });

    // ── Toast Notifikasi ─────────────────────────────────────────────────────
    function showApiToast(type, message) {
        const existing = document.getElementById('api-toast');
        if (existing) existing.remove();

        const colors = type === 'success'
            ? { bg: '#f0fdf4', border: '#bbf7d0', text: '#166534', icon: '✓' }
            : { bg: '#fef2f2', border: '#fecaca', text: '#991b1b', icon: '✕' };

        const toast = document.createElement('div');
        toast.id = 'api-toast';
        toast.style.cssText = `
            position: fixed; top: 24px; left: 50%; transform: translateX(-50%);
            z-index: 9999; display: flex; align-items: center; gap: 12px;
            background: ${colors.bg}; border: 1px solid ${colors.border};
            color: ${colors.text}; padding: 14px 22px; border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); font-size: 14px; font-weight: 600;
            animation: slideDown 0.3s ease;
        `;
        toast.innerHTML = `<span style="font-size:18px;">${colors.icon}</span><span>${message}</span>`;

        const style = document.createElement('style');
        style.textContent = '@keyframes slideDown { from { opacity:0; transform:translateX(-50%) translateY(-10px); } to { opacity:1; transform:translateX(-50%) translateY(0); } }';
        document.head.appendChild(style);

        document.body.appendChild(toast);
        setTimeout(() => { if (toast.parentNode) toast.remove(); }, 4000);
    }

    // Handle ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeRoleModal();
    });
</script>
@endpush
