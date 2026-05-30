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
            <p class="text-slate-500 mt-1">Kelola perizinan fitur untuk masing-masing role dan organisasi</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="premium-card p-6 mb-8 bg-white">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Pilih Role</label>
                <div class="input-container">
                    <select id="role-select" class="form-input-premium has-icon-right appearance-none">
                        <option value="Mahasiswa">Mahasiswa</option>
                        <option value="Ormawa Prodi">Ormawa Prodi (Himpunan)</option>
                        <option value="Ormawa Institusi">Ormawa Institusi (UKM)</option>
                        <option value="DPMBEM">DPM / BEM</option>
                        <option value="Kemahasiswaan">Kemahasiswaan</option>
                        <option value="Super Admin">Super Admin</option>
                    </select>
                    <div class="icon-right">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-2" id="himpunan-filter-container" style="display: none;">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1" id="himpunan-filter-label">Pilih Himpunan</label>
                <div class="input-container">
                    <select id="himpunan-select" class="form-input-premium has-icon-right appearance-none">
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
                    <div class="icon-right">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-2" id="nim-filter-container" style="display: none;">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">NIM Mahasiswa</label>
                <div class="input-container">
                    <input type="text" id="nim-input" placeholder="Masukkan NIM (contoh: 23110401)..." class="form-input-premium has-icon-left">
                    <div class="icon-left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>

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
    <div class="premium-card">
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
            <p class="text-sm text-slate-500">Mengonfigurasi hak akses untuk: <strong id="current-target-label" class="text-slate-700">Mahasiswa</strong></p>
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
                category: 'Autentikasi (2FA)',
                items: [
                    { id: 'verifikasi-2fa', name: 'Verifikasi 2FA', desc: 'Mewajibkan verifikasi kode OTP 6 digit yang dikirim ke email saat login atau registrasi.' }
                ]
            }
        ];

        // Default role permissions configurations
        const defaultRolePermissions = {
            'Super Admin': [
                'Create Proposal Kegiatan', 'View Proposal Kegiatan', 'Edit Proposal Kegiatan', 'Delete Proposal Kegiatan', 'Approve Proposal Kegiatan', 'Reject Proposal Kegiatan',
                'View Revisi Proposal', 'Edit Revisi Proposal', 'Approve Revisi Proposal',
                'Create LPJ Kegiatan', 'View LPJ Kegiatan', 'Edit LPJ Kegiatan', 'Delete LPJ Kegiatan', 'Approve LPJ Kegiatan', 'Reject LPJ Kegiatan',
                'Create Prestasi', 'View Prestasi', 'Edit Prestasi', 'Delete Prestasi', 'Approve Prestasi', 'Reject Prestasi',
                'View Users', 'Create Users', 'Edit Users', 'Delete Users',
                'Manage Template Dokumen', 'View Template Dokumen',
                'View Reports', 'Export Reports', 'Verifikasi 2FA'
            ],
            'Kemahasiswaan': [
                'View Proposal Kegiatan', 'Approve Proposal Kegiatan', 'Reject Proposal Kegiatan',
                'View Revisi Proposal', 'Approve Revisi Proposal',
                'View LPJ Kegiatan', 'Approve LPJ Kegiatan', 'Reject LPJ Kegiatan',
                'View Prestasi', 'Approve Prestasi', 'Reject Prestasi',
                'View Users', 'View Template Dokumen', 'View Reports', 'Export Reports', 'Verifikasi 2FA'
            ],
            'DPMBEM': [
                'View Proposal Kegiatan', 'Approve Proposal Kegiatan', 'Reject Proposal Kegiatan',
                'View LPJ Kegiatan', 'View Prestasi', 'View Users', 'View Template Dokumen', 'View Reports', 'Verifikasi 2FA'
            ],
            'Ormawa Institusi': [
                'Create Proposal Kegiatan', 'View Proposal Kegiatan', 'Edit Proposal Kegiatan',
                'Create LPJ Kegiatan', 'View LPJ Kegiatan', 'Edit LPJ Kegiatan',
                'Create Prestasi', 'View Prestasi', 'Edit Prestasi',
                'View Template Dokumen', 'Verifikasi 2FA'
            ],
            'Ormawa Prodi': [
                'Create Proposal Kegiatan', 'View Proposal Kegiatan', 'Edit Proposal Kegiatan',
                'Create LPJ Kegiatan', 'View LPJ Kegiatan', 'Edit LPJ Kegiatan',
                'Create Prestasi', 'View Prestasi', 'Edit Prestasi',
                'View Template Dokumen', 'Verifikasi 2FA'
            ],
            'Mahasiswa': [
                'Create Proposal Kegiatan', 'View Proposal Kegiatan',
                'Create Prestasi', 'View Prestasi',
                'View Template Dokumen', 'Verifikasi 2FA'
            ]
        };

        const roleSelect = document.getElementById('role-select');
        const himpunanSelect = document.getElementById('himpunan-select');
        const himpunanContainer = document.getElementById('himpunan-filter-container');
        const nimContainer = document.getElementById('nim-filter-container');
        const nimInput = document.getElementById('nim-input');
        const searchInput = document.getElementById('search-features');
        const featuresBody = document.getElementById('features-body');
        const currentTargetLabel = document.getElementById('current-target-label');
        const btnSave = document.getElementById('btn-save');
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        const himpunanOptions = [
            { value: 'HMIF', text: 'HMIF' },
            { value: 'HMTI', text: 'HMTI' },
            { value: 'HMDKV', text: 'HMDKV' },
            { value: 'HMSE', text: 'HMSE' },
            { value: 'HMTB', text: 'HMTB' },
            { value: 'HMTT', text: 'HMTT' },
            { value: 'HMTE', text: 'HMTE' },
            { value: 'HMTP', text: 'HMTP' },
            { value: 'HMDP', text: 'HMDP' },
            { value: 'HMSD', text: 'HMSD' },
            { value: 'HMTL', text: 'HMTL' },
            { value: 'HMBD', text: 'HMBD' },
            { value: 'HMDT', text: 'HMDT' }
        ];

        const ukmOptions = [
            { value: 'GDGOC', text: 'GDGOC' },
            { value: 'KSPM', text: 'KSPM' },
            { value: 'SRE', text: 'SRE' },
            { value: 'KSR', text: 'KSR' },
            { value: 'HIPMI', text: 'HIPMI' }
        ];

        // Render the features table based on active target config
        function renderTable() {
            const role = roleSelect.value;
            const himpunan = himpunanSelect.value;
            const nim = nimInput.value.trim();
            const isOrmawa = ['Ormawa Prodi', 'Ormawa Institusi'].includes(role);
            const isMahasiswa = role === 'Mahasiswa';
            
            // Build the local storage key
            let storageKey = '';
            if (isOrmawa) {
                storageKey = `kontrol_akun_perms_${role.replace(/\s+/g, '_')}_${himpunan.replace(/\s+/g, '_')}`;
            } else if (isMahasiswa && nim !== '') {
                storageKey = `kontrol_akun_perms_${role.replace(/\s+/g, '_')}_NIM_${nim}`;
            } else {
                storageKey = `kontrol_akun_perms_${role.replace(/\s+/g, '_')}`;
            }
            
            // Load custom settings or fall back to defaults
            let activePerms = JSON.parse(localStorage.getItem(storageKey));
            if (!activePerms) {
                // If it is a specific student and they don't have custom perms, we fall back to the general Mahasiswa role perms
                if (isMahasiswa && nim !== '') {
                    const generalKey = `kontrol_akun_perms_Mahasiswa`;
                    activePerms = JSON.parse(localStorage.getItem(generalKey)) || defaultRolePermissions['Mahasiswa'] || [];
                } else {
                    activePerms = defaultRolePermissions[role] || [];
                }
            }

            // Update label
            if (isOrmawa) {
                currentTargetLabel.textContent = `${role} (${himpunan})`;
            } else if (isMahasiswa && nim !== '') {
                currentTargetLabel.textContent = `${role} (NIM: ${nim})`;
            } else {
                currentTargetLabel.textContent = role;
            }

            // Render table
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

        // Save updated config to LocalStorage
        function savePermissions() {
            const role = roleSelect.value;
            const himpunan = himpunanSelect.value;
            const nim = nimInput.value.trim();
            const isOrmawa = ['Ormawa Prodi', 'Ormawa Institusi'].includes(role);
            const isMahasiswa = role === 'Mahasiswa';
            
            let storageKey = '';
            if (isOrmawa) {
                storageKey = `kontrol_akun_perms_${role.replace(/\s+/g, '_')}_${himpunan.replace(/\s+/g, '_')}`;
            } else if (isMahasiswa && nim !== '') {
                storageKey = `kontrol_akun_perms_${role.replace(/\s+/g, '_')}_NIM_${nim}`;
            } else {
                storageKey = `kontrol_akun_perms_${role.replace(/\s+/g, '_')}`;
            }
            
            const checkboxes = document.querySelectorAll('.feature-checkbox');
            const activePerms = [];
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    activePerms.push(cb.getAttribute('data-name'));
                }
            });

            // Save to localStorage
            localStorage.setItem(storageKey, JSON.stringify(activePerms));

            // Show Toast Notification
            let targetName = '';
            if (isOrmawa) {
                targetName = `${role} (${himpunan})`;
            } else if (isMahasiswa && nim !== '') {
                targetName = `${role} (NIM: ${nim})`;
            } else {
                targetName = role;
            }
            
            toastMessage.textContent = `Hak akses untuk ${targetName} berhasil diperbarui di LocalStorage.`;
            toast.classList.add('active');
            
            setTimeout(() => {
                toast.classList.remove('active');
            }, 3500);
        }

        // Toggle Himpunan dropdown visibility based on role selection
        function handleRoleChange() {
            const role = roleSelect.value;
            const isOrmawaProdi = role === 'Ormawa Prodi';
            const isOrmawaInstitusi = role === 'Ormawa Institusi';
            const isOrmawa = isOrmawaProdi || isOrmawaInstitusi;
            const isMahasiswa = role === 'Mahasiswa';
            
            const filterLabel = document.getElementById('himpunan-filter-label');
            
            if (isOrmawa) {
                himpunanContainer.style.display = 'flex';
                nimContainer.style.display = 'none';
                document.getElementById('search-filter-container').className = 'flex flex-col gap-2';
                
                // Clear and populate dropdown
                const selectedVal = himpunanSelect.value;
                himpunanSelect.innerHTML = '';
                const optionsList = isOrmawaProdi ? himpunanOptions : ukmOptions;
                
                optionsList.forEach(opt => {
                    const el = document.createElement('option');
                    el.value = opt.value;
                    el.textContent = opt.text;
                    himpunanSelect.appendChild(el);
                });
                
                // Attempt to restore value if it exists in the new list, else select first
                const hasMatchingOpt = optionsList.some(opt => opt.value === selectedVal);
                if (hasMatchingOpt) {
                    himpunanSelect.value = selectedVal;
                } else if (optionsList.length > 0) {
                    himpunanSelect.value = optionsList[0].value;
                }
                
                // Update Label Text
                if (filterLabel) {
                    filterLabel.textContent = isOrmawaProdi ? 'Pilih Himpunan' : 'Pilih UKM';
                }
            } else if (isMahasiswa) {
                himpunanContainer.style.display = 'none';
                nimContainer.style.display = 'flex';
                document.getElementById('search-filter-container').className = 'flex flex-col gap-2';
            } else {
                himpunanContainer.style.display = 'none';
                nimContainer.style.display = 'none';
                document.getElementById('search-filter-container').className = 'flex flex-col gap-2 md:col-span-1 lg:col-span-2';
            }
            
            renderTable();
        }

        // Event Listeners
        roleSelect.addEventListener('change', handleRoleChange);
        himpunanSelect.addEventListener('change', renderTable);
        nimInput.addEventListener('input', renderTable);
        searchInput.addEventListener('input', filterFeatures);
        btnSave.addEventListener('click', savePermissions);

        // Initial setup
        handleRoleChange();
    });
</script>
@endsection
