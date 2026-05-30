@extends('layouts.app')

@section('title', 'Prestasi Mahasiswa')
@section('page-title', 'Prestasi Mahasiswa')
@section('page-subtitle', 'Rekap data prestasi mahasiswa aktif')

@php
    $currentUser = auth()->user();
    // Direct DB queries removed to strictly align with Frontend consuming Backend API architecture.
    // The data is fetched dynamically via Axios in the script below.
    $totalPrestasi = 0;
    $internasional = 0;
    $nasional      = 0;
    $regional      = 0;
@endphp

@section('content')
<div class="p-4 sm:p-6 space-y-6">

    {{-- ══════════════════════════════════════════════════════════════
         STAT CARDS
    ══════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

        {{-- Total Prestasi --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100 flex items-start gap-4">
            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-red-100">
                <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0
                             014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42
                             3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806
                             1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42
                             3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0
                             01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438
                             3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400">Total Prestasi</p>
                <p class="mt-0.5 text-2xl font-extrabold text-gray-800" id="stat-total-prestasi">{{ $totalPrestasi }}</p>
                <p class="mt-1 text-[11px] text-green-500 font-medium">Data Terverifikasi</p>
            </div>
        </div>

        {{-- Tingkat Internasional --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100 flex items-start gap-4">
            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-blue-100">
                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8
                             3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2
                             0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0
                             11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400">Internasional</p>
                <p class="mt-0.5 text-2xl font-extrabold text-gray-800" id="stat-internasional">{{ $internasional }}</p>
                <p class="mt-1 text-[11px] text-blue-500 font-medium">Tingkat Prestasi</p>
            </div>
        </div>

        {{-- Tingkat Nasional --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100 flex items-start gap-4">
            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-yellow-100">
                <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0
                             00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0
                             00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1
                             1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1
                             1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1
                             0 00.951-.69l1.519-4.674z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400">Nasional</p>
                <p class="mt-0.5 text-2xl font-extrabold text-gray-800" id="stat-nasional">{{ $nasional }}</p>
                <p class="mt-1 text-[11px] text-yellow-500 font-medium">Tingkat Prestasi</p>
            </div>
        </div>

        {{-- Tingkat Regional --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100 flex items-start gap-4">
            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-green-100">
                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8
                             8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400">Regional</p>
                <p class="mt-0.5 text-2xl font-extrabold text-gray-800" id="stat-regional">{{ $regional }}</p>
                <p class="mt-1 text-[11px] text-green-500 font-medium">Tingkat Prestasi</p>
            </div>
        </div>

    </div>
    {{-- END STAT CARDS --}}


    {{-- ══════════════════════════════════════════════════════════════
         TABLE CARD
    ══════════════════════════════════════════════════════════════════ --}}
    <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">

        {{-- Table Header / Toolbar --}}
        <div class="flex flex-col gap-3 border-b border-gray-100 px-5 py-4
                    sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-base font-bold text-gray-800">Daftar Prestasi Mahasiswa</h2>
                <p class="mt-0.5 text-xs text-gray-400">Menampilkan seluruh data prestasi yang telah diinputkan</p>
            </div>
            <div class="flex items-center gap-2">
                {{-- Search --}}
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        type="text"
                        placeholder="Cari prestasi..."
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2 pl-9 pr-4
                               text-sm text-gray-700 placeholder-gray-400 outline-none
                               focus:border-red-400 focus:bg-white focus:ring-2 focus:ring-red-100
                               transition-all sm:w-52"
                    />
                </div>
                {{-- Tambah Button --}}
                <a
                    href="{{ route('prestasi.laporan_prestasi.biodata') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-red-700 px-4 py-2
                           text-sm font-semibold text-white shadow-sm
                           hover:bg-red-800 active:bg-red-900 transition-colors flex-shrink-0"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah
                </a>
            </div>
        </div>

        {{-- Responsive Table Wrapper --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">No</th>
                        <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Nama Mahasiswa</th>
                        <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">NIM</th>
                        <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Nama Prestasi</th>
                        <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Tingkat</th>
                        <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Capaian</th>
                        <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Tahun</th>
                        <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 text-center">Status</th>
                        <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="prestasi-tbody">
                    <tr id="prestasi-loading-row">
                        <td colspan="9" class="px-5 py-10 text-center text-gray-400 italic">
                            Sedang memuat data prestasi...
                        </td>
                    </tr>
                    <tr id="prestasi-empty-row" style="display:none;">
                        <td colspan="9" class="px-5 py-10 text-center text-gray-400 italic">
                            Belum ada data prestasi terverifikasi.
                        </td>
                    </tr>
                    <tr id="prestasi-no-results" style="display:none;">
                        <td colspan="9" class="px-5 py-10 text-center text-gray-400 italic">
                            Tidak ada data yang cocok dengan pencarian.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Table Footer / Pagination --}}
        <div class="flex flex-col items-center justify-between gap-3 border-t border-gray-100 px-5 py-3.5 sm:flex-row">
            <p class="text-xs text-gray-400">
                Menampilkan <span class="font-semibold text-gray-600" id="prestasi-showing-from">0</span>&ndash;<span class="font-semibold text-gray-600" id="prestasi-showing-to">0</span>
                dari <span class="font-semibold text-gray-600" id="prestasi-total">0</span> data
            </p>
            <div class="flex items-center gap-1" id="prestasi-pagination-buttons">
                {{-- Rendered by JS --}}
            </div>
        </div>

    </div>
    {{-- END TABLE CARD --}}

    <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="text-base font-bold text-gray-800">Status Pengajuan Dana Prestasi</h2>
            <p class="mt-0.5 text-xs text-gray-400">Pantau status verifikasi ajuan dana prestasi yang kamu ajukan</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="px-5 py-3 text-xs font-semibold uppercase text-gray-500">No</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase text-gray-500">Nama Kegiatan</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase text-gray-500">Pelaksanaan</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase text-gray-500">Ajuan Dana</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase text-gray-500 text-center">Status</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase text-gray-500 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="proposal-tbody">
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-gray-400 italic">
                            Sedang memuat data proposal...
                        </td>
                    </tr>
                </tbody>
            </table>
    </div>

    {{-- Detail Prestasi Modal --}}
    <div id="detailPrestasiModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-opacity duration-300">
        <div class="w-full max-w-2xl rounded-2xl bg-white p-6 shadow-2xl transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span>🏆</span> Detail Laporan Prestasi
                </h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto space-y-5 pr-2">
                <!-- Grid detail -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase">Nama Kompetisi</label>
                        <p id="modal-kompetisi" class="text-sm font-semibold text-gray-800 mt-0.5">-</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase">Penyelenggara</label>
                        <p id="modal-penyelenggara" class="text-sm font-semibold text-gray-800 mt-0.5">-</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase">Tingkat</label>
                        <p id="modal-tingkat" class="text-sm font-semibold text-gray-800 mt-0.5">-</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase">Capaian / Juara</label>
                        <p id="modal-capaian" class="text-sm font-semibold text-gray-800 mt-0.5">-</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase">Kategori</label>
                        <p id="modal-kategori" class="text-sm font-semibold text-gray-800 mt-0.5">-</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase">Tahun</label>
                        <p id="modal-tahun" class="text-sm font-semibold text-gray-800 mt-0.5">-</p>
                    </div>
                </div>

                <!-- Status & Catatan -->
                <div class="p-4 rounded-xl border border-gray-100 bg-gray-50 flex items-start gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <label class="text-xs font-semibold text-gray-400 uppercase">Status Verifikasi:</label>
                            <span id="modal-status" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold">-</span>
                        </div>
                        <div id="modal-catatan-container" class="mt-2.5 hidden">
                            <label class="text-xs font-semibold text-red-500 uppercase block">Catatan Revisi / Keterangan:</label>
                            <p id="modal-catatan" class="text-sm text-red-700 bg-red-50 border border-red-100 rounded-lg p-3 mt-1 font-medium">-</p>
                        </div>
                    </div>
                </div>

                <!-- Anggota & Dosen -->
                <div class="space-y-3">
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase block">Anggota Tim</label>
                        <p id="modal-anggota" class="text-sm text-gray-800 mt-0.5">-</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase block">Dosen Pendamping</label>
                        <p id="modal-dosen" class="text-sm text-gray-800 mt-0.5">-</p>
                    </div>
                </div>

                <!-- Evidence / Berkas -->
                <div>
                    <label class="text-xs font-semibold text-gray-400 uppercase block mb-2">Evidence / Berkas Pendukung</label>
                    <div id="modal-dokumen" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Dynamic docs -->
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end gap-2">
                <button onclick="closeDetailModal()" class="px-5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm font-semibold text-gray-700 transition">
                    Tutup
                </button>
                <a id="revisi-button" href="#" style="display: none;" class="px-5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-sm font-semibold text-white transition">
                    ⚠️ Revisi Data
                </a>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    let ALL_DATA = [];
    let _filtered = [];
    let _perPage = 10;
    let _page = 1;

    const token = localStorage.getItem('topkema_api_token');
    if (!token || !window.axios) {
        return;
    }

    // ── API: Load Prestasi ────────────────────────────────────────────────────
    window.axios.get('prestasi')
        .then(function (res) {
            ALL_DATA = res.data.data || [];
            _filtered = [...ALL_DATA];
            
            // Hide loading row
            const loadingRow = document.getElementById('prestasi-loading-row');
            if (loadingRow) loadingRow.style.display = 'none';

            // Update stats
            updateStats(ALL_DATA);

            // Initial render
            _page = 1;
            render();
        })
        .catch(function (err) {
            console.error('Failed to load user prestasi via API:', err);
            const tbody = document.getElementById('prestasi-tbody');
            if (tbody) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="px-5 py-10 text-center text-red-500 italic">
                            Gagal memuat data prestasi dari API.
                        </td>
                    </tr>
                `;
            }
        });

    // ── API: Load Proposal ────────────────────────────────────────────────────
    window.axios.get('proposal')
        .then(function (res) {
            const list = res.data.data || [];
            updateProposalTable(list);
        })
        .catch(function (err) {
            console.error('Failed to load proposals via API:', err);
            const tbody = document.getElementById('proposal-tbody');
            if (tbody) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-red-500 italic">
                            Gagal memuat data proposal dari API.
                        </td>
                    </tr>
                `;
            }
        });

    function updateStats(list) {
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
    }

    function updateProposalTable(list) {
        const tbody = document.getElementById('proposal-tbody');
        if (!tbody) return;

        if (list.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-gray-400 italic">
                        Belum ada pengajuan ajuan dana prestasi.
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = '';
        list.forEach(function (prop, index) {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50/70 transition-colors';

            const statusClass = prop.status === 'Disetujui' ? 'bg-green-100 text-green-700' :
                                (prop.status === 'Menunggu' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700');

            let dateStr = '-';
            if (prop.waktu_kegiatan) {
                try {
                    const dateObj = new Date(prop.waktu_kegiatan);
                    dateStr = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                } catch (e) {}
            }

            const amount = prop.besar_ajuan ? parseFloat(prop.besar_ajuan) : 0;
            const amountFormatted = 'Rp ' + amount.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });

            let fileLink = '<span class="text-gray-400">-</span>';
            if (prop.file) {
                fileLink = `<a href="/storage/${prop.file}" target="_blank" class="text-blue-500 hover:underline text-xs">Lihat File</a>`;
            }

            tr.innerHTML = `
                <td class="px-5 py-3.5 text-gray-400">${index + 1}</td>
                <td class="px-5 py-3.5 font-medium text-gray-800">${prop.nama_kegiatan || '-'}</td>
                <td class="px-5 py-3.5 text-gray-600">${dateStr}</td>
                <td class="px-5 py-3.5 text-gray-600">${amountFormatted}</td>
                <td class="px-5 py-3.5 text-center">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ${statusClass}">
                        ${prop.status || 'Menunggu'}
                    </span>
                </td>
                <td class="px-5 py-3.5 text-center">
                    ${fileLink}
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Hook search input
    const searchInput = document.querySelector('input[placeholder="Cari prestasi..."]');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            _filtered = ALL_DATA.filter(p =>
                (p.nama_kompetisi || '').toLowerCase().includes(q) ||
                (p.tingkat || '').toLowerCase().includes(q) ||
                (p.capaian || '').toLowerCase().includes(q) ||
                (p.penyelenggara || '').toLowerCase().includes(q)
            );
            _page = 1;
            render();
        });
    }

    function render() {
        const tbody = document.getElementById('prestasi-tbody');
        if (!tbody) return;

        // Clear existing dynamic rows
        const rows = tbody.querySelectorAll('.prestasi-row');
        rows.forEach(r => r.remove());

        const total = _filtered.length;
        
        // Update total indicator
        const totalEl = document.getElementById('prestasi-total');
        if (totalEl) totalEl.textContent = total;

        const emptyRow = document.getElementById('prestasi-empty-row');
        const noResults = document.getElementById('prestasi-no-results');

        if (ALL_DATA.length === 0) {
            if (emptyRow) emptyRow.style.display = '';
            if (noResults) noResults.style.display = 'none';
            updatePagination(0);
            return;
        }

        if (total === 0) {
            if (emptyRow) emptyRow.style.display = 'none';
            if (noResults) noResults.style.display = '';
            updatePagination(0);
            return;
        }

        if (emptyRow) emptyRow.style.display = 'none';
        if (noResults) noResults.style.display = 'none';

        const pages = Math.max(1, Math.ceil(total / _perPage));
        _page = Math.min(_page, pages);

        const start = (_page - 1) * _perPage;
        const end = Math.min(start + _perPage, total);

        // Count text
        const fromEl = document.getElementById('prestasi-showing-from');
        const toEl = document.getElementById('prestasi-showing-to');
        if (fromEl) fromEl.textContent = start + 1;
        if (toEl) toEl.textContent = end;

        const displayed = _filtered.slice(start, end);
        displayed.forEach(function (item, index) {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50/70 transition-colors prestasi-row';
            
            const num = start + index + 1;
            const user = item.user || {};
            const namaMhs = (user.nama_depan || '') + ' ' + (user.nama_belakang || '');
            const avatarInitials = ((user.nama_depan || '').substring(0, 1) + (user.nama_belakang || '').substring(0, 1)).toUpperCase() || 'M';
            const nimMhs = user.nim || '-';
            const year = item.created_at ? new Date(item.created_at).getFullYear() : '-';

            const statusClass = item.status_verifikasi == 'Valid' ? 'bg-green-100 text-green-700' : 
                               (item.status_verifikasi == 'Menunggu' ? 'bg-blue-100 text-blue-700' : 
                               (item.status_verifikasi == 'Revisi' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700'));

            // Prep detailed dataset for modal
            const dosenStr = item.dosen && item.dosen.length > 0
                ? item.dosen.map(d => d.nama + ' (' + (d.nip || '-') + ')').join(', ')
                : 'Tidak ada';
                
            const anggotaStr = item.anggota && item.anggota.length > 0
                ? item.anggota.map(a => a.nama + ' (' + (a.nim || '-') + ')').join(', ')
                : 'Tidak ada';

            const docsJson = JSON.stringify(item.dokumen || []);

            tr.innerHTML = `
                <td class="px-5 py-3.5 text-gray-400 font-medium">${num}</td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-red-100 text-xs font-bold text-red-700">
                            ${avatarInitials}
                        </div>
                        <span class="font-medium text-gray-800">${namaMhs}</span>
                    </div>
                </td>
                <td class="px-5 py-3.5 text-gray-500 font-mono text-xs">${nimMhs}</td>
                <td class="px-5 py-3.5 text-gray-700 max-w-xs truncate">${item.nama_kompetisi || '-'}</td>
                <td class="px-5 py-3.5">
                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-700">
                        ${item.tingkat || '-'}
                    </span>
                </td>
                <td class="px-5 py-3.5">
                    <span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-semibold text-yellow-700">
                        🏆 ${item.capaian || '-'}
                    </span>
                </td>
                <td class="px-5 py-3.5 text-gray-500">${year}</td>
                <td class="px-5 py-3.5 text-center">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ${statusClass}">
                        ${item.status_verifikasi || 'Menunggu'}
                    </span>
                </td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-1.5">
                        <a href="javascript:void(0)" 
                           class="btn-detail rounded-lg p-1.5 text-blue-500 hover:bg-blue-50 transition-colors" 
                           title="Detail">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                    </div>
                </td>
            `;

            // Attach event listener for detail click
            tr.querySelector('.btn-detail').addEventListener('click', function() {
                showDetailModal({
                    id: item.id_prestasi,
                    kompetisi: item.nama_kompetisi,
                    penyelenggara: item.penyelenggara,
                    tingkat: item.tingkat,
                    capaian: item.capaian,
                    kategori: item.kategori,
                    tahun: year,
                    status: item.status_verifikasi,
                    catatan: item.catatan_admin || 'Tidak ada catatan revisi.',
                    anggota: anggotaStr,
                    dosen: dosenStr,
                    dokumen: docsJson
                });
            });

            tbody.appendChild(tr);
        });

        updatePagination(pages);
    }

    function updatePagination(pages) {
        const btnContainer = document.getElementById('prestasi-pagination-buttons');
        if (!btnContainer) return;
        btnContainer.innerHTML = '';

        if (pages <= 1) return;

        const prev = document.createElement('button');
        prev.className = 'rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-400 hover:bg-gray-50 transition-colors disabled:opacity-40';
        prev.textContent = '« Sebelumnya';
        prev.disabled = _page <= 1;
        prev.addEventListener('click', () => { _page--; render(); });
        btnContainer.appendChild(prev);

        for (let i = 1; i <= pages; i++) {
            const btn = document.createElement('button');
            if (i === _page) {
                btn.className = 'rounded-lg bg-red-700 px-3 py-1.5 text-xs font-semibold text-white shadow-sm';
            } else {
                btn.className = 'rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors';
            }
            btn.textContent = i;
            btn.addEventListener('click', () => { _page = i; render(); });
            btnContainer.appendChild(btn);
        }

        const next = document.createElement('button');
        next.className = 'rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-400 hover:bg-gray-50 transition-colors disabled:opacity-40';
        next.textContent = 'Berikutnya »';
        next.disabled = _page >= pages;
        next.addEventListener('click', () => { _page++; render(); });
        btnContainer.appendChild(next);
    }

    function showDetailModal(el) {
        const modal = document.getElementById('detailPrestasiModal');
        const container = modal.querySelector('.transform');
        
        // Set text values
        document.getElementById('modal-kompetisi').textContent = el.kompetisi;
        document.getElementById('modal-penyelenggara').textContent = el.penyelenggara;
        document.getElementById('modal-tingkat').textContent = el.tingkat;
        document.getElementById('modal-capaian').textContent = el.capaian;
        document.getElementById('modal-kategori').textContent = el.kategori;
        document.getElementById('modal-tahun').textContent = el.tahun;
        
        // Set status badge
        const status = el.status;
        const statusEl = document.getElementById('modal-status');
        statusEl.textContent = status;
        statusEl.className = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold';
        
        if (status === 'Valid') {
            statusEl.classList.add('bg-green-100', 'text-green-700');
        } else if (status === 'Menunggu') {
            statusEl.classList.add('bg-blue-100', 'text-blue-700');
        } else if (status === 'Revisi') {
            statusEl.classList.add('bg-amber-100', 'text-amber-700');
        } else {
            statusEl.classList.add('bg-red-100', 'text-red-700');
        }
        
        // Set Catatan
        const catatanContainer = document.getElementById('modal-catatan-container');
        if (status === 'Revisi' || (el.catatan && el.catatan !== 'Tidak ada catatan revisi.')) {
            document.getElementById('modal-catatan').textContent = el.catatan;
            catatanContainer.classList.remove('hidden');
        } else {
            catatanContainer.classList.add('hidden');
        }
        
        // Set Revisi Button (show only if status = Revisi)
        const revisiBtn = document.getElementById('revisi-button');
        if (status === 'Revisi') {
            revisiBtn.href = `/prestasi/revisi/${el.id}`;
            revisiBtn.style.display = '';
        } else {
            revisiBtn.style.display = 'none';
        }
        
        // Set Anggota & Dosen
        document.getElementById('modal-anggota').textContent = el.anggota;
        document.getElementById('modal-dosen').textContent = el.dosen;
        
        // Set Documents
        const docsContainer = document.getElementById('modal-dokumen');
        docsContainer.innerHTML = '';
        
        try {
            const docs = JSON.parse(el.dokumen);
            if (docs && docs.length > 0) {
                docs.forEach(doc => {
                    const fileUrl = `/storage/${doc.file}`;
                    const ext = doc.file.split('.').pop().toLowerCase();
                    const isImage = ['jpg','jpeg','png','gif','webp'].includes(ext);
                    
                    const docLink = document.createElement('a');
                    docLink.href = fileUrl;
                    docLink.target = '_blank';
                    docLink.className = 'flex items-center gap-2 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 transition group';
                    
                    docLink.innerHTML = `
                        <div class="h-8 w-8 flex-shrink-0 flex items-center justify-center rounded-lg ${isImage ? 'bg-blue-50 text-blue-600' : 'bg-red-50 text-red-600'}">
                            ${isImage ? `
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            ` : `
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            `}
                        </div>
                        <div class="overflow-hidden min-w-0 flex-1">
                            <p class="text-xs font-semibold text-gray-700 truncate group-hover:text-red-600 transition">${doc.jenis_dokumen}</p>
                            <p class="text-[9px] text-gray-400 uppercase">${ext}</p>
                        </div>
                    `;
                    docsContainer.appendChild(docLink);
                });
            } else {
                docsContainer.innerHTML = '<p class="text-xs text-gray-400 italic">Tidak ada dokumen evidence</p>';
            }
        } catch (e) {
            docsContainer.innerHTML = '<p class="text-xs text-gray-400 italic">Tidak ada dokumen evidence</p>';
        }
        
        // Open modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            container.classList.remove('scale-95');
            container.classList.add('scale-100');
        }, 10);
    }

    // Expose closeDetailModal globally
    window.closeDetailModal = function() {
        const modal = document.getElementById('detailPrestasiModal');
        const container = modal.querySelector('.transform');
        
        container.classList.remove('scale-100');
        container.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 150);
    };
});
</script>
@endpush
