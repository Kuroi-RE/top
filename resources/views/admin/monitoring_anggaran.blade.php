@extends('layouts.app')

@section('title', 'Monitoring Anggaran')
@section('page-title', 'Monitoring Anggaran')
@section('page-subtitle', 'Ringkasan transparansi anggaran proposal dan LPJ')

@push('styles')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
@endpush

@section('content')
<style>
    .page-shell {
        width: 100%;
    }

    .page-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 6px 0 2px;
    }

    .page-hero .title h1 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
    }

    .page-hero .title p {
        margin: 0;
        color: #64748b;
        font-size: 0.95rem;
    }

    .dashboard-summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 38px;
        align-items: stretch;
    }

    .content-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
    }

    .dashboard-card {
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        border: 0;
        border-radius: 12px;
        padding: 16px;
        background: #ffffff;
        min-height: 126px;
    }

    .summary-body {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .summary-meta {
        min-width: 0;
        flex: 1;
    }

    .summary-title {
        margin: 0;
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        line-height: 1.2;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .summary-value {
        margin-top: 6px;
        margin-bottom: 0;
        font-size: clamp(1.5rem, 2vw, 1.75rem);
        font-weight: 700;
        line-height: 1.1;
        letter-spacing: -0.01em;
        color: #0f172a;
        white-space: nowrap;
    }

    .dashboard-card.is-currency .summary-value {
        font-size: clamp(1.25rem, 1.6vw, 1.5rem);
        white-space: normal;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .summary-caption {
        margin-top: 6px;
        margin-bottom: 0;
        color: #94a3b8;
        font-size: 0.8rem;
        line-height: 1.2;
    }

    .dashboard-table-wrap {
        scrollbar-width: thin;
        overflow: auto;
        max-width: 100%;
    }

    .dashboard-table-wrap::-webkit-scrollbar {
        height: 10px;
    }

    .dashboard-table-wrap::-webkit-scrollbar-thumb {
        background: rgb(203 213 225);
        border-radius: 9999px;
    }

    .summary-icon {
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #fef2f2;
        color: #dc2626;
        flex-shrink: 0;
    }

    .table-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 110px;
        padding: 6px 12px;
        border-radius: 9999px;
        background: #f1f5f9;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .dashboard-summary-grid {
            grid-template-columns: 1fr;
        }

        .page-hero {
            flex-direction: column;
            align-items: flex-start;
        }

        .summary-value {
            font-size: 1.9rem;
            white-space: normal;
            word-break: break-word;
        }

        .dashboard-card.is-currency .summary-value {
            font-size: 1.5rem;
        }
    }
</style>

<div class="page-shell space-y-6">
    <div class="page-hero">
        <div class="title">
            <h1>Monitoring Anggaran</h1>
            <p>Ringkasan transparansi anggaran proposal dan LPJ</p>
        </div>
    </div>

    <div class="content-card">

        {{-- ====== BARIS 1: Proposal ====== --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">

            <div class="dashboard-card rounded-2xl bg-white">
                <div class="summary-body">
                    <div class="summary-meta">
                        <p class="summary-title">Total Proposal</p>
                        <p class="summary-value" id="stat-total-proposal">{{ $summary['totalProposal'] }}</p>
                        <p class="summary-caption">Data proposal yang tercatat</p>
                    </div>
                    <div class="summary-icon">
                        <span class="material-symbols-outlined" style="font-size: 22px;">description</span>
                    </div>
                </div>
            </div>

            <div class="dashboard-card is-currency rounded-2xl bg-white">
                <div class="summary-body">
                    <div class="summary-meta">
                        <p class="summary-title">Total Anggaran Diajukan</p>
                        <p class="summary-value" id="stat-total-diajukan">Rp {{ number_format($summary['totalDiajukan'], 0, ',', '.') }}</p>
                        <p class="summary-caption">Total pengajuan anggaran</p>
                    </div>
                    <div class="summary-icon">
                        <span class="material-symbols-outlined" style="font-size: 22px;">account_balance_wallet</span>
                    </div>
                </div>
            </div>

            <div class="dashboard-card is-currency rounded-2xl bg-white">
                <div class="summary-body">
                    <div class="summary-meta">
                        <p class="summary-title">Total Anggaran Disetujui</p>
                        <p class="summary-value" id="stat-total-disetujui">Rp {{ number_format($summary['totalDisetujui'], 0, ',', '.') }}</p>
                        <p class="summary-caption">Anggaran yang sudah disetujui</p>
                    </div>
                    <div class="summary-icon">
                        <span class="material-symbols-outlined" style="font-size: 22px;">verified</span>
                    </div>
                </div>
            </div>

        </div>



    </div>

    {{-- ====== Filter Bar ====== --}}
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px; flex-wrap: wrap;">

        {{-- Record per page --}}
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="position: relative; display: inline-block;">
                <select
                    id="mon-per-page"
                    onchange="monChangePerPage(this.value)"
                    aria-label="Pilih jumlah per halaman"
                    style="appearance: none; height: 38px; width: 68px; padding: 0 28px 0 12px;
                           border-radius: 8px; border: 1px solid #e2e8f0; background: #ffffff;
                           font-size: 13px; color: #374151; cursor: pointer;
                           box-shadow: 0 1px 3px rgba(0,0,0,0.06); outline: none;"
                >
                    <option value="5" selected>5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <span class="material-symbols-outlined" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 18px; color: #9ca3af;">expand_more</span>
            </div>
            <span style="font-size: 13px; color: #6b7280;">Record per page</span>
        </div>

        {{-- Search --}}
        <div style="position: relative; max-width: 380px; width: 100%;">
            <span class="material-symbols-outlined" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 18px; color: #e11d48; pointer-events: none;">search</span>
            <input
                id="mon-search"
                type="search"
                placeholder="Cari kegiatan..."
                aria-label="Cari kegiatan"
                oninput="monFilterTable()"
                style="width: 100%; height: 38px; padding: 0 36px 0 40px;
                       border-radius: 8px; border: 1px solid #e2e8f0; background: #ffffff;
                       font-size: 13px; color: #374151;
                       box-shadow: 0 1px 3px rgba(0,0,0,0.06); outline: none;
                       transition: border-color 0.15s, box-shadow 0.15s;"
                onfocus="this.style.borderColor='#f43f5e'; this.style.boxShadow='0 0 0 3px rgba(244,63,94,0.12)';"
                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.06)';"
            />
            <button
                type="button"
                id="mon-clear"
                onclick="document.getElementById('mon-search').value=''; monFilterTable();"
                aria-label="Kosongkan pencarian"
                style="display: none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
                       background: none; border: none; cursor: pointer; color: #9ca3af; padding: 2px;"
            >
                <span class="material-symbols-outlined" style="font-size: 16px;">close</span>
            </button>
        </div>

    </div>

    <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-gray-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-base font-bold text-gray-800">Daftar Proposal Terbaru</h2>
                <p class="mt-0.5 text-xs text-gray-400">8 data terakhir yang masuk ke monitoring anggaran</p>
            </div>
            <a
                href="{{ route('admin.monitoring_anggaran.export_pdf') }}"
                rel="noopener noreferrer"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition-colors hover:bg-gray-50 hover:text-gray-700"
                aria-label="Download laporan monitoring anggaran"
                title="Download PDF"
            >
                <span class="material-symbols-outlined" style="font-size: 20px;">download</span>
            </a>
        </div>

        <div class="dashboard-table-wrap overflow-x-auto">
            <table class="min-w-[980px] w-full border-separate border-spacing-y-3 border-spacing-x-0 text-left text-sm text-slate-700">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 font-semibold">No</th>
                        <th class="px-4 py-3 font-semibold">TW</th>
                        <th class="px-4 py-3 font-semibold">Nama Kegiatan</th>
                        <th class="px-4 py-3 font-semibold">Ajuan</th>
                        <th class="px-4 py-3 font-semibold">Disetujui</th>
                        <th class="px-4 py-3 font-semibold text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="proposal-table-body">
                    @forelse($proposals as $proposal)
                        <tr class="mon-row"
                            data-nama="{{ strtolower($proposal->nama_kegiatan) }}"
                            data-status="{{ strtolower($proposal->status ?? '') }}"
                            data-tw="{{ strtolower($proposal->ajuan_triwulan ?? '') }}">
                            <td class="bg-white px-4 py-4 align-middle first:rounded-l-xl">{{ $loop->iteration }}</td>
                            <td class="bg-white px-4 py-4 align-middle">{{ $proposal->ajuan_triwulan }}</td>
                            <td class="bg-white px-4 py-4 align-middle">{{ $proposal->nama_kegiatan }}</td>
                            <td class="bg-white px-4 py-4 align-middle whitespace-nowrap">Rp {{ number_format($proposal->besar_ajuan ?? 0, 0, ',', '.') }}</td>
                            <td class="bg-white px-4 py-4 align-middle whitespace-nowrap">Rp {{ number_format($proposal->anggaran_disetujui ?? 0, 0, ',', '.') }}</td>
                            <td class="bg-white px-4 py-4 align-middle text-center last:rounded-r-xl">
                                <span class="table-pill">{{ $proposal->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="bg-white px-4 py-8 text-center text-slate-500 first:rounded-l-xl last:rounded-r-xl">
                                Belum ada data monitoring anggaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    let ALL_ROWS = Array.from(document.querySelectorAll('.mon-row'));
    let _filtered  = [...ALL_ROWS];
    let _perPage   = 5;
    let _page      = 1;

    window.monFilterTable = function () {
        const q = (document.getElementById('mon-search').value || '').trim().toLowerCase();
        const clearBtn = document.getElementById('mon-clear');
        if (clearBtn) clearBtn.style.display = q ? 'block' : 'none';

        _filtered = ALL_ROWS.filter(row =>
            (row.dataset.nama   || '').includes(q) ||
            (row.dataset.status || '').includes(q) ||
            (row.dataset.tw     || '').includes(q)
        );
        _page = 1;
        render();
    };

    window.monChangePerPage = function (val) {
        _perPage = parseInt(val, 10);
        _page    = 1;
        render();
    };

    function render() {
        const total = _filtered.length;
        const pages = Math.max(1, Math.ceil(total / _perPage));
        _page = Math.min(_page, pages);

        const start = (_page - 1) * _perPage;
        const end   = Math.min(start + _perPage, total);

        ALL_ROWS.forEach(r => r.style.display = 'none');
        _filtered.slice(start, end).forEach(r => r.style.display = '');
    }

    // ── API Integration ───────────────────────────────────────────────────────
    function formatRupiah(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }

    function initAPI() {
        const token = localStorage.getItem('topkema_api_token');
        if (!token || !window.axios) {
            render();
            return;
        }

        // Fetch statistics real-time
        window.axios.get('monitoring/statistics')
            .then(function (res) {
                const data = res.data.data;
                const pStats = data.proposal_statistics;
                const bStats = data.budget_statistics;

                const elProposal = document.getElementById('stat-total-proposal');
                const elDiajukan = document.getElementById('stat-total-diajukan');
                const elDisetujui = document.getElementById('stat-total-disetujui');

                if (elProposal) elProposal.textContent = pStats.total;
                if (elDiajukan) elDiajukan.textContent = formatRupiah(bStats.total_budget_requested);
                if (elDisetujui) elDisetujui.textContent = formatRupiah(bStats.total_budget_approved);
            })
            .catch(function (err) {
                console.error('Failed to load real-time statistics:', err);
            });

        // Fetch recent activities/proposals list
        window.axios.get('monitoring/kegiatan', { params: { per_page: 8 } })
            .then(function (res) {
                const proposals = res.data.data || [];
                const tbody = document.getElementById('proposal-table-body');
                if (!tbody) return;

                if (proposals.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="bg-white px-4 py-8 text-center text-slate-500 first:rounded-l-xl last:rounded-r-xl">
                                Belum ada data monitoring anggaran.
                            </td>
                        </tr>
                    `;
                    ALL_ROWS = [];
                    _filtered = [];
                    render();
                    return;
                }

                tbody.innerHTML = '';
                proposals.forEach(function (proposal, idx) {
                    const tr = document.createElement('tr');
                    tr.className = 'mon-row';
                    tr.dataset.nama = (proposal.nama_kegiatan || '').toLowerCase();
                    tr.dataset.status = (proposal.status || '').toLowerCase();
                    tr.dataset.tw = (proposal.ajuan_triwulan || '').toLowerCase();

                    tr.innerHTML = `
                        <td class="bg-white px-4 py-4 align-middle first:rounded-l-xl">${idx + 1}</td>
                        <td class="bg-white px-4 py-4 align-middle">${proposal.ajuan_triwulan || '-'}</td>
                        <td class="bg-white px-4 py-4 align-middle">${proposal.nama_kegiatan || '-'}</td>
                        <td class="bg-white px-4 py-4 align-middle whitespace-nowrap">${formatRupiah(proposal.besar_ajuan || 0)}</td>
                        <td class="bg-white px-4 py-4 align-middle whitespace-nowrap">${formatRupiah(proposal.anggaran_disetujui || 0)}</td>
                        <td class="bg-white px-4 py-4 align-middle text-center last:rounded-r-xl">
                            <span class="table-pill">${proposal.status || '-'}</span>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                // Re-evaluate table rows for filtering/pagination
                ALL_ROWS = Array.from(tbody.querySelectorAll('.mon-row'));
                _filtered = [...ALL_ROWS];
                render();
            })
            .catch(function (err) {
                console.error('Failed to load recent proposals list:', err);
                render();
            });
    }

    // Init page
    initAPI();
})();
</script>
@endpush
