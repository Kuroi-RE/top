@extends('layouts.app')

@section('title', 'Publikasi Kegiatan Ormawa')

@section('content')

<div class="min-h-screen bg-gray-100 flex justify-center px-4 py-8">
    <div class="w-full max-w-5xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Publikasi Kegiatan Ormawa</h1>
                <p class="text-sm text-gray-500">Kelola publikasi kegiatan dan status tayang.</p>
            </div>
            <a
                href="{{ route('organisasi.publikasi_create') }}"
                class="inline-flex items-center justify-center rounded-full bg-red-700 px-5 py-2 text-sm font-semibold text-white transition hover:bg-red-800"
            >
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Publikasi Baru
            </a>
        </div>

        <!-- Ringkasan Publikasi -->
        <div class="flex flex-wrap gap-6">
            <div class="flex-1 min-w-[220px] rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <p class="text-xs text-gray-500 uppercase">Tampil di Beranda</p>
                <p id="stat-published" class="mt-2 text-2xl font-bold text-gray-800">0</p>
                <p class="mt-1 text-xs text-gray-500">Poster sudah tayang</p>
            </div>
            <div class="flex-1 min-w-[220px] rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <p class="text-xs text-gray-500 uppercase">Pending</p>
                <p id="stat-pending" class="mt-2 text-2xl font-bold text-gray-800">0</p>
                <p class="mt-1 text-xs text-gray-500">Menunggu verifikasi</p>
            </div>
            <div class="flex-1 min-w-[220px] rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <p class="text-xs text-gray-500 uppercase">Ditolak</p>
                <p id="stat-rejected" class="mt-2 text-2xl font-bold text-gray-800">0</p>
                <p class="mt-1 text-xs text-gray-500">Perlu perbaikan</p>
            </div>
        </div>

        <!-- Kuota Informasi -->
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-base font-semibold text-gray-800">Kuota Informasi</p>
                    <p class="mt-1 text-sm text-gray-500">Kegiatan Mingguan</p>
                </div>
                <div class="text-base font-semibold text-red-600">
                    <span id="quota-used">0</span>/<span id="quota-total">3</span>
                </div>
            </div>
            <div class="mt-3">
                <div class="h-2 w-full rounded-full bg-gray-200">
                    <div id="quota-bar" class="h-2 rounded-full bg-red-600" style="width: 0%"></div>
                </div>
                <p id="quota-desc" class="mt-3 text-xs text-gray-500">Tersisa 3 slot pengunggahan poster kegiatan minggu ini.</p>
                <p id="quota-warn" class="mt-1 text-xs font-semibold text-red-600 hidden">Kuota minggu ini sudah habis.</p>
            </div>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-base font-semibold text-gray-800">Daftar Publikasi</h2>
                <a
                    href="{{ route('organisasi.publikasi_export') }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition-colors hover:bg-gray-50 hover:text-gray-700"
                    aria-label="Download publikasi"
                    title="Download"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l4-4m-4 4l-4-4M5 21h14" />
                    </svg>
                </a>
            </div>

            @php
                $publikasiItems = $publikasiItems ?? collect([]);
            @endphp

            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Caption</th>
                            <th class="px-4 py-3">Link</th>
                            <th class="px-4 py-3">File</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($publikasiItems as $item)
                            <tr class="text-gray-700">
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $item['judul'] ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $item['caption'] ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if (!empty($item['link']))
                                        <a href="{{ $item['link'] }}" target="_blank" rel="noopener noreferrer"
                                           class="text-blue-600 hover:underline">
                                            {{ $item['link'] }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button type="button" class="rounded-lg bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Edit</button>
                                        <button type="button" class="rounded-lg bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">Delete</button>
                                        <button type="button" class="rounded-lg bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500">
                                    Belum ada publikasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const MAX = 3;
        const key = 'publikasi_quota_v1';
        const statusKey = 'publikasi_status_counts_v1';
        const usedEl = document.getElementById('quota-used');
        const totalEl = document.getElementById('quota-total');
        const barEl = document.getElementById('quota-bar');
        const descEl = document.getElementById('quota-desc');
        const warnEl = document.getElementById('quota-warn');
        const publishedEl = document.getElementById('stat-published');
        const pendingEl = document.getElementById('stat-pending');
        const rejectedEl = document.getElementById('stat-rejected');

        function getWeekKey(date) {
            const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
            const dayNum = d.getUTCDay() || 7;
            d.setUTCDate(d.getUTCDate() + 4 - dayNum);
            const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
            const week = Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
            return `${d.getUTCFullYear()}-W${String(week).padStart(2, '0')}`;
        }

        const weekKey = getWeekKey(new Date());

        function getStore() {
            try {
                return JSON.parse(localStorage.getItem(key) || '{}');
            } catch (e) {
                return {};
            }
        }

        function getStatusStore() {
            try {
                return JSON.parse(localStorage.getItem(statusKey) || '{}');
            } catch (e) {
                return {};
            }
        }

        function getStatusCounts() {
            const store = getStatusStore();
            return {
                published: Number(store.published || 0),
                pending: Number(store.pending || 0),
                rejected: Number(store.rejected || 0),
            };
        }

        function getCount() {
            const store = getStore();
            return Number(store[weekKey] || 0);
        }

        function updateUI(count) {
            const used = Math.min(count, MAX);
            const remaining = Math.max(0, MAX - used);
            if (usedEl) usedEl.textContent = used;
            if (totalEl) totalEl.textContent = MAX;
            if (barEl) barEl.style.width = `${(used / MAX) * 100}%`;
            if (descEl) {
                descEl.textContent = remaining > 0
                    ? `Tersisa ${remaining} slot pengunggahan poster kegiatan minggu ini.`
                    : 'Kuota minggu ini sudah habis.';
            }
            if (warnEl) warnEl.classList.toggle('hidden', remaining > 0);
        }

        function updateStatusUI() {
            const counts = getStatusCounts();
            if (publishedEl) publishedEl.textContent = counts.published;
            if (pendingEl) pendingEl.textContent = counts.pending;
            if (rejectedEl) rejectedEl.textContent = counts.rejected;
        }

        updateUI(getCount());
        updateStatusUI();
    })();
</script>
@endpush
