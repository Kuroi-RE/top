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
        padding: 20px 24px;
        border-bottom: 1px solid rgba(2,6,23,0.03);
        color: #1e293b;
        font-weight: 500;
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

    .role-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        background: rgba(193, 18, 31, 0.05);
        color: #c1121f;
        border: 1px solid rgba(193, 18, 31, 0.1);
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
    <div class="premium-card p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Pilih Ormawa</label>
                <div class="input-container">
                    <select class="form-input-premium has-icon-right appearance-none">
                        <option value="Ormawa Prodi">Ormawa Prodi</option>
                        <option value="Ormawa Institusi">Ormawa Institusi</option>
                    </select>
                    <div class="icon-right">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-2 md:col-span-2 lg:col-span-2">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Cari Fitur</label>
                <div class="input-container">
                    <input type="text" placeholder="Masukkan nama fitur atau role..." class="form-input-premium has-icon-left">
                    <div class="icon-left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $controlRows = [
            ['feature' => 'Monitoring Anggaran', 'role' => 'DPM/BEM', 'enabled' => true, 'desc' => 'Akses untuk memantau penggunaan anggaran organisasi'],
            ['feature' => 'Publikasi Kegiatan', 'role' => 'Ormawa Prodi', 'enabled' => false, 'desc' => 'Fitur untuk mengunggah dokumentasi kegiatan'],
            ['feature' => 'Input Prestasi', 'role' => 'Mahasiswa', 'enabled' => true, 'desc' => 'Pengisian data prestasi mandiri oleh mahasiswa'],
            ['feature' => 'Template Proposal', 'role' => 'Ketua Institusi', 'enabled' => true, 'desc' => 'Pengaturan format baku proposal universitas'],
        ];
    @endphp

    <!-- Table -->
    <div class="premium-card">
        <div class="overflow-x-auto">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Fitur & Deskripsi</th>
                        <th>Role Terkait</th>
                        <th class="text-center">Status Akses</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($controlRows as $row)
                        <tr>
                            <td>
                                <div class="flex flex-col">
                                    <span class="text-slate-900 font-bold text-base">{{ $row['feature'] }}</span>
                                    <span class="text-slate-500 text-xs font-normal mt-0.5">{{ $row['desc'] }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="role-badge">{{ $row['role'] }}</span>
                            </td>
                            <td class="text-center">
                                <label class="switch">
                                    <input type="checkbox" {{ $row['enabled'] ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6 bg-slate-50/50 border-t border-slate-100 flex justify-between items-center">
            <p class="text-sm text-slate-500">Menampilkan {{ count($controlRows) }} pengaturan sistem</p>
            <button class="px-6 py-2 bg-white border border-slate-200 rounded-xl text-slate-700 font-bold text-sm hover:bg-slate-50 transition-colors shadow-sm">
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>
@endsection
