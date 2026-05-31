@extends('layouts.app')

@section('title', 'Verifikasi Publikasi Kegiatan')

@push('styles')
<style>
    .modal-bounce-in {
        animation: modalBounceIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    .modal-bounce-out {
        animation: modalBounceOut 0.3s cubic-bezier(0.36, 0, 0.66, -0.56) forwards;
    }
    @keyframes modalBounceIn {
        0% {
            opacity: 0;
            transform: scale(0.9) translateY(30px);
        }
        100% {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    @keyframes modalBounceOut {
        0% {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
        100% {
            opacity: 0;
            transform: scale(0.9) translateY(30px);
        }
    }
</style>
@endpush

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
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-4">
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Menunggu Verifikasi</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $publikasis->whereIn('status', ['Menunggu', 'Pending'])->count() }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Telah Disetujui</p>
                <p class="mt-2 text-3xl font-bold text-green-600">{{ $publikasis->whereIn('status', ['Disetujui', 'Approved'])->count() }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Revisi</p>
                <p class="mt-2 text-3xl font-bold text-amber-600">{{ $publikasis->whereIn('status', ['Revisi', 'Revision'])->count() }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Ditolak</p>
                <p class="mt-2 text-3xl font-bold text-red-600">{{ $publikasis->whereIn('status', ['Ditolak', 'Rejected'])->count() }}</p>
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
                                        $transMap = [
                                            'Pending' => 'Menunggu',
                                            'Approved' => 'Disetujui',
                                            'Revision' => 'Revisi',
                                            'Rejected' => 'Ditolak',
                                            'Menunggu' => 'Menunggu',
                                            'Disetujui' => 'Disetujui',
                                            'Revisi' => 'Revisi',
                                            'Ditolak' => 'Ditolak',
                                        ];
                                        $displayStatus = $transMap[$p->status] ?? $p->status;
                                        
                                        $statusClass = [
                                            'Menunggu' => 'bg-blue-50 text-blue-700 border-blue-100',
                                            'Disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                            'Revisi' => 'bg-amber-50 text-amber-700 border-amber-100',
                                            'Ditolak' => 'bg-rose-50 text-rose-700 border-rose-100',
                                        ][$displayStatus] ?? 'bg-gray-50 text-gray-700 border-gray-100';
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">
                                        {{ $displayStatus }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button 
                                        type="button"
                                        onclick="openVerifyModal(this)"
                                        data-id="{{ $p->id_publikasi }}"
                                        data-judul="{{ $p->judul }}"
                                        data-status="{{ $p->status }}"
                                        data-catatan="{{ $p->catatan_admin }}"
                                        class="inline-flex items-center rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-gray-800"
                                    >
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
<div id="poster-modal" class="fixed inset-0 z-50 overflow-y-auto bg-black/80 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300 opacity-0" style="display: none;">
    <div class="relative max-w-4xl w-full">
        <button onclick="closePoster()" class="absolute -right-12 -top-12 text-white hover:text-gray-300 transition">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <img id="modal-img" src="" class="mx-auto max-h-[85vh] rounded-xl shadow-2xl object-contain bg-white" alt="Poster Preview">
    </div>
</div>

<!-- Modal Verifikasi -->
<div id="verify-modal" style="position: fixed; inset: 0; z-index: 9999; background: rgba(15, 23, 42, 0.65); display: none; align-items: center; justify-content: center; backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); padding: 16px; transition: opacity 0.25s ease; opacity: 0;">
    <div id="verify-modal-content" style="background: #ffffff; width: 100%; max-width: 320px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.08); border: 1px solid #e2e8f0; overflow: hidden; transform: scale(0.95) translateY(12px); opacity: 0; transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.25s ease;">
        <!-- Header -->
        <div style="background: #f8fafc; padding: 12px 16px; border-b: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; box-sizing: border-box;">
            <div>
                <h3 style="margin: 0; font-size: 14px; font-weight: 700; color: #1e293b; font-family: inherit;">Verifikasi Publikasi</h3>
                <p style="margin: 2px 0 0 0; font-size: 10px; color: #64748b; font-weight: 500; font-family: inherit;" id="verify-modal-title-desc"></p>
            </div>
            <button type="button" onclick="closeVerifyModal()" style="border: none; background: transparent; padding: 4px; cursor: pointer; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #94a3b8; transition: color 0.15s, background 0.15s;" onmouseover="this.style.color='#475569'; this.style.background='#f1f5f9';" onmouseout="this.style.color='#94a3b8'; this.style.background='transparent';">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <form id="verify-form" method="POST" action="" style="padding: 16px; margin: 0; display: flex; flex-direction: column; gap: 16px; box-sizing: border-box;">
            @csrf
            
            <!-- Status Selection -->
            <div>
                <label style="display: block; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 8px; font-family: inherit;">Pilih Status</label>
                <div style="display: flex; flex-direction: column; gap: 8px; background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; box-sizing: border-box;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 600; color: #065f46; font-family: inherit; margin: 0;">
                        <input type="radio" name="status" value="Disetujui" id="status-setuju" onchange="updatePlacementVisibility()" style="accent-color: #10b981; width: 16px; height: 16px; cursor: pointer; margin: 0;">
                        Setujui Publikasi
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 600; color: #b45309; font-family: inherit; margin: 0;">
                        <input type="radio" name="status" value="Revisi" id="status-revisi" onchange="updatePlacementVisibility()" style="accent-color: #f59e0b; width: 16px; height: 16px; cursor: pointer; margin: 0;">
                        Revisi Publikasi
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 600; color: #b91c1c; font-family: inherit; margin: 0;">
                        <input type="radio" name="status" value="Ditolak" id="status-tolak" onchange="updatePlacementVisibility()" style="accent-color: #ef4444; width: 16px; height: 16px; cursor: pointer; margin: 0;">
                        Tolak Publikasi
                    </label>
                </div>
            </div>

            <!-- Placement Selection (Hanya tampil saat Disetujui) -->
            <div id="placement-section" style="display: none;">
                <label style="display: block; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 8px; font-family: inherit;">Penempatan Publikasi</label>
                <div style="display: flex; flex-direction: column; gap: 8px; background: #f0fdf4; padding: 12px; border-radius: 8px; border: 1px solid #dcfce7; box-sizing: border-box;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 600; color: #166534; font-family: inherit; margin: 0;">
                        <input type="radio" name="placement" value="kecil" id="placement-kecil" style="accent-color: #16a34a; width: 16px; height: 16px; cursor: pointer; margin: 0;">
                        Berita Kecil (Ditampilkan di bawah)
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 600; color: #166534; font-family: inherit; margin: 0;">
                        <input type="radio" name="placement" value="besar" id="placement-besar" style="accent-color: #16a34a; width: 16px; height: 16px; cursor: pointer; margin: 0;">
                        Berita Besar (Ditampilkan di banner)
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 600; color: #166534; font-family: inherit; margin: 0;">
                        <input type="radio" name="placement" value="keduanya" id="placement-keduanya" style="accent-color: #16a34a; width: 16px; height: 16px; cursor: pointer; margin: 0;">
                        Keduanya (Tampil di kedua tempat)
                    </label>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="catatan" style="display: block; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 6px; font-family: inherit;">Catatan Admin (Opsional)</label>
                <textarea name="catatan" id="catatan" rows="2" style="width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 12px; font-family: inherit; resize: none; box-sizing: border-box; line-height: 1.4; outline: none; transition: border-color 0.15s;" onfocus="this.style.borderColor='#0f172a'" onblur="this.style.borderColor='#cbd5e1'" placeholder="Berikan catatan revisi atau penolakan..."></textarea>
            </div>

            <!-- Actions -->
            <div style="display: flex; gap: 8px; padding-top: 12px; border-top: 1px solid #e2e8f0; box-sizing: border-box;">
                <button type="button" onclick="closeVerifyModal()" style="flex: 1; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; padding: 8px 12px; font-size: 12px; font-weight: 700; color: #475569; cursor: pointer; transition: background 0.15s, color 0.15s; font-family: inherit;" onmouseover="this.style.background='#f8fafc'; this.style.color='#1e293b';" onmouseout="this.style.background='#ffffff'; this.style.color='#475569';">Batal</button>
                <button type="submit" style="flex: 1; border-radius: 8px; border: none; background: #0f172a; padding: 8px 12px; font-size: 12px; font-weight: 700; color: #ffffff; cursor: pointer; transition: background 0.15s; font-family: inherit;" onmouseover="this.style.background='#1e293b';" onmouseout="this.style.background='#0f172a';">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Safe body-append layout fixes to guarantee correct z-index fixed placement outside transforms
    document.addEventListener('DOMContentLoaded', function() {
        const verifyModal = document.getElementById('verify-modal');
        const posterModal = document.getElementById('poster-modal');
        if (verifyModal) document.body.appendChild(verifyModal);
        if (posterModal) document.body.appendChild(posterModal);
    });

    function showPoster(src) {
        const modal = document.getElementById('poster-modal');
        const img = document.getElementById('modal-img');
        img.src = src;
        modal.style.display = 'flex';
        // Force reflow
        modal.getBoundingClientRect();
        modal.classList.remove('opacity-0');
    }

    function closePoster() {
        const modal = document.getElementById('poster-modal');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    function updatePlacementVisibility() {
        const placementSection = document.getElementById('placement-section');
        const statusSetuju = document.getElementById('status-setuju').checked;
        
        if (statusSetuju) {
            placementSection.style.display = 'block';
        } else {
            placementSection.style.display = 'none';
            // Reset placement selection
            document.querySelectorAll('input[name="placement"]').forEach(radio => {
                radio.checked = false;
            });
        }
    }

    function openVerifyModal(button) {
        const id = button.getAttribute('data-id');
        const judul = button.getAttribute('data-judul');
        const currentStatus = button.getAttribute('data-status');
        const notes = button.getAttribute('data-catatan');

        const modal = document.getElementById('verify-modal');
        const content = document.getElementById('verify-modal-content');
        const form = document.getElementById('verify-form');
        
        form.action = `/admin/verifikasi-publikasi/${id}`;
        document.getElementById('catatan').value = notes || '';
        document.getElementById('verify-modal-title-desc').innerText = `Publikasi: ${judul}`;
        
        if (currentStatus === 'Disetujui' || currentStatus === 'Approved') {
            document.getElementById('status-setuju').checked = true;
        } else if (currentStatus === 'Revisi' || currentStatus === 'Revision') {
            document.getElementById('status-revisi').checked = true;
        } else if (currentStatus === 'Ditolak' || currentStatus === 'Rejected') {
            document.getElementById('status-tolak').checked = true;
        } else {
            document.getElementById('status-setuju').checked = false;
            document.getElementById('status-revisi').checked = false;
            document.getElementById('status-tolak').checked = false;
        }

        // Update placement visibility
        updatePlacementVisibility();

        modal.style.display = 'flex';
        // Force reflow
        modal.getBoundingClientRect();
        
        modal.style.opacity = '1';
        content.classList.remove('modal-bounce-out');
        content.classList.add('modal-bounce-in');
    }

    function closeVerifyModal() {
        const modal = document.getElementById('verify-modal');
        const content = document.getElementById('verify-modal-content');
        
        modal.style.opacity = '0';
        content.classList.remove('modal-bounce-in');
        content.classList.add('modal-bounce-out');
        
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
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
