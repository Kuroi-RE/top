@extends('layouts.app')

@section('title', 'Verifikasi Publikasi Kegiatan')

@section('content')
<div class="min-h-screen bg-gray-100 px-4 py-8">
    <div class="mx-auto max-w-7xl space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Verifikasi Publikasi Kegiatan</h1>
                <p class="text-sm text-gray-500">Tinjau dan setujui poster kegiatan yang akan tampil di Landing Page.</p>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Menunggu Verifikasi</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $publikasis->where('status', 'Menunggu')->count() }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Telah Disetujui</p>
                <p class="mt-2 text-3xl font-bold text-green-600">{{ $publikasis->where('status', 'Disetujui')->count() }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Ditolak</p>
                <p class="mt-2 text-3xl font-bold text-red-600">{{ $publikasis->where('status', 'Ditolak')->count() }}</p>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500">
                    <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-700">
                        <tr>
                            <th class="px-6 py-4">Judul & Ormawa</th>
                            <th class="px-6 py-4">Caption</th>
                            <th class="px-6 py-4 text-center">Poster</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($publikasis as $p)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $p->judul }}</div>
                                    <div class="text-xs text-gray-500">{{ $p->ormawa }}</div>
                                </td>
                                <td class="px-6 py-4 max-w-xs truncate">
                                    {{ $p->caption }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="showPoster('{{ asset('storage/' . $p->poster) }}')" class="group relative inline-block h-12 w-12 overflow-hidden rounded-lg border border-gray-200">
                                        <img src="{{ asset('storage/' . $p->poster) }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-110" alt="Poster">
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/20 opacity-0 transition group-hover:opacity-100">
                                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </div>
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusClass = [
                                            'Menunggu' => 'bg-amber-50 text-amber-700 border-amber-100',
                                            'Disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                            'Ditolak' => 'bg-rose-50 text-rose-700 border-rose-100',
                                        ][$p->status] ?? 'bg-gray-50 text-gray-700 border-gray-100';
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button onclick="openVerifyModal({{ $p->id_publikasi }}, '{{ $p->judul }}', '{{ $p->status }}', '{{ $p->catatan_admin }}')" class="inline-flex items-center rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-gray-800">
                                        Update Status
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">
                                    Belum ada data publikasi untuk diverifikasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Poster -->
<div id="poster-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/80 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative max-w-4xl w-full">
        <button onclick="closePoster()" class="absolute -right-12 -top-12 text-white hover:text-gray-300 transition">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <img id="modal-img" src="" class="mx-auto max-h-[85vh] rounded-xl shadow-2xl object-contain bg-white" alt="Poster Preview">
    </div>
</div>

<!-- Modal Verifikasi -->
<div id="verify-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="w-full max-w-md scale-95 transform rounded-2xl bg-white p-6 shadow-2xl transition-all duration-300 opacity-0" id="verify-modal-content">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
            <h3 class="text-lg font-bold text-gray-900">Update Status Publikasi</h3>
            <button onclick="closeVerifyModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <form id="verify-form" method="POST" action="">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex cursor-pointer items-center justify-center rounded-xl border border-gray-200 bg-white p-3 shadow-sm transition hover:bg-gray-50 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 has-[:checked]:ring-1 has-[:checked]:ring-emerald-500">
                            <input type="radio" name="status" value="Disetujui" id="status-setuju" class="sr-only">
                            <div class="flex flex-col items-center">
                                <svg class="mb-1 h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-xs font-bold text-emerald-700 uppercase">Setujui</span>
                            </div>
                        </label>
                        <label class="relative flex cursor-pointer items-center justify-center rounded-xl border border-gray-200 bg-white p-3 shadow-sm transition hover:bg-gray-50 has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50 has-[:checked]:ring-1 has-[:checked]:ring-rose-500">
                            <input type="radio" name="status" value="Ditolak" id="status-tolak" class="sr-only">
                            <div class="flex flex-col items-center">
                                <svg class="mb-1 h-5 w-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span class="text-xs font-bold text-rose-700 uppercase">Tolak</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">Catatan Admin (Opsional)</label>
                    <textarea name="catatan" id="catatan" rows="3" class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm focus:border-gray-900 focus:ring-gray-900" placeholder="Berikan alasan jika ditolak..."></textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeVerifyModal()" class="flex-1 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" class="flex-1 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-bold text-white hover:bg-gray-800 transition">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function showPoster(src) {
        const modal = document.getElementById('poster-modal');
        const img = document.getElementById('modal-img');
        img.src = src;
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.add('opacity-100'), 10);
    }

    function closePoster() {
        const modal = document.getElementById('poster-modal');
        modal.classList.remove('opacity-100');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function openVerifyModal(id, judul, currentStatus, notes) {
        const modal = document.getElementById('verify-modal');
        const content = document.getElementById('verify-modal-content');
        const form = document.getElementById('verify-form');
        
        form.action = `/admin/verifikasi-publikasi/${id}`;
        document.getElementById('catatan').value = notes || '';
        
        if (currentStatus === 'Disetujui') {
            document.getElementById('status-setuju').checked = true;
        } else if (currentStatus === 'Ditolak') {
            document.getElementById('status-tolak').checked = true;
        } else {
            document.getElementById('status-setuju').checked = false;
            document.getElementById('status-tolak').checked = false;
        }

        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeVerifyModal() {
        const modal = document.getElementById('verify-modal');
        const content = document.getElementById('verify-modal-content');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    // Close on click outside
    window.onclick = function(event) {
        const posterModal = document.getElementById('poster-modal');
        const verifyModal = document.getElementById('verify-modal');
        if (event.target == posterModal) closePoster();
        if (event.target == verifyModal) closeVerifyModal();
    }
</script>
@endsection
