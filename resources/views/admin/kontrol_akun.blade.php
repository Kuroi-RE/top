@extends('layouts.app')

@section('title', 'Kontrol Akun')

@section('content')
<style>
    .control-panel {
        max-width: 1200px;
        margin: 0 auto;
        animation: fade-in 0.6s ease-out both;
    }

    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .premium-card {
        background: linear-gradient(135deg, #ffffff 0%, #fbfdff 100%);
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        border: 1px solid rgba(2,6,23,0.05);
        overflow: hidden;
    }

    .form-input-premium {
        background: #f8fafc;
        border: 1px solid rgba(2,6,23,0.08);
        border-radius: 14px;
        padding: 12px 16px;
        transition: all 0.2s ease;
        outline: none;
        width: 100%;
        box-sizing: border-box;
    }

    .form-input-premium:focus {
        background: #ffffff;
        border-color: #c1121f;
        box-shadow: 0 0 0 4px rgba(193, 18, 31, 0.08);
    }

    .input-container {
        position: relative;
        width: 100%;
    }

    .input-container .icon-left {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .input-container .icon-right {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .input-container input.has-icon-left {
        padding-left: 44px !important;
    }

    .input-container select.has-icon-right {
        padding-right: 44px !important;
    }

    .premium-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .premium-table th {
        background: #f8fafc;
        padding: 16px 24px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #64748b;
        border-bottom: 1px solid rgba(2,6,23,0.05);
    }

    .premium-table td {
        padding: 18px 24px;
        border-bottom: 1px solid rgba(2,6,23,0.03);
        color: #1e293b;
        font-weight: 500;
        transition: background-color 0.2s ease;
    }

    .premium-table tr:hover td {
        background-color: #fbfdff;
    }

    /* Modern Toggle */
    .switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #e2e8f0;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    input:checked + .slider {
        background: linear-gradient(135deg, #c1121f 0%, #780116 100%);
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #c1121f;
    }

    input:checked + .slider:before {
        transform: translateX(24px);
    }

    .category-header {
        background: #f1f5f9;
        font-weight: 800;
        color: #334155;
        font-size: 0.85rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 10px 24px !important;
    }

    /* Toast styling */
    .toast-notification {
        position: fixed;
        top: 24px;
        right: 24px;
        background: rgba(15, 23, 42, 0.9);
        backdrop-filter: blur(8px);
        color: white;
        padding: 16px 24px;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 12px;
        transform: translateY(-20px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        pointer-events: none;
    }

    .toast-notification.active {
        transform: translateY(0);
        opacity: 1;
        pointer-events: auto;
    }

    .toast-icon {
        width: 24px;
        height: 24px;
        background: #10b981;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }
</style>

<div class="control-panel">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Kontrol Akun</h1>
            <p class="text-slate-500 mt-1">Kelola perizinan fitur untuk masing-masing pengguna secara individual</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="premium-card p-6 mb-8 bg-white">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kolom 1: Cari Pengguna (universal) -->
            <div class="flex flex-col gap-2 relative" id="user-filter-container">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Cari Pengguna</label>
                <div class="input-container">
                    <input type="text" id="user-search-input" placeholder="Cari nama, NIM, username, atau email..." class="form-input-premium has-icon-left" autocomplete="off">
                    <div class="icon-left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <!-- Dynamic Search Results Dropdown -->
                <div id="user-search-results" class="absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 max-h-72 overflow-y-auto hidden divide-y divide-slate-100">
                    <div class="p-3 text-xs text-slate-400 italic text-center">Mengetik pencarian...</div>
                </div>
                <!-- Selected User Pill -->
                <div id="selected-user-pill" class="flex items-center justify-between bg-red-50/60 border border-red-100/80 rounded-2xl p-3 mt-0.5 hidden">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-full bg-red-100/80 flex items-center justify-center text-red-700 font-bold text-xs" id="selected-user-avatar">U</div>
                        <div class="flex flex-col">
                            <span id="selected-user-name" class="font-bold text-slate-800 text-sm"></span>
                            <span id="selected-user-meta" class="text-xs text-slate-500 font-medium"></span>
                        </div>
                    </div>
                    <button type="button" id="btn-deselect-user" class="h-7 w-7 rounded-full flex items-center justify-center text-slate-400 hover:text-red-700 hover:bg-red-100/50 transition-colors text-lg font-semibold">
                        &times;
                    </button>
                </div>
            </div>

            <!-- Kolom 2: Cari Fitur -->
            <div class="flex flex-col gap-2 flex-grow" id="search-filter-container">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Cari Fitur</label>
                <div class="input-container">
                    <input type="text" id="search-features" placeholder="Masukkan nama fitur atau deskripsi..." class="form-input-premium has-icon-left">
                    <div class="icon-left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div id="nim-placeholder" class="premium-card p-12 text-center bg-white border border-slate-100 rounded-2xl shadow-sm" style="display: flex; min-height: 280px; align-items: center; justify-content: center; flex-direction: column;">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
        </svg>
        <h3 class="text-lg font-bold text-slate-700">Pilih Pengguna</h3>
        <p class="mt-2 text-slate-500 max-w-md mx-auto text-sm leading-relaxed">Silakan cari dan pilih pengguna terlebih dahulu untuk mengatur hak aksesnya secara individual.</p>
    </div>

    <div class="premium-card" id="permissions-container" style="display: none;">
        <div class="overflow-x-auto">
            <table class="premium-table" id="features-table">
                <thead>
                    <tr>
                        <th>Fitur & Deskripsi</th>
                        <th class="text-center" style="width: 160px;">Status Akses</th>
                    </tr>
                </thead>
                <tbody id="features-body">
                    <!-- Dynamic Rows Will Be Rendered by JavaScript -->
                </tbody>
            </table>
        </div>
        <div class="p-6 bg-slate-50/50 border-t border-slate-100 flex justify-between items-center">
            <p class="text-sm text-slate-500">Mengonfigurasi hak akses untuk: <strong id="current-target-label" class="text-slate-700">-</strong></p>
            <button id="btn-save" class="px-6 py-2 bg-red-600 border border-transparent rounded-xl text-white font-bold text-sm hover:bg-red-700 transition-colors shadow-sm">
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="toast-notification">
    <div class="toast-icon">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
        </svg>
    </div>
    <span id="toast-message">Konfigurasi hak akses berhasil disimpan.</span>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Logically grouped features with descriptions
        const featureDefinitions = [
            {
                category: 'Proposal Kegiatan',
                items: [
                    { id: 'create-proposal', name: 'Create Proposal Kegiatan', desc: 'Mengizinkan pembuatan dan pengajuan proposal kegiatan baru.' },
                    { id: 'view-proposal', name: 'View Proposal Kegiatan', desc: 'Mengizinkan melihat daftar dan rincian proposal kegiatan.' },
                    { id: 'edit-proposal', name: 'Edit Proposal Kegiatan', desc: 'Mengizinkan mengedit draf proposal kegiatan.' },
                    { id: 'delete-proposal', name: 'Delete Proposal Kegiatan', desc: 'Mengizinkan menghapus draf proposal kegiatan.' },
                    { id: 'approve-proposal', name: 'Approve Proposal Kegiatan', desc: 'Mengizinkan menyetujui proposal kegiatan.' },
                    { id: 'reject-proposal', name: 'Reject Proposal Kegiatan', desc: 'Mengizinkan menolak proposal kegiatan.' }
                ]
            },
            {
                category: 'Revisi Proposal',
                items: [
                    { id: 'view-revisi', name: 'View Revisi Proposal', desc: 'Mengizinkan melihat revisi proposal kegiatan.' },
                    { id: 'edit-revisi', name: 'Edit Revisi Proposal', desc: 'Mengizinkan mengubah revisi proposal.' },
                    { id: 'approve-revisi', name: 'Approve Revisi Proposal', desc: 'Mengizinkan menyetujui revisi proposal.' }
                ]
            },
            {
                category: 'LPJ Kegiatan',
                items: [
                    { id: 'create-lpj', name: 'Create LPJ Kegiatan', desc: 'Mengizinkan pengunggahan Laporan Pertanggungjawaban (LPJ) kegiatan.' },
                    { id: 'view-lpj', name: 'View LPJ Kegiatan', desc: 'Mengizinkan melihat laporan pertanggungjawaban kegiatan.' },
                    { id: 'edit-lpj', name: 'Edit LPJ Kegiatan', desc: 'Mengizinkan mengubah draf LPJ kegiatan.' },
                    { id: 'delete-lpj', name: 'Delete LPJ Kegiatan', desc: 'Mengizinkan menghapus draf LPJ.' },
                    { id: 'approve-lpj', name: 'Approve LPJ Kegiatan', desc: 'Mengizinkan menyetujui LPJ kegiatan.' },
                    { id: 'reject-lpj', name: 'Reject LPJ Kegiatan', desc: 'Mengizinkan menolak LPJ kegiatan.' }
                ]
            },
            {
                category: 'Prestasi',
                items: [
                    { id: 'create-prestasi', name: 'Create Prestasi', desc: 'Mengizinkan pelaporan prestasi baru.' },
                    { id: 'view-prestasi', name: 'View Prestasi', desc: 'Mengizinkan melihat daftar dan detail prestasi.' },
                    { id: 'edit-prestasi', name: 'Edit Prestasi', desc: 'Mengizinkan mengubah data prestasi.' },
                    { id: 'delete-prestasi', name: 'Delete Prestasi', desc: 'Mengizinkan menghapus data prestasi.' },
                    { id: 'approve-prestasi', name: 'Approve Prestasi', desc: 'Mengizinkan validasi prestasi.' },
                    { id: 'reject-prestasi', name: 'Reject Prestasi', desc: 'Mengizinkan penolakan prestasi.' }
                ]
            },
            {
                category: 'Manajemen User',
                items: [
                    { id: 'view-users', name: 'View Users', desc: 'Mengizinkan melihat daftar pengguna dalam sistem.' },
                    { id: 'create-users', name: 'Create Users', desc: 'Mengizinkan pembuatan pengguna baru secara manual.' },
                    { id: 'edit-users', name: 'Edit Users', desc: 'Mengizinkan memperbarui data dan role pengguna.' },
                    { id: 'delete-users', name: 'Delete Users', desc: 'Mengizinkan menghapus pengguna dari sistem.' }
                ]
            },
            {
                category: 'Template Dokumen',
                items: [
                    { id: 'manage-templates', name: 'Manage Template Dokumen', desc: 'Mengizinkan menambah/mengubah template dokumen panduan.' },
                    { id: 'view-templates', name: 'View Template Dokumen', desc: 'Mengizinkan melihat dan mengunduh template dokumen.' }
                ]
            },
            {
                category: 'Laporan & Statistik',
                items: [
                    { id: 'view-reports', name: 'View Reports', desc: 'Mengizinkan akses melihat laporan rekapitulasi/statistik.' },
                    { id: 'export-reports', name: 'Export Reports', desc: 'Mengizinkan mencetak/ekspor laporan ke format PDF.' }
                ]
            },
            {
                category: 'Publikasi Kegiatan',
                items: [
                    { id: 'create-publikasi', name: 'Create Publikasi', desc: 'Mengizinkan pembuatan dan pengiriman publikasi kegiatan baru.' },
                    { id: 'view-publikasi', name: 'View Publikasi', desc: 'Mengizinkan melihat daftar dan detail publikasi kegiatan.' },
                    { id: 'edit-publikasi', name: 'Edit Publikasi', desc: 'Mengizinkan mengubah data publikasi kegiatan.' },
                    { id: 'delete-publikasi', name: 'Delete Publikasi', desc: 'Mengizinkan menghapus publikasi kegiatan.' },
                    { id: 'approve-publikasi', name: 'Approve Publikasi', desc: 'Mengizinkan verifikasi dan persetujuan publikasi kegiatan.' }
                ]
            },
            {
                category: 'Autentikasi (2FA)',
                items: [
                    { id: 'verifikasi-2fa', name: 'Verifikasi 2FA', desc: 'Mewajibkan verifikasi kode OTP 6 digit yang dikirim ke email saat login atau registrasi.' }
                ]
            }
        ];

        // Element references
        const userSearchInput = document.getElementById('user-search-input');
        const userSearchResults = document.getElementById('user-search-results');
        const selectedUserPill = document.getElementById('selected-user-pill');
        const selectedUserName = document.getElementById('selected-user-name');
        const selectedUserMeta = document.getElementById('selected-user-meta');
        const selectedUserAvatar = document.getElementById('selected-user-avatar');
        const btnDeselectUser = document.getElementById('btn-deselect-user');
        const searchInput = document.getElementById('search-features');
        const featuresBody = document.getElementById('features-body');
        const currentTargetLabel = document.getElementById('current-target-label');
        const btnSave = document.getElementById('btn-save');
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        const permissionsContainer = document.getElementById('permissions-container');
        const userPlaceholder = document.getElementById('nim-placeholder');

        // State: user yang sedang dikonfigurasi (per-orang)
        let selectedUserId = null;
        let _searchTimeout = null;

        // Helper: bangun nama lengkap dari objek user
        function buildFullName(u) {
            const first = u.nama_depan || '';
            const last = u.nama_belakang || '';
            return (first + ' ' + last).trim() || u.username || 'Nama Tidak Tersedia';
        }

        // ── Live search pengguna (lintas semua role) ──────────────────────────
        userSearchInput.addEventListener('input', function () {
            const query = userSearchInput.value.trim();
            if (query.length < 2) {
                userSearchResults.classList.add('hidden');
                return;
            }

            clearTimeout(_searchTimeout);
            _searchTimeout = setTimeout(function () {
                userSearchResults.innerHTML = '<div class="p-3 text-xs text-slate-400 italic text-center">Mencari...</div>';
                userSearchResults.classList.remove('hidden');

                window.axios.get('users', { params: { search: query } })
                    .then(function (res) {
                        const users = res.data?.data || [];
                        if (users.length === 0) {
                            userSearchResults.innerHTML = '<div class="p-3 text-xs text-red-500 font-semibold text-center">Pengguna tidak ditemukan</div>';
                            return;
                        }

                        userSearchResults.innerHTML = '';
                        users.forEach(function (u) {
                            const fullName = buildFullName(u);
                            const role = u.role || '-';
                            const ident = u.nim || u.username || u.email || '-';

                            const item = document.createElement('div');
                            item.className = 'user-item p-3 hover:bg-slate-50 border-b border-slate-100 last:border-0 cursor-pointer flex justify-between items-center transition-colors';
                            item.innerHTML = `
                                <div class="flex flex-col w-full">
                                    <span class="font-bold text-slate-800 text-sm">${fullName}</span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs font-bold bg-red-50 text-red-700 border border-red-100 px-2 py-0.5 rounded-md">${role}</span>
                                        <span class="text-xs text-slate-500">${ident}</span>
                                    </div>
                                </div>
                                <div class="text-slate-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            `;

                            item.addEventListener('click', function () {
                                selectUser(u);
                            });

                            userSearchResults.appendChild(item);
                        });
                    })
                    .catch(function (err) {
                        console.warn('[TOPKEMA] Gagal memuat data pencarian:', err.message);
                        userSearchResults.innerHTML = '<div class="p-3 text-xs text-red-500 text-center">Gagal memuat hasil pencarian</div>';
                    });
            }, 300);
        });

        // Pilih user → tampilkan pill, sembunyikan input, load permission via API
        function selectUser(u) {
            selectedUserId = u.id_user;
            userSearchResults.classList.add('hidden');

            const fullName = buildFullName(u);
            const role = u.role || '-';
            const ident = u.nim || u.username || u.email || '-';

            if (selectedUserName) selectedUserName.textContent = fullName;
            if (selectedUserMeta) selectedUserMeta.textContent = `${role} \u2022 ${ident}`;
            if (selectedUserAvatar) selectedUserAvatar.textContent = (fullName.charAt(0) || 'U').toUpperCase();

            const inputContainer = userSearchInput.closest('.input-container');
            if (inputContainer) inputContainer.style.display = 'none';
            if (selectedUserPill) selectedUserPill.classList.remove('hidden');

            currentTargetLabel.textContent = `${fullName} (${role})`;

            window.axios.get(`users/${u.id_user}/permissions`)
                .then(function (res) {
                    const permissions = (res.data?.data || []).map(function (p) {
                        return typeof p === 'string' ? p : p.name;
                    });
                    renderTable(permissions);
                })
                .catch(function (err) {
                    console.warn('[TOPKEMA] Gagal mengambil permissions user:', err.message);
                    showToast('Gagal memuat hak akses pengguna.', 'error');
                    renderTable([]);
                });
        }

        // Batal pilih user → reset ke kondisi awal
        function deselectUser() {
            selectedUserId = null;
            userSearchInput.value = '';

            const inputContainer = userSearchInput.closest('.input-container');
            if (inputContainer) inputContainer.style.display = 'block';
            if (selectedUserPill) selectedUserPill.classList.add('hidden');
            userSearchResults.classList.add('hidden');

            if (permissionsContainer) permissionsContainer.style.display = 'none';
            if (userPlaceholder) userPlaceholder.style.display = 'flex';
            featuresBody.innerHTML = '';
            currentTargetLabel.textContent = '-';
        }

        if (btnDeselectUser) btnDeselectUser.addEventListener('click', deselectUser);

        // Tutup dropdown hasil pencarian saat klik di luar
        document.addEventListener('click', function (e) {
            if (!userSearchInput.contains(e.target) && !userSearchResults.contains(e.target)) {
                userSearchResults.classList.add('hidden');
            }
        });

        // Render the features table based on the user's active permissions
        function renderTable(activePerms) {
            activePerms = activePerms || [];

            if (permissionsContainer) permissionsContainer.style.display = 'block';
            if (userPlaceholder) userPlaceholder.style.display = 'none';

            featuresBody.innerHTML = '';

            featureDefinitions.forEach(group => {
                // Category Header row
                const headerRow = document.createElement('tr');
                headerRow.className = 'category-row';
                headerRow.innerHTML = `<td colspan="2" class="category-header">${group.category}</td>`;
                featuresBody.appendChild(headerRow);

                group.items.forEach(item => {
                    const isChecked = activePerms.includes(item.name);

                    const row = document.createElement('tr');
                    row.className = 'feature-item-row';
                    row.dataset.name = item.name.toLowerCase();
                    row.dataset.desc = item.desc.toLowerCase();

                    row.innerHTML = `
                        <td>
                            <div class="flex flex-col">
                                <span class="text-slate-900 font-bold text-base">${item.name}</span>
                                <span class="text-slate-500 text-xs font-normal mt-0.5">${item.desc}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <label class="switch">
                                <input type="checkbox" class="feature-checkbox" data-name="${item.name}" ${isChecked ? 'checked' : ''}>
                                <span class="slider"></span>
                            </label>
                        </td>
                    `;
                    featuresBody.appendChild(row);
                });
            });

            // Re-apply current search filter if any
            filterFeatures();
        }

        // Filter feature list based on search query
        function filterFeatures() {
            const query = searchInput.value.toLowerCase().trim();
            const itemRows = document.querySelectorAll('.feature-item-row');
            const categoryRows = document.querySelectorAll('.category-row');
            
            if (query === '') {
                // Show everything
                itemRows.forEach(row => row.style.display = '');
                categoryRows.forEach(row => row.style.display = '');
                return;
            }

            // Hide/Show item rows matching query
            itemRows.forEach(row => {
                const name = row.dataset.name;
                const desc = row.dataset.desc;
                if (name.includes(query) || desc.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Hide category headers if no visible items in them
            categoryRows.forEach(catRow => {
                let sibling = catRow.nextElementSibling;
                let hasVisibleSibling = false;
                
                while (sibling && sibling.classList.contains('feature-item-row')) {
                    if (sibling.style.display !== 'none') {
                        hasVisibleSibling = true;
                        break;
                    }
                    sibling = sibling.nextElementSibling;
                }
                
                catRow.style.display = hasVisibleSibling ? '' : 'none';
            });
        }

        // Save updated permissions — murni via API (per-user)
        function savePermissions() {
            if (!selectedUserId) {
                showToast('Pilih pengguna terlebih dahulu.', 'error');
                return;
            }

            const checkboxes = document.querySelectorAll('.feature-checkbox');
            const activePerms = [];
            checkboxes.forEach(function (cb) {
                if (cb.checked) activePerms.push(cb.getAttribute('data-name'));
            });

            btnSave.disabled = true;
            window.axios.patch(`users/${selectedUserId}/permissions`, { permissions: activePerms })
                .then(function () {
                    showToast('Hak akses pengguna berhasil disimpan.', 'success');
                })
                .catch(function (err) {
                    const msg = err?.response?.data?.message || 'Gagal menyimpan hak akses.';
                    showToast(msg, 'error');
                })
                .finally(function () {
                    btnSave.disabled = false;
                });
        }

        function showToast(message, type) {
            const iconEl = toast.querySelector('.toast-icon');
            if (type === 'error') {
                toast.style.background = 'rgba(153, 27, 27, 0.92)';
                if (iconEl) iconEl.style.background = '#dc2626';
            } else {
                toast.style.background = 'rgba(15, 23, 42, 0.9)';
                if (iconEl) iconEl.style.background = '#10b981';
            }
            toastMessage.textContent = message;
            toast.classList.add('active');
            setTimeout(function () { toast.classList.remove('active'); }, 3500);
        }

        // Event Listeners
        searchInput.addEventListener('input', filterFeatures);
        btnSave.addEventListener('click', savePermissions);

        // Initial setup — belum ada user dipilih
        deselectUser();
    });
</script>
@endsection
