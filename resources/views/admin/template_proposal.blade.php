@extends('layouts.app')

@section('title', 'Template Dokumen')

@section('content')
<style>
    .template-shell {
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
        padding: 10px 16px;
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
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .input-container input.has-icon-left {
        padding-left: 40px !important;
    }

    .btn-add-premium {
        background: linear-gradient(135deg, #c1121f 0%, #780116 100%);
        color: white;
        padding: 12px 24px;
        border-radius: 16px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(193, 18, 31, 0.2);
    }

    .btn-add-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(193, 18, 31, 0.3);
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
        vertical-align: middle;
    }

    .doc-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #f1f5f9;
        border-radius: 12px;
        color: #475569;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .doc-badge:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .action-btn-circle {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .btn-edit { color: #0f172a; background: #f1f5f9; }
    .btn-edit:hover { background: #e2e8f0; border-color: #cbd5e1; }
    
    .btn-delete { color: #c1121f; background: #fff1f2; }
    .btn-delete:hover { background: #fee2e2; border-color: #fecaca; }
</style>

<div class="template-shell">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Template Dokumen</h1>
            <p class="text-slate-500 mt-1">Kelola berkas panduan dan formulir resmi untuk Ormawa</p>
        </div>
        <a href="{{ route('admin.input_template_dokumen') }}" class="btn-add-premium">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Template
        </a>
    </div>

    <!-- Toolbar -->
    <div class="premium-card p-4 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-slate-500">Show</span>
                <select class="form-input-premium pr-8">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
                <span class="text-sm font-bold text-slate-500">entries</span>
            </div>
            
            <div class="input-container w-full md:w-80">
                <input type="text" placeholder="Cari nama dokumen..." class="form-input-premium has-icon-left">
                <div class="icon-left">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @php
        $templates = [
            ['name' => 'Panduan Pengajuan Proposal Triwulan', 'updated' => '2 hari yang lalu', 'type' => 'PDF'],
            ['name' => 'Formulir Laporan Pertanggungjawaban (LPJ)', 'updated' => '1 minggu yang lalu', 'type' => 'DOCX'],
            ['name' => 'Template Poster & Media Publikasi', 'updated' => '2 minggu yang lalu', 'type' => 'ZIP'],
            ['name' => 'Surat Keterangan Kepengurusan Ormawa', 'updated' => '1 bulan yang lalu', 'type' => 'DOCX'],
        ];
    @endphp

    <!-- Table -->
    <div class="premium-card">
        <div class="overflow-x-auto">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th class="w-1/2">Nama Dokumen</th>
                        <th class="text-center">Tipe Berkas</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($templates as $template)
                        <tr>
                            <td>
                                <div class="flex flex-col">
                                    <span class="text-slate-900 font-bold text-base">{{ $template['name'] }}</span>
                                    <span class="text-slate-500 text-xs font-normal mt-1">Terakhir diperbarui: {{ $template['updated'] }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="doc-badge">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    {{ $template['type'] }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="action-btn-circle btn-edit" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button class="action-btn-circle btn-delete" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6 bg-slate-50/50 border-t border-slate-100">
            <div class="flex justify-between items-center text-sm font-medium text-slate-500">
                <span>Showing 1 to {{ count($templates) }} of {{ count($templates) }} entries</span>
                <div class="flex gap-2">
                    <button class="px-3 py-1 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 disabled:opacity-50" disabled>Previous</button>
                    <button class="px-3 py-1 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 disabled:opacity-50" disabled>Next</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
