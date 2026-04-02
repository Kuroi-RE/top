@extends('layouts.app')

@section('title', 'Beranda Ormawa Institusi')

@section('content')

@php
// Dummy data — ganti dengan query Eloquent: ProposalKegiatan::paginate($perPage)
$kegiatans = collect([
    ['no' => 1,  'tw' => 'I',   'nama_kegiatan' => 'Upgrading',              'pelaksanaan' => '17/01/2026', 'ajuan_dana' => 'Rp 200.000', 'anggaran' => 'Rp 200.000', 'status' => 'Selesai',   'lpj_keuangan' => true,  'lpj_kegiatan' => true],
    ['no' => 2,  'tw' => 'I',   'nama_kegiatan' => 'LDK',                    'pelaksanaan' => '17/02/2026', 'ajuan_dana' => 'Rp 200.000', 'anggaran' => 'Rp 200.000', 'status' => 'Pencairan', 'lpj_keuangan' => false, 'lpj_kegiatan' => false],
    ['no' => 3,  'tw' => 'I',   'nama_kegiatan' => 'Pematerian',              'pelaksanaan' => '20/02/2026', 'ajuan_dana' => 'Rp 200.000', 'anggaran' => 'Rp 200.000', 'status' => 'Acc',       'lpj_keuangan' => false, 'lpj_kegiatan' => false],
    ['no' => 4,  'tw' => 'I',   'nama_kegiatan' => 'Buka bersama Manggala',  'pelaksanaan' => '27/03/2026', 'ajuan_dana' => 'Rp 200.000', 'anggaran' => 'Rp 200.000', 'status' => 'Revisi',    'lpj_keuangan' => false, 'lpj_kegiatan' => false],
    ['no' => 5,  'tw' => 'II',  'nama_kegiatan' => 'Seminar Nasional',        'pelaksanaan' => '10/04/2026', 'ajuan_dana' => 'Rp 500.000', 'anggaran' => 'Rp 450.000', 'status' => 'Acc',       'lpj_keuangan' => false, 'lpj_kegiatan' => false],
    ['no' => 6,  'tw' => 'II',  'nama_kegiatan' => 'Pelatihan Leadership',    'pelaksanaan' => '24/04/2026', 'ajuan_dana' => 'Rp 350.000', 'anggaran' => 'Rp 350.000', 'status' => 'Pencairan', 'lpj_keuangan' => false, 'lpj_kegiatan' => false],
    ['no' => 7,  'tw' => 'II',  'nama_kegiatan' => 'Musyawarah Kerja',        'pelaksanaan' => '05/05/2026', 'ajuan_dana' => 'Rp 150.000', 'anggaran' => 'Rp 150.000', 'status' => 'Revisi',    'lpj_keuangan' => false, 'lpj_kegiatan' => false],
    ['no' => 8,  'tw' => 'III', 'nama_kegiatan' => 'Bakti Sosial',            'pelaksanaan' => '20/07/2026', 'ajuan_dana' => 'Rp 700.000', 'anggaran' => 'Rp 700.000', 'status' => 'Selesai',   'lpj_keuangan' => true,  'lpj_kegiatan' => true],
    ['no' => 9,  'tw' => 'III', 'nama_kegiatan' => 'Olimpiade Mahasiswa',     'pelaksanaan' => '15/08/2026', 'ajuan_dana' => 'Rp 600.000', 'anggaran' => 'Rp 580.000', 'status' => 'Acc',       'lpj_keuangan' => false, 'lpj_kegiatan' => false],
    ['no' => 10, 'tw' => 'IV',  'nama_kegiatan' => 'Malam Keakraban',         'pelaksanaan' => '12/11/2026', 'ajuan_dana' => 'Rp 400.000', 'anggaran' => 'Rp 400.000', 'status' => 'Pencairan', 'lpj_keuangan' => false, 'lpj_kegiatan' => false],
]);

$statusConfig = [
    'Selesai'   => ['bg' => 'bg-green-500',  'text' => 'text-white'],
    'Pencairan' => ['bg' => 'bg-blue-500',   'text' => 'text-white'],
    'Acc'       => ['bg' => 'bg-purple-600', 'text' => 'text-white'],
    'Revisi'    => ['bg' => 'bg-red-600',    'text' => 'text-white'],
];
@endphp

<div class="mb-3 flex flex-wrap items-center justify-between gap-3">
    <div class="flex items-center gap-2">
        <select
            id="per-page-select"
            onchange="changePerPage(this.value)"
            class="rounded border border-gray-300 bg-white px-2 py-1.5 text-sm text-gray-700
                   shadow-sm focus:border-red-400 focus:outline-none focus:ring-1
                   focus:ring-red-200 cursor-pointer"
        >
            <option value="5" selected>5</option>
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
        </select>
        <span class="text-sm text-gray-600">Record per page</span>
    </div>

    <div class="relative">
        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input
            id="search-input"
            type="text"
            placeholder="Search..."
            oninput="filterTable()"
            class="w-64 rounded-lg border border-gray-300 bg-white py-1.5 pl-9 pr-4
                   text-sm text-gray-700 placeholder-gray-400 shadow-sm
                   focus:border-red-400 focus:outline-none focus:ring-1 focus:ring-red-200
                   transition-all"
        />
    </div>
</div>

<div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-gray-100">
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
                        <button onclick="sortTable('nama_kegiatan')"
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
                        <button onclick="sortTable('pelaksanaan')"
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
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100" id="table-body">
                @foreach ($kegiatans as $item)
                @php
                    $cfg = $statusConfig[$item['status']] ?? ['bg' => 'bg-gray-400', 'text' => 'text-white'];
                @endphp
                <tr class="hover:bg-slate-50 transition-colors"
                    data-nama="{{ strtolower($item['nama_kegiatan']) }}"
                    data-status="{{ strtolower($item['status']) }}"
                    data-tw="{{ strtolower($item['tw']) }}">

                    <td class="px-4 py-3 text-gray-500 font-medium">{{ $item['no'] }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="font-semibold text-gray-700">{{ $item['tw'] }}</span>
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $item['nama_kegiatan'] }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $item['pelaksanaan'] }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $item['ajuan_dana'] }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $item['anggaran'] }}</td>

                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full px-3 py-1
                                     text-xs font-semibold leading-none
                                     {{ $cfg['bg'] }} {{ $cfg['text'] }}">
                            {{ $item['status'] }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-center">
                        @if ($item['lpj_keuangan'])
                            <a href="#" title="Lihat LPJ Keuangan"
                               class="inline-flex items-center justify-center rounded-lg p-1.5
                                      text-gray-500 hover:bg-blue-50 hover:text-blue-600 transition-colors">
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
                        @if ($item['lpj_kegiatan'])
                            <a href="#" title="Lihat LPJ Kegiatan"
                               class="inline-flex items-center justify-center rounded-lg p-1.5
                                      text-gray-500 hover:bg-blue-50 hover:text-blue-600 transition-colors">
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

@endsection


@push('scripts')
<script>
(function () {
    'use strict';

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
        _filtered.sort((a, b) => {
            const aVal = a.dataset[col.replace('_', '')] || '';
            const bVal = b.dataset[col.replace('_', '')] || '';
            const cmp  = aVal.localeCompare(bVal, 'id', { numeric: true });
            return _sortDir === 'asc' ? cmp : -cmp;
        });
        _page = 1;
        render();
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
