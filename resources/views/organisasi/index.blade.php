@extends('layouts.app')

@section('title', 'Beranda Ormawa Institusi')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0');

@php
    $currentUser = auth()->user();
    $displayName = trim(($currentUser?->nama_depan ?? '') . ' ' . ($currentUser?->nama_belakang ?? ''));
    $displayName = $displayName !== '' ? $displayName : ($currentUser?->username ?? 'teman');

    $summaryCards = [
        ['title' => 'Proposal Kegiatan', 'count' => \App\Models\ProposalKegiatan::where('id_user', $currentUser->id_user)->count(), 'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H8.25m0 12.75h7.5m-7.5 3h4.5M5.625 2.25h5.603c.895 0 1.754.356 2.386.988l4.773 4.773c.632.632.988 1.49.988 2.386v8.853a2.25 2.25 0 01-2.25 2.25H5.625a2.25 2.25 0 01-2.25-2.25V4.5a2.25 2.25 0 012.25-2.25z'],
        ['title' => 'LPJ Kegiatan', 'count' => \App\Models\LpjKegiatan::whereHas('proposal', fn($q) => $q->where('id_user', $currentUser->id_user))->count(), 'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H8.25m0 12.75h7.5m-7.5 3h4.5M5.625 2.25h5.603c.895 0 1.754.356 2.386.988l4.773 4.773c.632.632.988 1.49.988 2.386v8.853a2.25 2.25 0 01-2.25 2.25H5.625a2.25 2.25 0 01-2.25-2.25V4.5a2.25 2.25 0 012.25-2.25z'],
        ['title' => 'Publikasi Kegiatan', 'count' => \App\Models\InformasiKegiatan::where('id_user', $currentUser->id_user)->count(), 'icon' => 'M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253'],
    ];

    $activities = \App\Models\ProposalKegiatan::where('id_user', $currentUser->id_user)
        ->latest('id_proposal')
        ->get()
        ->map(function ($proposal, $index) {
            $formattedDate = data_get($proposal, 'waktu_kegiatan');
            if (!$formattedDate) {
                $formattedDate = optional($proposal->created_at)->format('d/m/Y') ?? '-';
            }

            return [
                'no' => $index + 1,
                'tw' => data_get($proposal, 'ajuan_triwulan', '-'),
                'nama_kegiatan' => data_get($proposal, 'nama_kegiatan', '-'),
                'pelaksanaan' => $formattedDate,
                'ajuan_dana' => 'Rp ' . number_format((float) data_get($proposal, 'besar_ajuan', 0), 0, ',', '.'),
                'anggaran' => 'Rp ' . number_format((float) data_get($proposal, 'anggaran_disetujui', 0), 0, ',', '.'),
                'status' => ($proposal->status == 'Disetujui' && optional(\App\Models\LpjKegiatan::where('id_proposal', data_get($proposal, 'id_proposal'))->first())->status_lpj == 'Menunggu') ? 'Cek LPJ' : data_get($proposal, 'status', 'Ajuan baru'),
                'lpj_keuangan' => data_get($proposal, 'file_lpj_keuangan'),
                'lpj_kegiatan_file' => \App\Models\LpjKegiatan::where('id_proposal', data_get($proposal, 'id_proposal'))->first(),
                'lpj_kegiatan_status' => optional(\App\Models\LpjKegiatan::where('id_proposal', data_get($proposal, 'id_proposal'))->first())->status_lpj,
                'lpj_kegiatan_notes' => optional(\App\Models\LpjKegiatan::where('id_proposal', data_get($proposal, 'id_proposal'))->first())->catatan_admin,
                'catatan_admin' => data_get($proposal, 'catatan_admin'),
                'id' => data_get($proposal, 'id_proposal'),
            ];
        });
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0');

    .greeting-hero {
        position: relative;
        display: flex;
        opacity: 0;
        transform: translateY(8px) scale(0.98);
        animation: greeting-pop 0.55s ease-out 0.08s forwards;
    }

    .greeting-sub {
        margin-top: 2px;
        font-size: 0.82rem;
        color: #9a3412;
        opacity: 0;
        transform: translateY(8px) scale(0.98);
        animation: greeting-pop 0.55s ease-out 0.2s forwards;
    }

    @keyframes greeting-pop {
        from { opacity: 0; transform: translateY(10px) scale(0.96); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes greeting-wiggle {
        0% { transform: rotate(0deg); }
        20% { transform: rotate(14deg); }
        40% { transform: rotate(-8deg); }
        60% { transform: rotate(14deg); }
        80% { transform: rotate(-4deg); }
        100% { transform: rotate(0deg); }
    }

    @keyframes content-fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-content {
        animation: content-fade-in 0.6s ease-out both;
    }

    /* Stagger card animations */
    .summary-card:nth-child(1) { animation-delay: 0.1s; }
    .summary-card:nth-child(2) { animation-delay: 0.2s; }
    .summary-card:nth-child(3) { animation-delay: 0.3s; }
    .main-content-card { animation-delay: 0.4s; }

    .dark .greeting-hero {
        border-color: rgba(249, 115, 22, 0.25);
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.9) 0%, rgba(15, 23, 42, 0.95) 65%);
        box-shadow: 0 14px 32px rgba(2, 6, 23, 0.5);
    }

    .dark .greeting-title {
        color: #fed7aa;
    }

    .dark .greeting-sub {
        color: #fdba74;
    }

    .dark .greeting-wave {
        color: #fb923c;
    }
</style>

@php
    $statusConfig = [
        'Selesai'   => ['bg' => 'bg-green-50',  'text' => 'text-green-700'],
        'Pencairan' => ['bg' => 'bg-blue-100',  'text' => 'text-blue-700'],
        'Acc'       => ['bg' => 'bg-purple-100','text' => 'text-purple-700'],
        'Menunggu'  => ['bg' => 'bg-blue-50',   'text' => 'text-blue-700'],
        'Revisi'    => ['bg' => 'bg-red-50',    'text' => 'text-red-700'],
        'Disetujui' => ['bg' => 'bg-green-50',  'text' => 'text-green-700'],
        'Ditolak'   => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700'],
    ];

    $kegiatans = $activities; // Using the $activities variable defined above
@endphp

<div class="mb-6">
    <div class="greeting-hero mb-4">
        <div class="greeting-wave" aria-hidden="true">
            <span class="greeting-wave-icon">👋</span>
        </div>
        <div>
            <div class="greeting-title">Halo, {{ $displayName }}! Selamat datang kembali di TOPKEMA</div>
            <div class="greeting-sub">Semoga harimu lancar dan penuh ide untuk kegiatan berikutnya</div>
        </div>
    </div>

    <div class="flex gap-4 items-stretch overflow-x-auto pb-2">
        <div class="summary-card animate-content flex-1 min-w-[260px] rounded-2xl bg-white border border-gray-100 p-4 shadow h-28 flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-xs text-gray-500 uppercase">Total Proposal</p>
                <p class="mt-1 text-2xl font-extrabold text-gray-900">{{ $total ?? 0 }}</p>
            </div>
            <div class="flex-shrink-0 ml-4">
                <span class="inline-flex items-center rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">+3 bulan ini</span>
            </div>
        </div>

        <div class="summary-card animate-content flex-1 min-w-[260px] rounded-2xl bg-white border border-gray-100 p-4 shadow h-28 flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-xs text-gray-500 uppercase">Revisi</p>
                <p class="mt-1 text-2xl font-extrabold text-gray-900">{{ $revisi ?? 0 }}</p>
            </div>
            <div class="flex-shrink-0 ml-4">
                <span class="inline-flex items-center rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700">Prioritas</span>
            </div>
        </div>

        <div class="summary-card animate-content flex-1 min-w-[260px] rounded-2xl bg-white border border-gray-100 p-4 shadow h-28 flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-xs text-gray-500 uppercase">Disetujui</p>
                <p class="mt-1 text-2xl font-extrabold text-gray-900">{{ $disetujui ?? 0 }}</p>
            </div>
            <div class="flex-shrink-0 ml-4">
                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Siap LPJ</span>
            </div>
        </div>
    </div>
</div>

<div class="mb-3 flex items-center justify-between gap-3">
    <div class="flex items-center gap-2">
        <div class="relative inline-block">
            <select
                id="per-page-select"
                onchange="changePerPage(this.value)"
                aria-label="Pilih jumlah per halaman"
                class="appearance-none h-10 w-16 pl-3 pr-8 rounded-lg border border-gray-200 bg-white text-sm text-gray-700
                       shadow-sm focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-100 cursor-pointer transition-colors"
            >
                <option value="5" selected>5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            
        </div>
        <span class="text-sm text-gray-600">Record per page</span>
    </div>

    <div class="search-box relative" style="max-width:420px; width:100%; margin-left:auto;">
        <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="11" cy="11" r="7"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input
            id="search-input"
            type="search"
            placeholder="Cari kegiatan..."
            aria-label="Cari kegiatan"
            class="w-full rounded-lg bg-transparent py-1.5 pl-12 pr-4
                   text-sm text-gray-700 placeholder-gray-400 shadow-sm
                   focus:outline-none transition-all"
        />
        <button type="button" class="search-clear" aria-label="Kosongkan pencarian" title="Kosongkan pencarian">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<div class="main-content-card animate-content overflow-hidden rounded-2xl bg-white shadow-sm border border-gray-100">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <h2 class="text-base font-semibold text-gray-800">Daftar Proposal</h2>
        <div class="flex items-center gap-2">
            <a
                href="{{ route('organisasi.proposal_export') }}"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition-colors hover:bg-gray-50 hover:text-gray-700"
                aria-label="Download semua proposal"
                title="Download"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l4-4m-4 4l-4-4M5 21h14" />
                </svg>
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="kegiatan-table">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50 text-left">
                    <th class="whitespace-nowrap px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 w-12">
                        No
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 w-14">
                        TW
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 min-w-[160px]">
                        <button data-sort="nama_kegiatan" onclick="sortTable('nama_kegiatan')"
                            class="flex items-center gap-1 hover:text-gray-800 transition-colors group">
                            <span>Nama Kegiatan</span>
                            <svg class="h-3.5 w-3.5 text-gray-400 group-hover:text-gray-600"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </button>
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <button data-sort="pelaksanaan" onclick="sortTable('pelaksanaan')"
                            class="flex items-center gap-1 hover:text-gray-800 transition-colors group">
                            <span>Pelaksanaan</span>
                            <svg class="h-3.5 w-3.5 text-gray-400 group-hover:text-gray-600"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </button>
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Ajuan Dana
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Anggaran
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <button onclick="sortTable('status')"
                                class="flex items-center gap-1 hover:text-gray-800 transition-colors group">
                            <span>Status</span>
                            <svg class="h-3.5 w-3.5 text-gray-400 group-hover:text-gray-600"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </button>
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
                        LPJ Keuangan
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
                        LPJ Kegiatan
                    </th>
                    <th class="whitespace-nowrap px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100" id="table-body">
                @foreach ($kegiatans as $item)
                @php
                    $cfg = $statusConfig[$item['status']] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-600'];
                    try {
                        $pelaksanaanIso = \Carbon\Carbon::createFromFormat('d/m/Y', $item['pelaksanaan'])->format('Y-m-d');
                    } catch (\Exception $e) {
                        $pelaksanaanIso = $item['pelaksanaan'];
                    }
                @endphp
                <tr class="hover:bg-slate-50 transition-colors"
                    data-nama="{{ strtolower($item['nama_kegiatan']) }}"
                    data-status="{{ strtolower($item['status']) }}"
                    data-tw="{{ strtolower($item['tw']) }}"
                    data-pelaksanaan="{{ $pelaksanaanIso }}">

                    <td class="px-4 py-3 text-gray-500 font-medium">{{ $item['no'] }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="font-semibold text-gray-700">{{ $item['tw'] }}</span>
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-800">
                        <a href="{{ $item['status'] == 'Revisi' ? route('organisasi.edit', $item['id']) : route('organisasi.show', $item['id']) }}" class="transition-colors hover:text-blue-600 hover:underline" title="{{ $item['status'] == 'Revisi' ? 'Klik untuk revisi kegiatan' : 'Lihat detail kegiatan' }}">
                            {{ $item['nama_kegiatan'] }}
                        </a>
                        @if($item['status'] == 'Revisi' && $item['catatan_admin'])
                            <p class="mt-1 text-xs font-bold text-red-600 italic">
                                *Rev: {{ \Illuminate\Support\Str::limit($item['catatan_admin'], 50) }}
                            </p>
                        @elseif(isset($item['lpj_kegiatan_status']) && $item['lpj_kegiatan_status'] == 'Revisi' && isset($item['lpj_kegiatan_notes']))
                            <p class="mt-1 text-xs font-bold text-red-600 italic">
                                *Rev LPJ: {{ \Illuminate\Support\Str::limit($item['lpj_kegiatan_notes'], 50) }}
                            </p>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $item['pelaksanaan'] }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $item['ajuan_dana'] }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $item['anggaran'] }}</td>

                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium 
                            {{ $item['status'] == 'Disetujui' ? 'bg-green-100 text-green-800' : ($item['status'] == 'Revisi' || $item['status'] == 'Revisi LPJ' ? 'bg-red-100 text-red-800' : ($item['status'] == 'Selesai' ? 'bg-blue-100 text-blue-800' : ($item['status'] == 'Cek LPJ' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800'))) }}">
                            {{ $item['status'] }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-center">
                        @if ($item['lpj_keuangan'])
                            <a href="{{ asset('storage/' . $item['lpj_keuangan']) }}" target="_blank" title="Lihat LPJ Keuangan"
                               class="inline-flex items-center justify-center rounded-lg p-1.5
                                      text-blue-500 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0
                                             01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </a>
                        @else
                            <span class="text-gray-300">&mdash;</span>
                        @endif
                    </td>

                    <td class="px-4 py-3 text-center">
                        @if ($item['status'] == 'Selesai' && isset($item['lpj_kegiatan_file']) && $item['lpj_kegiatan_file'])
                            <a href="{{ asset('storage/' . $item['lpj_kegiatan_file']->file_lpj) }}" target="_blank" title="Lihat LPJ Kegiatan"
                               class="inline-flex items-center justify-center rounded-lg p-1.5
                                      text-blue-500 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0
                                             01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </a>
                        @else
                            <span class="text-gray-300">&mdash;</span>
                        @endif
                    </td>

                    <td class="px-4 py-3 text-center">
                        @if ($item['status'] == 'Revisi')
                            <a href="{{ route('organisasi.edit', $item['id']) }}" title="Edit Revisi"
                               class="inline-flex items-center justify-center rounded-lg p-1.5
                                      text-blue-500 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                        @elseif(isset($item['lpj_kegiatan_status']) && $item['lpj_kegiatan_status'] == 'Revisi')
                            <a href="{{ route('organisasi.lpj.revisi', $item['id']) }}" title="Revisi LPJ"
                               class="inline-flex items-center justify-center rounded-lg p-1.5
                                      text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.995-1.465"/>
                                </svg>
                            </a>
                        @else
                            <span class="text-gray-300">&mdash;</span>
                        @endif
                    </td>
                </tr>
                @endforeach

                <tr id="empty-row" class="hidden">
                    <td colspan="9" class="px-4 py-14 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100">
                                <svg class="h-7 w-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500">Data tidak ditemukan</p>
                                <p class="mt-0.5 text-xs text-gray-400">Coba gunakan kata kunci yang berbeda</p>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex flex-col items-center justify-between gap-3 border-t border-gray-100
                px-5 py-3 sm:flex-row" id="pagination-wrapper">
        <p class="text-xs text-gray-500" id="pagination-info">
            Menampilkan
            <span class="font-semibold text-gray-700" id="showing-from">1</span>
            &ndash;
            <span class="font-semibold text-gray-700" id="showing-to">5</span>
            dari
            <span class="font-semibold text-gray-700" id="showing-total">10</span>
            data
        </p>
        <div class="flex items-center gap-1" id="pagination-buttons"></div>
    </div>
</div>

<style>
    .deadline-card {
        position: fixed;
        right: 20px;
        bottom: 20px;
        z-index: 9998;
        width: 320px;
        max-width: calc(100vw - 40px);
        background: #fde2dd;
        border: 2px solid #e6bdb2;
        border-radius: 16px;
        padding: 12px 14px;
        box-shadow: 0 12px 24px rgba(127, 29, 29, 0.12);
        transition: opacity 200ms ease, transform 200ms ease, max-height 200ms ease, padding 200ms ease;
        overflow: hidden;
    }

    .deadline-card.hide {
        opacity: 0;
        transform: translateY(8px);
        max-height: 0;
        padding: 0;
        border-width: 0;
        box-shadow: none;
        pointer-events: none;
    }

    .deadline-close {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 26px;
        height: 26px;
        border-radius: 9999px;
        border: 1px solid #e6bdb2;
        background: #fff;
        color: #7f1d1d;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .deadline-close:hover {
        background: #fef2f2;
    }

    .deadline-title {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #7f1d1d;
        font-weight: 800;
        letter-spacing: 0.16em;
        font-size: 11px;
    }

    .deadline-text {
        margin: 8px 0 0;
        font-size: 14px;
        font-weight: 500;
        color: #3f2a2a;
    }

    .deadline-grid {
        display: flex;
        gap: 8px;
        margin-top: 10px;
    }

    .deadline-box {
        flex: 1;
        background: #ffffff;
        border-radius: 10px;
        padding: 8px 6px;
        text-align: center;
    }

    .deadline-value {
        font-size: 18px;
        font-weight: 800;
        color: #7f1d1d;
    }

    .deadline-label {
        margin-top: 2px;
        font-size: 10px;
        font-weight: 700;
        color: #94a3b8;
        letter-spacing: 0.08em;
    }

    .deadline-toggle {
        position: fixed;
        right: 20px;
        bottom: 20px;
        z-index: 9998;
        display: none;
        align-items: center;
        gap: 8px;
        height: 40px;
        padding: 0 14px;
        border-radius: 9999px;
        background: #fde2dd;
        color: #7f1d1d;
        font-weight: 700;
        font-size: 12px;
        border: 1px solid #e6bdb2;
        box-shadow: 0 10px 20px rgba(127, 29, 29, 0.12);
        cursor: pointer;
    }

    .deadline-toggle.show {
        display: inline-flex;
    }

    .dark .deadline-card {
        background: #0f172a;
        border-color: rgba(148, 163, 184, 0.25);
        box-shadow: 0 16px 36px rgba(2, 6, 23, 0.6);
    }

    .dark .deadline-close {
        background: #0b1220;
        border-color: rgba(148, 163, 184, 0.3);
        color: #fca5a5;
    }

    .dark .deadline-close:hover {
        background: #111827;
    }

    .dark .deadline-title {
        color: #fecaca;
    }

    .dark .deadline-text {
        color: #e2e8f0;
    }

    .dark .deadline-box {
        background: #111827;
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    .dark .deadline-value {
        color: #fecaca;
    }

    .dark .deadline-label {
        color: #94a3b8;
    }

    .dark .deadline-toggle {
        background: #0f172a;
        color: #fecaca;
        border-color: rgba(148, 163, 184, 0.25);
        box-shadow: 0 16px 30px rgba(2, 6, 23, 0.6);
    }
</style>

@if($deadline)
<div id="deadline-card" class="deadline-card" aria-live="polite">
    <button id="deadline-close" class="deadline-close" type="button" aria-label="Tutup notifikasi">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;">
            <path d="M6 6l12 12M18 6l-12 12" />
        </svg>
    </button>
    <div class="deadline-title">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;">
            <circle cx="12" cy="13" r="8"></circle>
            <path d="M12 9v4l2 2"></path>
            <path d="M9 2h6"></path>
            <path d="M15 2v2"></path>
            <path d="M9 2v2"></path>
        </svg>
        {{ strtoupper($deadline->title) }}
    </div>
    <p class="deadline-text">Pengumpulan proposal ditutup dalam:</p>
    <div class="deadline-grid">
        <div class="deadline-box">
            <div id="cd-days" class="deadline-value">00</div>
            <div class="deadline-label">HARI</div>
        </div>
        <div class="deadline-box">
            <div id="cd-hours" class="deadline-value">00</div>
            <div class="deadline-label">JAM</div>
        </div>
        <div class="deadline-box">
            <div id="cd-mins" class="deadline-value">00</div>
            <div class="deadline-label">MENIT</div>
        </div>
    </div>
</div>

<button id="deadline-toggle" class="deadline-toggle" type="button" aria-label="Buka notifikasi deadline">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;">
        <circle cx="12" cy="13" r="8"></circle>
        <path d="M12 9v4l2 2"></path>
        <path d="M9 2h6"></path>
        <path d="M15 2v2"></path>
        <path d="M9 2v2"></path>
    </svg>
    Deadline
</button>
@endif

<a
    id="wa-button"
    href="https://wa.me/6281234567890?text=Halo%20admin%2C%20saya%20ingin%20bertanya%20tentang%20dashboard%20ormawa."
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Hubungi via WhatsApp"
    onmouseenter="animateWaHoverIn(this)"
    onmouseleave="animateWaHoverOut(this)"
    ontouchstart="animateWaHoverIn(this)"
    ontouchend="animateWaHoverOut(this)"
    onclick="animateWaClick(this)"
    style="position: fixed; right: 20px; bottom: 20px; z-index: 9999; display: inline-flex; align-items: center; justify-content: center; width: 52px; height: 52px; border-radius: 9999px; background: #2CB100; color: #fff; text-decoration: none; box-shadow: 0 12px 26px rgba(44, 177, 0, 0.32);"
>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 30px; height: 30px;">
        <path d="M12.04 2C6.49 2 2 6.49 2 12.04c0 1.78.47 3.52 1.35 5.04L2 22l5.06-1.33a9.99 9.99 0 0 0 4.98 1.35H12c5.55 0 10.04-4.49 10.04-10.04C22.04 6.49 17.55 2 12.04 2zm5.83 14.16c-.24.67-1.39 1.28-1.93 1.36-.49.07-1.11.1-1.79-.12-.41-.13-.94-.31-1.63-.61-2.87-1.24-4.74-4.14-4.88-4.33-.13-.18-1.17-1.56-1.17-2.97 0-1.41.74-2.11 1-2.4.26-.29.57-.36.76-.36h.55c.18 0 .42-.07.65.49.24.58.82 2.01.89 2.15.07.14.12.31.02.49-.1.18-.15.29-.3.45-.15.17-.32.37-.45.49-.15.15-.31.31-.13.6.18.29.79 1.3 1.7 2.11 1.16 1.03 2.15 1.35 2.44 1.5.29.15.46.13.63-.08.17-.21.73-.85.93-1.14.2-.29.4-.24.67-.15.27.09 1.72.81 2.01.95.29.15.49.22.56.34.07.12.07.72-.17 1.39z"/>
    </svg>
</a>

@endsection


@push('scripts')
<script>
window.animateWaHoverIn = function (el) {
    if (!el) return;
    el.style.transform = 'translateY(-4px) scale(1.06)';
    el.style.boxShadow = '0 18px 34px rgba(6, 78, 59, 0.42)';
    el.style.transition = 'transform 180ms ease, box-shadow 180ms ease';
};

window.animateWaHoverOut = function (el) {
    if (!el) return;
    el.style.transform = 'translateY(0) scale(1)';
    el.style.boxShadow = '0 12px 28px rgba(6, 78, 59, 0.34)';
    el.style.transition = 'transform 180ms ease, box-shadow 180ms ease';
};

window.animateWaClick = function (el) {
    if (!el || !el.animate) return;

    el.animate(
        [
            { transform: 'scale(1)' },
            { transform: 'scale(0.88)' },
            { transform: 'scale(1.08)' },
            { transform: 'scale(1)' }
        ],
        {
            duration: 420,
            easing: 'cubic-bezier(0.22, 1, 0.36, 1)'
        }
    );
};

@if($deadline)
(function() {
    const deadlineTime = new Date("{{ $deadline->deadline_at->toIso8601String() }}").getTime();
    
    function updateCountdown() {
        const now = new Date().getTime();
        const diff = deadlineTime - now;
        
        if (diff <= 0) {
            document.getElementById('deadline-card').classList.add('hide');
            if(document.getElementById('deadline-toggle')) document.getElementById('deadline-toggle').style.display = 'none';
            return;
        }
        
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        
        document.getElementById('cd-days').textContent = days.toString().padStart(2, '0');
        document.getElementById('cd-hours').textContent = hours.toString().padStart(2, '0');
        document.getElementById('cd-mins').textContent = mins.toString().padStart(2, '0');
    }
    
    setInterval(updateCountdown, 1000);
    updateCountdown();
})();
@endif

(function () {
    'use strict';

    const deadlineCard = document.getElementById('deadline-card');
    const deadlineClose = document.getElementById('deadline-close');
    const deadlineToggle = document.getElementById('deadline-toggle');
    const waButton = document.getElementById('wa-button');
    const DEADLINE_BOTTOM = 20;
    const WA_OPEN_GAP = 32;
    const WA_CLOSED_GAP = 12;
    let deadlineOpenHeight = deadlineCard ? deadlineCard.offsetHeight : 0;

    function updateDeadlineOpenHeight() {
        if (!deadlineCard || deadlineCard.classList.contains('hide')) return;
        const height = deadlineCard.offsetHeight;
        if (height > 0) deadlineOpenHeight = height;
    }

    function updateWaPosition() {
        if (!waButton) return;
        if (!deadlineCard) {
            waButton.style.bottom = `${DEADLINE_BOTTOM}px`;
            return;
        }

        if (deadlineCard.classList.contains('hide')) {
            const toggleHeight = deadlineToggle ? deadlineToggle.offsetHeight : 0;
            waButton.style.bottom = `${DEADLINE_BOTTOM + toggleHeight + WA_CLOSED_GAP}px`;
            return;
        }

        updateDeadlineOpenHeight();
        const cardHeight = deadlineOpenHeight || deadlineCard.offsetHeight || deadlineCard.scrollHeight || 0;
        waButton.style.bottom = `${DEADLINE_BOTTOM + cardHeight + WA_OPEN_GAP}px`;
    }

    function scheduleWaUpdate() {
        requestAnimationFrame(updateWaPosition);
    }

    if (deadlineCard && deadlineClose && deadlineToggle) {
        deadlineClose.addEventListener('click', function () {
            deadlineCard.classList.add('hide');
            deadlineToggle.classList.add('show');
            scheduleWaUpdate();
        });

        deadlineToggle.addEventListener('click', function () {
            deadlineCard.classList.remove('hide');
            deadlineToggle.classList.remove('show');
            scheduleWaUpdate();
        });

        deadlineCard.addEventListener('transitionend', function (event) {
            if (event.propertyName === 'max-height' || event.propertyName === 'padding') {
                updateDeadlineOpenHeight();
                updateWaPosition();
            }
        });
    }

    if (waButton) {
        window.addEventListener('resize', function () {
            updateDeadlineOpenHeight();
            updateWaPosition();
        });
        updateDeadlineOpenHeight();
        updateWaPosition();
    }

    const ALL_ROWS   = Array.from(document.querySelectorAll('#table-body tr:not(#empty-row)'));
    const EMPTY_ROW  = document.getElementById('empty-row');
    const INFO_FROM  = document.getElementById('showing-from');
    const INFO_TO    = document.getElementById('showing-to');
    const INFO_TOTAL = document.getElementById('showing-total');
    const BTN_WRAP   = document.getElementById('pagination-buttons');

    let _perPage  = 5;
    let _page     = 1;
    let _filtered = [...ALL_ROWS];
    let _sortCol  = null;
    let _sortDir  = 'asc';

    window.filterTable = function () {
        const q = document.getElementById('search-input').value.trim().toLowerCase();
        _filtered = ALL_ROWS.filter(row => {
            return (row.dataset.nama   || '').includes(q)
                || (row.dataset.status || '').includes(q)
                || (row.dataset.tw     || '').includes(q);
        });
        _page = 1;
        render();
    };

    window.changePerPage = function (val) {
        _perPage = parseInt(val, 10);
        _page    = 1;
        render();
    };

    window.sortTable = function (col) {
        _sortDir = (_sortCol === col && _sortDir === 'asc') ? 'desc' : 'asc';
        _sortCol = col;

        // map column name to dataset key
        const key = (col === 'nama_kegiatan') ? 'nama' : (col === 'pelaksanaan' ? 'pelaksanaan' : col);

        _filtered.sort((a, b) => {
            let aVal = (a.dataset[key] || '').toString();
            let bVal = (b.dataset[key] || '').toString();

            if (key === 'pelaksanaan') {
                // dataset holds ISO date (YYYY-MM-DD) when available; compare as dates if possible
                const aTime = Date.parse(aVal);
                const bTime = Date.parse(bVal);
                let cmp;
                if (!isNaN(aTime) && !isNaN(bTime)) cmp = aTime - bTime; // earlier first
                else cmp = aVal.localeCompare(bVal, 'id', { numeric: true });
                return _sortDir === 'asc' ? cmp : -cmp;
            }

            const cmp = aVal.localeCompare(bVal, 'id', { numeric: true });
            return _sortDir === 'asc' ? cmp : -cmp;
        });

        _page = 1;
        render();

        // update header indicators (rotate the small svg) for user feedback
        try {
            document.querySelectorAll('button[data-sort]').forEach(btn => {
                const svg = btn.querySelector('svg');
                if (svg) svg.style.transform = 'rotate(0deg)';
            });
            const active = document.querySelector(`button[data-sort="${col}"]`);
            if (active) {
                const svg = active.querySelector('svg');
                if (svg) svg.style.transform = (_sortDir === 'asc') ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        } catch (e) {
            // silent
        }
    };

    function render() {
        const tbody = document.getElementById('table-body');
        const total = _filtered.length;
        const pages = Math.max(1, Math.ceil(total / _perPage));
        _page       = Math.min(_page, pages);

        const start = (_page - 1) * _perPage;
        const end   = Math.min(start + _perPage, total);

        ALL_ROWS.forEach(r => r.classList.add('hidden'));
        _filtered.slice(start, end).forEach(r => {
            tbody.appendChild(r);
            r.classList.remove('hidden');
        });

        EMPTY_ROW.classList.toggle('hidden', total > 0);

        INFO_FROM.textContent  = total === 0 ? 0 : start + 1;
        INFO_TO.textContent    = end;
        INFO_TOTAL.textContent = total;

        renderPagination(pages);
    }

    function renderPagination(pages) {
        BTN_WRAP.innerHTML = '';

        BTN_WRAP.appendChild(makeBtn('&laquo; Sebelumnya', _page === 1, () => { _page--; render(); }));

        buildRange(_page, pages).forEach(p => {
            if (p === '...') {
                const dots = document.createElement('span');
                dots.className   = 'px-2 py-1.5 text-xs text-gray-400';
                dots.textContent = '…';
                BTN_WRAP.appendChild(dots);
            } else {
                const btn      = document.createElement('button');
                btn.innerHTML  = p;
                btn.disabled   = (p === _page);
                btn.className  = p === _page
                    ? 'rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm'
                    : 'rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors';
                btn.onclick    = () => { _page = p; render(); };
                BTN_WRAP.appendChild(btn);
            }
        });

        BTN_WRAP.appendChild(makeBtn('Berikutnya &raquo;', _page === pages, () => { _page++; render(); }));
    }

    function makeBtn(label, disabled, onClick) {
        const btn     = document.createElement('button');
        btn.innerHTML = label;
        btn.disabled  = disabled;
        btn.className = 'rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium '
                      + 'text-gray-600 hover:bg-gray-50 transition-colors '
                      + 'disabled:opacity-40 disabled:cursor-not-allowed';
        btn.onclick   = onClick;
        return btn;
    }

    function buildRange(cur, total) {
        if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
        const pages = [1];
        if (cur > 3) pages.push('...');
        for (let i = Math.max(2, cur - 1); i <= Math.min(total - 1, cur + 1); i++) pages.push(i);
        if (cur < total - 2) pages.push('...');
        pages.push(total);
        return pages;
    }

    render();
})();
</script>
@endpush
