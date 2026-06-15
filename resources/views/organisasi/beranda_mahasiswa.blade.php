@extends('layouts.app')

@section('title', 'Beranda Mahasiswa')

@section('content')

@php
    $user = auth()->user();
    $displayName = trim(($user?->nama_depan ?? '') . ' ' . ($user?->nama_belakang ?? ''));
    $displayName = $displayName !== '' ? $displayName : ($user?->username ?? 'teman');
    $prodi = $user?->prodi ?? 'Tidak ada';
    $userId = $user?->id_user ?? null;
    
    // Direct DB queries removed to strictly align with Frontend consuming Backend API architecture.
    // The data is fetched dynamically via Axios in the script below.
    $prestasis = collect([]);
    $myProposals = collect([]);

    $summaryCards = [
        ['title' => 'Total Prestasi', 'count' => 0, 'hint' => 'Prestasi Anda', 'color' => 'text-red-600', 'bg' => 'bg-red-50'],
        ['title' => 'Internasional', 'count' => 0, 'hint' => '0% dari total', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
        ['title' => 'Nasional', 'count' => 0, 'hint' => '0% dari total', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50'],
        ['title' => 'Regional', 'count' => 0, 'hint' => '0% dari total', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
    ];

    $tingkatStyles = [
        'Internasional' => 'bg-blue-100 text-blue-700',
        'Nasional' => 'bg-amber-100 text-amber-700',
        'Regional' => 'bg-emerald-100 text-emerald-700',
    ];
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0');

    .greeting-hero {
        position: relative;
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 16px;
        border-radius: 14px;
        border: 1px solid #fca5a5;
        background: linear-gradient(135deg, #fff1f2 0%, #ffffff 65%);
        box-shadow: 0 10px 24px rgba(225, 29, 72, 0.08);
        overflow: hidden;
    }

    .greeting-wave {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #e11d48;
        animation: greeting-pop 0.6s ease-out both;
        flex-shrink: 0;
    }

    .greeting-wave-icon {
        font-family: 'Material Symbols Rounded';
        font-size: 34px;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-shadow: 0 6px 12px rgba(225, 29, 72, 0.14);
        font-variation-settings: 'FILL' 0, 'wght' 600, 'GRAD' 0, 'opsz' 48;
        transform-origin: 50% 80%;
        animation: greeting-wiggle 2.2s ease-in-out infinite;
    }

    .greeting-title {
        font-size: 0.98rem;
        font-weight: 700;
        color: #9f1239;
        opacity: 0;
        transform: translateY(8px) scale(0.98);
        animation: greeting-pop 0.55s ease-out 0.08s forwards;
    }

    .greeting-sub {
        margin-top: 2px;
        font-size: 0.82rem;
        color: #be123c;
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
        20% { transform: rotate(8deg); }
        40% { transform: rotate(-6deg); }
        60% { transform: rotate(6deg); }
        80% { transform: rotate(-4deg); }
        100% { transform: rotate(0deg); }
    }
</style>

<div class="mb-6">
    <div class="greeting-hero mb-4">
        <div class="greeting-wave" aria-hidden="true">
            <span class="greeting-wave-icon">waving_hand</span>
        </div>
        <div>
            <div class="greeting-title">Halo, {{ $displayName }}! Selamat datang di Beranda Mahasiswa</div>
            <div class="greeting-sub">Pantau prestasi, pencapaian, dan progres dokumen mahasiswa dari satu tempat</div>
        </div>
    </div>
</div>

<div class="dashboard-shell flex flex-col gap-8">
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
        @foreach ($summaryCards as $card)
            @php
                $cardId = match($card['title']) {
                    'Total Prestasi' => 'stat-total-prestasi',
                    'Internasional'  => 'stat-internasional',
                    'Nasional'       => 'stat-nasional',
                    'Regional'       => 'stat-regional',
                    default          => '',
                };
                $hintId = $cardId ? $cardId . '-hint' : '';
            @endphp
            <div class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100 flex items-start gap-4">
                <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl {{ $card['bg'] }}">
                    <svg class="h-5 w-5 {{ $card['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400">{{ $card['title'] }}</p>
                    <p class="mt-0.5 text-2xl font-extrabold text-gray-800" @if($cardId) id="{{ $cardId }}" @endif>{{ $card['count'] }}</p>
                    <p class="mt-1 text-[11px] text-gray-500 font-medium" @if($hintId) id="{{ $hintId }}" @endif>{{ $card['hint'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-gray-100">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Daftar Prestasi Mahasiswa</h2>
            <a href="{{ route('prestasi.transkrip_prestasi') }}" class="inline-flex w-fit items-center gap-2 rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 -960 960 960" class="h-5 w-5 shrink-0">
                    <path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                </svg>
                Unduh Transkrip
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-[900px] w-full border-separate border-spacing-y-3 border-spacing-x-0 text-left text-sm text-slate-700">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 font-semibold rounded-l-lg">No</th>
                        <th class="px-4 py-3 font-semibold">Nama Kegiatan</th>
                        <th class="px-4 py-3 font-semibold">Tingkat</th>
                        <th class="px-4 py-3 font-semibold">Prestasi Dicapai</th>
                        <th class="px-4 py-3 font-semibold">Penyelenggara</th>
                        <th class="px-4 py-3 font-semibold text-center">Status</th>
                        <th class="px-4 py-3 font-semibold text-center rounded-r-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody id="prestasi-table-body">
                    @forelse ($prestasis as $index => $prestasi)
                        @php
                            $statusClasses = match($prestasi->status_verifikasi) {
                                'Valid'       => 'bg-green-100 text-green-700',
                                'Revisi'      => 'bg-amber-100 text-amber-700',
                                'Tidak Valid' => 'bg-red-100 text-red-700',
                                default       => 'bg-blue-100 text-blue-700', // Menunggu
                            };
                        @endphp
                        <tr class="group transition-colors hover:bg-slate-50">
                            <td class="bg-white px-4 py-4 align-middle border-y border-l border-slate-200 first:rounded-l-xl group-hover:bg-slate-50 text-slate-500">{{ $index + 1 }}</td>
                            <td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 font-medium text-slate-800">{{ $prestasi->nama_kompetisi }}</td>
                            <td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $tingkatStyles[$prestasi->tingkat] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $prestasi->tingkat }}
                                </span>
                            </td>
                            <td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                    {{ $prestasi->capaian ?? '-' }}
                                </span>
                            </td>
                            <td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-slate-600">{{ $prestasi->penyelenggara ?? '-' }}</td>
                            <td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-center">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusClasses }}">
                                    {{ $prestasi->status_verifikasi ?? 'Menunggu' }}
                                </span>
                            </td>
                            <td class="bg-white px-4 py-4 align-middle border-y border-r border-slate-200 last:rounded-r-xl group-hover:bg-slate-50 text-center">
                                @if($prestasi->status_verifikasi === 'Revisi')
                                    <a href="{{ route('prestasi.revisi', $prestasi->id_prestasi) }}"
                                       class="inline-flex items-center gap-1.5 rounded-lg bg-amber-500 hover:bg-amber-600 px-3 py-1.5 text-xs font-semibold text-white transition-colors whitespace-nowrap shadow-sm"
                                       title="Lakukan Revisi">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Revisi
                                    </a>
                                @else
                                    <span class="text-slate-300 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="bg-white px-4 py-8 text-center text-slate-500 first:rounded-l-xl last:rounded-r-xl border border-slate-200">
                                Belum ada prestasi. <a href="{{ route('prestasi.create') }}" class="text-red-600 hover:underline font-medium">Tambah prestasi sekarang</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PRESTASI PERLU REVISI --}}
    <div id="prestasi-revisi-container-wrap">
        @php
            $prestasiRevisi = $prestasis->where('status_verifikasi', 'Revisi')->all();
        @endphp
        @if(!empty($prestasiRevisi))
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-amber-200 mt-8 ring-2 ring-amber-50">
            <div class="px-5 py-4 border-b border-amber-200 bg-amber-50">
                <h2 class="text-base font-semibold text-amber-900 flex items-center gap-2">
                    <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    ⚠️ Prestasi Perlu Revisi
                </h2>
                <p class="text-xs text-amber-700 mt-1">Admin memberikan catatan untuk perbaikan data prestasi Anda</p>
            </div>

            <div class="divide-y divide-amber-100">
                @foreach($prestasiRevisi as $prestasi)
                <div class="p-5 hover:bg-amber-50/50 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="font-semibold text-gray-800 truncate">{{ $prestasi->nama_kompetisi }}</h3>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 whitespace-nowrap">
                                    📋 {{ $prestasi->tingkat }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                    🏆 {{ $prestasi->capaian }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">{{ $prestasi->penyelenggara }}</p>
                            
                            {{-- Catatan Admin --}}
                            @if($prestasi->catatan_admin)
                            <div class="mb-3 p-3 rounded-lg border-l-4 border-red-500 bg-red-50">
                                <p class="text-xs font-bold text-red-700 mb-1">📌 Catatan Admin:</p>
                                <p class="text-sm text-red-800 leading-relaxed">{{ $prestasi->catatan_admin }}</p>
                            </div>
                            @endif
                        </div>
                        
                        <a href="{{ route('prestasi.revisi', $prestasi->id_prestasi) }}" 
                           class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold transition-colors whitespace-nowrap">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Revisi
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-gray-100 mt-8">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Status Pengajuan Dana Prestasi</h2>
            <p class="text-xs text-gray-500 mt-1">Pantau status verifikasi ajuan dana prestasi yang kamu ajukan</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-5 py-3 font-semibold uppercase text-[11px] tracking-wider text-gray-500">No</th>
                        <th class="px-5 py-3 font-semibold uppercase text-[11px] tracking-wider text-gray-500">Nama Kegiatan</th>
                        <th class="px-5 py-3 font-semibold uppercase text-[11px] tracking-wider text-gray-500">Pelaksanaan</th>
                        <th class="px-5 py-3 font-semibold uppercase text-[11px] tracking-wider text-gray-500">Ajuan Dana</th>
                        <th class="px-5 py-3 font-semibold uppercase text-[11px] tracking-wider text-gray-500 text-center">Status</th>
                        <th class="px-5 py-3 font-semibold uppercase text-[11px] tracking-wider text-gray-500 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="proposal-table-body">
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-slate-400 italic">
                            Sedang memuat data proposal...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const token = localStorage.getItem('topkema_api_token');
    if (!token || !window.axios) {
        return;
    }

    const CURRENT_USER_ID = {{ auth()->user()->id_user }};

    // ── API: Load Informasi Kegiatan ──────────────────────────────────────────
    window.axios.get('informasi')
        .then(function (res) {
            console.log('Informasi Kegiatan loaded successfully via API:', res.data);
        })
        .catch(function (err) {
            console.error('Failed to load informasi kegiatan via API:', err);
        });

    // ── API: Load Prestasi ────────────────────────────────────────────────────
    window.axios.get('prestasi')
        .then(function (res) {
            const list = res.data.data || [];
            updateSummaryCards(list);
            updatePrestasiTable(list);
            updateRevisionSection(list);
        })
        .catch(function (err) {
            console.error('Failed to load user prestasi via API:', err);
        });

    // ── API: Load Proposal (Status Pengajuan Dana) ────────────────────────────
    window.axios.get('proposal')
        .then(function (res) {
            const list = res.data.data || [];
            updateProposalTable(list);
        })
        .catch(function (err) {
            console.error('Failed to load user proposals via API:', err);
            const tbody = document.getElementById('proposal-table-body');
            if (tbody) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-red-500 italic">
                            Gagal memuat data proposal dari API.
                        </td>
                    </tr>
                `;
            }
        });

    function updateSummaryCards(list) {
        const total = list.length;
        const internasional = list.filter(p => p.tingkat === 'Internasional').length;
        const nasional = list.filter(p => p.tingkat === 'Nasional').length;
        const regional = list.filter(p => p.tingkat === 'Regional').length;

        const elTotal = document.getElementById('stat-total-prestasi');
        const elInternasional = document.getElementById('stat-internasional');
        const elNasional = document.getElementById('stat-nasional');
        const elRegional = document.getElementById('stat-regional');

        if (elTotal) elTotal.textContent = total;
        if (elInternasional) elInternasional.textContent = internasional;
        if (elNasional) elNasional.textContent = nasional;
        if (elRegional) elRegional.textContent = regional;

        // Hints
        const elTotalHint = document.getElementById('stat-total-prestasi-hint');
        const elInternasionalHint = document.getElementById('stat-internasional-hint');
        const elNasionalHint = document.getElementById('stat-nasional-hint');
        const elRegionalHint = document.getElementById('stat-regional-hint');

        const rate = (count) => total > 0 ? ((count / total) * 100).toFixed(1) + '% dari total' : '0% dari total';

        if (elTotalHint) elTotalHint.textContent = 'Prestasi Anda';
        if (elInternasionalHint) elInternasionalHint.textContent = rate(internasional);
        if (elNasionalHint) elNasionalHint.textContent = rate(nasional);
        if (elRegionalHint) elRegionalHint.textContent = rate(regional);
    }

    function updatePrestasiTable(list) {
        const tbody = document.getElementById('prestasi-table-body');
        if (!tbody) return;

        if (list.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="bg-white px-4 py-8 text-center text-slate-500 first:rounded-l-xl last:rounded-r-xl border border-slate-200">
                        Belum ada prestasi. <a href="/prestasi/create" class="text-red-600 hover:underline font-medium">Tambah prestasi sekarang</a>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = '';
        list.forEach(function (p, index) {
            const tr = document.createElement('tr');
            tr.className = 'group transition-colors hover:bg-slate-50';

            const statusClass = p.status_verifikasi === 'Valid' ? 'bg-green-100 text-green-700' :
                                (p.status_verifikasi === 'Revisi' ? 'bg-amber-100 text-amber-700' :
                                 (p.status_verifikasi === 'Tidak Valid' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'));

            const tingkatClass = p.tingkat === 'Internasional' ? 'bg-blue-100 text-blue-700' :
                                 (p.tingkat === 'Nasional' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700');

            let actionHtml = '<span class="text-slate-300 text-xs">—</span>';
            if (p.status_verifikasi === 'Revisi') {
                actionHtml = `
                    <a href="/prestasi/revisi/${p.id_prestasi}"
                       class="inline-flex items-center gap-1.5 rounded-lg bg-amber-500 hover:bg-amber-600 px-3 py-1.5 text-xs font-semibold text-white transition-colors whitespace-nowrap shadow-sm"
                       title="Lakukan Revisi">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Revisi
                    </a>
                `;
            }

            tr.innerHTML = `
                <td class="bg-white px-4 py-4 align-middle border-y border-l border-slate-200 first:rounded-l-xl group-hover:bg-slate-50 text-slate-500">${index + 1}</td>
                <td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 font-medium text-slate-800">${p.nama_kompetisi || '-'}</td>
                <td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ${tingkatClass}">
                        ${p.tingkat || '-'}
                    </span>
                </td>
                <td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">
                        ${p.capaian || '-'}
                    </span>
                </td>
                <td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-slate-600">${p.penyelenggara || '-'}</td>
                <td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-center">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ${statusClass}">
                        ${p.status_verifikasi || 'Menunggu'}
                    </span>
                </td>
                <td class="bg-white px-4 py-4 align-middle border-y border-r border-slate-200 last:rounded-r-xl group-hover:bg-slate-50 text-center">
                    ${actionHtml}
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function updateRevisionSection(list) {
        const wrap = document.getElementById('prestasi-revisi-container-wrap');
        if (!wrap) return;

        const revisis = list.filter(p => p.status_verifikasi === 'Revisi');
        if (revisis.length === 0) {
            wrap.innerHTML = '';
            return;
        }

        let itemsHtml = '';
        revisis.forEach(function (p) {
            let noteHtml = '';
            if (p.catatan_admin) {
                noteHtml = `
                    <div class="mb-3 p-3 rounded-lg border-l-4 border-red-500 bg-red-50">
                        <p class="text-xs font-bold text-red-700 mb-1">📌 Catatan Admin:</p>
                        <p class="text-sm text-red-800 leading-relaxed">${p.catatan_admin}</p>
                    </div>
                `;
            }

            itemsHtml += `
                <div class="p-5 hover:bg-amber-50/50 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="font-semibold text-gray-800 truncate">${p.nama_kompetisi || '-'}</h3>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 whitespace-nowrap">
                                    📋 ${p.tingkat || '-'}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                    🏆 ${p.capaian || '-'}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">${p.penyelenggara || '-'}</p>
                            ${noteHtml}
                        </div>
                        
                        <a href="/prestasi/revisi/${p.id_prestasi}" 
                           class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold transition-colors whitespace-nowrap">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Revisi
                        </a>
                    </div>
                </div>
            `;
        });

        wrap.innerHTML = `
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-amber-200 mt-8 ring-2 ring-amber-50">
                <div class="px-5 py-4 border-b border-amber-200 bg-amber-50">
                    <h2 class="text-base font-semibold text-amber-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        ⚠️ Prestasi Perlu Revisi
                    </h2>
                    <p class="text-xs text-amber-700 mt-1">Admin memberikan catatan untuk perbaikan data prestasi Anda</p>
                </div>
                <div class="divide-y divide-amber-100">
                    ${itemsHtml}
                </div>
            </div>
        `;
    }

    function updateProposalTable(list) {
        const tbody = document.getElementById('proposal-table-body');
        if (!tbody) return;

        if (list.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-slate-400 italic">
                        Belum ada pengajuan proposal kegiatan.
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = '';
        list.forEach(function (prop, index) {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50/50 transition-colors';

            const statusClass = prop.status === 'Disetujui' || prop.status === 'Approved' || prop.status === 'Selesai' ? 'bg-green-100 text-green-700' :
                                (prop.status === 'Menunggu' || prop.status === 'Pending' || prop.status === 'Cek LPJ' ? 'bg-blue-100 text-blue-700' : 
                                 (prop.status === 'Revisi' || prop.status === 'Revisi LPJ' || prop.status === 'Revision' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700'));

            let dateStr = '-';
            if (prop.waktu_kegiatan) {
                try {
                    const dateObj = new Date(prop.waktu_kegiatan);
                    dateStr = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                } catch (e) {
                    dateStr = prop.waktu_kegiatan;
                }
            }

            const amount = prop.besar_ajuan ? parseFloat(prop.besar_ajuan) : 0;
            const amountFormatted = 'Rp ' + amount.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });

            let actionHtml = '-';
            if (prop.status === 'Menunggu' || prop.status === 'Revisi' || prop.status === 'Pending' || prop.status === 'Revision') {
                actionHtml = `
                    <a href="/organisasi/${prop.id_proposal}/edit" 
                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600 transition hover:bg-blue-100" 
                       title="Edit Proposal">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                        </svg>
                    </a>
                `;
            } else if (prop.status === 'Disetujui' || prop.status === 'Approved') {
                actionHtml = `
                    <a href="/organisasi/${prop.id_proposal}/lpj?type=mahasiswa" 
                       class="inline-flex h-8 px-2.5 items-center justify-center rounded-lg bg-green-50 text-green-600 font-semibold text-xs transition hover:bg-green-100" 
                       title="Upload LPJ">
                       Upload LPJ
                    </a>
                `;
            } else if (prop.status === 'Revisi LPJ') {
                actionHtml = `
                    <a href="/organisasi/lpj/${prop.id_proposal}/revisi?type=mahasiswa" 
                       class="inline-flex h-8 px-2.5 items-center justify-center rounded-lg bg-amber-50 text-amber-600 font-semibold text-xs transition hover:bg-amber-100" 
                       title="Revisi LPJ">
                       Revisi LPJ
                    </a>
                `;
            }

            tr.innerHTML = `
                <td class="px-5 py-4 text-gray-500">${index + 1}</td>
                <td class="px-5 py-4 font-medium text-gray-800">
                    <a href="/organisasi/${prop.id_proposal}" class="transition-colors hover:text-blue-600 hover:underline" title="Lihat detail kegiatan">
                        ${prop.nama_kegiatan || '-'}
                    </a>
                </td>
                <td class="px-5 py-4 text-gray-600">${dateStr}</td>
                <td class="px-5 py-4 text-gray-600">${amountFormatted}</td>
                <td class="px-5 py-4 text-center">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ${statusClass}">
                        ${prop.status || 'Menunggu'}
                    </span>
                </td>
                <td class="px-5 py-4 text-center">
                    ${actionHtml}
                </td>
            `;
            tbody.appendChild(tr);
        });
    }
});
</script>
@endpush
@endsection