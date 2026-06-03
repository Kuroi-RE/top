@extends('layouts.app')

@section('title', 'Form Verifikasi')

@section('content')

<style>
    .verify-form {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .verify-row {
        display: flex;
        align-items: center;
        column-gap: 24px;
    }

    .verify-row-top {
        align-items: flex-start;
    }

    .verify-label {
        width: 210px;
        flex-shrink: 0;
        font-size: 0.95rem;
        color: rgb(51 65 85);
    }

    .verify-value {
        flex: 1;
        min-width: 0;
        color: rgb(30 41 59);
    }

    .verify-status-wrap {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .verify-status-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: rgb(51 65 85);
        font-size: 1rem;
        line-height: 1;
    }

    .verify-radio {
        appearance: none;
        -webkit-appearance: none;
        width: 24px;
        height: 24px;
        border-radius: 9999px;
        border: 3px solid rgb(71 85 105);
        background: #fff;
        margin: 0;
        cursor: pointer;
        display: inline-block;
        transition: background-color 120ms ease, border-color 120ms ease;
    }

    .verify-radio:checked {
        background: rgb(37 99 235);
        border-color: rgb(71 85 105);
    }

    .verify-radio:focus-visible {
        outline: 2px solid rgb(191 219 254);
        outline-offset: 2px;
    }

    @media (max-width: 768px) {
        .verify-row {
            flex-direction: column;
            align-items: flex-start;
            row-gap: 8px;
        }

        .verify-label {
            width: 100%;
        }
    }
</style>

@php
    $p = $proposal;
    $formattedWaktu = \Carbon\Carbon::parse($p->waktu_kegiatan)->format('d/m/Y');
    $formattedAjuan = 'Rp. ' . number_format((float) $p->besar_ajuan, 0, ',', '.');
    
    // Check if we are in LPJ verification phase
    $lpj = $p->lpj->first();
    $isLpjPhase = ($p->status == 'Disetujui' || $p->status == 'Approved' || $p->status == 'Selesai' || $p->status == 'Cek LPJ' || $p->status == 'Revisi LPJ') && $lpj;

    // Detect type parameter
    $type = ($p instanceof \App\Models\ProposalPrestasiMahasiswa || ($p->user && $p->user->isMahasiswa())) ? 'mahasiswa' : 'ormawa';
@endphp

<div class="mx-auto max-w-4xl">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
        <h1 class="text-4xl font-bold tracking-tight text-slate-900">
            {{ $isLpjPhase ? 'Verifikasi LPJ Kegiatan' : 'Form Verifikasi' }}
        </h1>

        @if ($errors->any())
            <div class="rounded-2xl bg-red-50 border border-red-200 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-red-800">Terdapat kesalahan:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form class="mt-8 space-y-6" method="POST" action="{{ route('admin.form_verifikasi.update', $p->id_proposal) }}?type={{ $type }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <div class="verify-form text-slate-700">
                @if($p->user)
                    @if($p->user->isMahasiswa())
                        <div class="verify-row bg-slate-50/50 p-4 rounded-2xl border border-slate-100 mb-2">
                            <p class="verify-label font-bold text-slate-900">Profil Pengaju</p>
                            <div class="verify-value flex flex-col">
                                <span class="font-bold text-slate-900 text-base">{{ $p->user->nama_depan }} {{ $p->user->nama_belakang }}</span>
                                <span class="text-xs text-slate-500 font-medium mt-0.5">NIM: {{ $p->user->nim ?: $p->user->username }} &bull; Program Studi: {{ $p->user->prodi ?? '-' }}</span>
                            </div>
                        </div>
                    @else
                        <div class="verify-row bg-slate-50/50 p-4 rounded-2xl border border-slate-100 mb-2">
                            <p class="verify-label font-bold text-slate-900">Organisasi Pengaju</p>
                            <div class="verify-value flex flex-col">
                                <span class="font-bold text-slate-900 text-base">{{ $p->user->nama_belakang ?: $p->user->username }}</span>
                                <span class="text-xs text-slate-500 font-medium mt-0.5">Tipe: {{ ucfirst($p->user->ormawa_type ?? 'Ormawa') }} &bull; PIC: {{ $p->user->nama_depan }}</span>
                            </div>
                        </div>
                    @endif
                @endif

                <div class="verify-row">
                    <p class="verify-label font-medium">Ajuan TW</p>
                    <p class="verify-value">{{ $p->ajuan_triwulan }}</p>
                </div>

                <div class="verify-row">
                    <p class="verify-label font-medium">Resiko Proposal</p>
                    <p class="verify-value">{{ $p->risiko_proposal }}</p>
                </div>

                <div class="verify-row">
                    <p class="verify-label font-medium">Nama Kegiatan</p>
                    <p class="verify-value font-semibold">{{ $p->nama_kegiatan }}</p>
                </div>

                <div class="verify-row">
                    <p class="verify-label font-medium">Waktu Kegiatan</p>
                    <p class="verify-value">{{ $formattedWaktu }}</p>
                </div>

                <div class="verify-row border-b pb-4 mb-4">
                    <p class="verify-label font-medium">Besar Ajuan</p>
                    <p class="verify-value">{{ $formattedAjuan }}</p>
                </div>

                @if(!$isLpjPhase)
                    {{-- PHASE 1: PROPOSAL VERIFICATION --}}
                    <div class="verify-row">
                        <p class="verify-label font-medium">Besar Anggaran</p>
                        <input
                            id="anggaran"
                            name="besar_anggaran"
                            type="number"
                            value="{{ $p->anggaran_disetujui ?? $p->besar_ajuan }}"
                            class="verify-value w-full rounded-full border border-slate-400 bg-white px-5 py-3 text-slate-800 focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100"
                        >
                    </div>

                    <div class="verify-row">
                        <p class="verify-label italic">Proposal Kegiatan</p>
                        <a href="{{ asset('storage/' . $p->file) }}" target="_blank" class="verify-value inline-flex w-fit items-center gap-2 rounded-full border border-blue-700 px-4 py-2 text-sm font-medium text-blue-700 transition hover:bg-blue-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H8.25m0 12.75h7.5m-7.5 3h4.5M5.625 2.25h5.603c.895 0 1.754.356 2.386.988l4.773 4.773c.632.632.988 1.49.988 2.386v8.853a2.25 2.25 0 01-2.25 2.25H5.625a2.25 2.25 0 01-2.25-2.25V4.5a2.25 2.25 0 012.25-2.25z" />
                            </svg>
                            Lihat Proposal
                        </a>
                    </div>

                    <div class="verify-row verify-row-top">
                        <label for="lpj_keuangan" class="verify-label pt-6 font-medium">LPJ Keuangan (Admin)</label>
                        <label class="block w-full cursor-pointer">
                            <input type="file" id="lpj-keuangan-input" name="lpj_keuangan" class="hidden" accept="image/*,application/pdf">
                            <div id="lpj-keuangan-dropzone" class="rounded-2xl border border-dashed border-slate-300 p-8 text-center transition hover:bg-slate-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-auto h-8 w-8 text-slate-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75v-2.25m-18 0A2.25 2.25 0 005.25 14.25h13.5A2.25 2.25 0 0021 16.5m-18 0V6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v9.75m-18 0l3-3m0 0l3 3m-3-3v11.25m18-14.25l-3 3m0 0l-3-3m3 3V12" />
                                </svg>
                                <p id="lpj-keuangan-text" class="mt-2 text-slate-600">Upload bukti pencairan disini</p>
                            </div>
                        </label>
                    </div>

                    <div class="verify-row">
                        <p class="verify-label font-medium">Status Verifikasi</p>
                        <div class="verify-value verify-status-wrap">
                            <label class="verify-status-item">
                                <input type="radio" name="status" value="Revisi" class="verify-radio" {{ in_array($p->status, ['Revisi', 'Revision']) ? 'checked' : '' }}>
                                <span>Revisi</span>
                            </label>
                            <label class="verify-status-item">
                                <input type="radio" name="status" value="Disetujui" class="verify-radio" {{ in_array($p->status, ['Disetujui', 'Approved']) ? 'checked' : '' }}>
                                <span>Setuju</span>
                            </label>
                            <label class="verify-status-item">
                                <input type="radio" name="status" value="Ditolak" class="verify-radio" {{ in_array($p->status, ['Ditolak', 'Rejected']) ? 'checked' : '' }}>
                                <span>Tolak</span>
                            </label>
                        </div>
                    </div>

                    <div id="revisi-row" class="verify-row verify-row-top" style="display: none;">
                        <label for="revisi" class="verify-label pt-4 font-medium">Catatan Revisi</label>
                        <textarea id="revisi" name="revisi" rows="4" placeholder="Tuliskan detail revisi proposal di sini..." class="verify-value w-full rounded-2xl border border-slate-400 bg-white px-5 py-3 text-slate-800 focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100">{{ $p->catatan_admin }}</textarea>
                    </div>

                @else
                    {{-- PHASE 2: LPJ VERIFICATION --}}
                    <div class="verify-row">
                        <p class="verify-label font-medium">Besar Anggaran Disetujui</p>
                        <p class="verify-value font-bold text-red-700">Rp. {{ number_format($p->anggaran_disetujui, 0, ',', '.') }}</p>
                    </div>

                    <div class="verify-row">
                        <p class="verify-label italic text-slate-500">Berkas Pendukung</p>
                        <div class="verify-value flex flex-wrap gap-3">
                            <a href="{{ asset('storage/' . $p->file) }}" target="_blank" class="inline-flex items-center gap-2 rounded-full border border-blue-600 px-4 py-2 text-xs font-medium text-blue-600 transition hover:bg-blue-50">
                                Proposal
                            </a>
                            @if($p->file_lpj_keuangan)
                                <a href="{{ asset('storage/' . $p->file_lpj_keuangan) }}" target="_blank" class="inline-flex items-center gap-2 rounded-full border border-emerald-600 px-4 py-2 text-xs font-medium text-emerald-600 transition hover:bg-emerald-50">
                                    LPJ Keuangan
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="verify-row bg-slate-50 p-6 rounded-2xl border border-slate-100 flex-col !items-start gap-4">
                        <p class="text-sm font-bold text-slate-500 uppercase">File LPJ Kegiatan (Dari Ormawa)</p>
                        <a href="{{ asset('storage/' . $lpj->file_lpj) }}" target="_blank" class="inline-flex w-full items-center justify-center gap-3 rounded-xl bg-blue-600 p-4 text-white shadow-md hover:bg-blue-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75v-2.25m-18 0A2.25 2.25 0 005.25 14.25h13.5A2.25 2.25 0 0021 16.5m-18 0V6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v9.75m-18 0l3-3m0 0l3 3m-3-3v11.25m18-14.25l-3 3m0 0l-3-3m3 3V12" />
                            </svg>
                            <span class="text-lg font-bold">Lihat & Download LPJ Kegiatan</span>
                        </a>
                    </div>

                    <div class="verify-row">
                        <p class="verify-label font-medium">Verifikasi Laporan</p>
                        <div class="verify-value verify-status-wrap">
                            <label class="verify-status-item">
                                <input type="radio" name="status" value="Revisi" class="verify-radio" {{ in_array($lpj->status_lpj, ['Revisi', 'Revision']) ? 'checked' : '' }}>
                                <span>Perlu Revisi</span>
                            </label>
                            <label class="verify-status-item">
                                <input type="radio" name="status" value="Selesai" class="verify-radio" {{ in_array($p->status, ['Selesai', 'Approved']) ? 'checked' : '' }}>
                                <span>Setuju (Selesai)</span>
                            </label>
                        </div>
                    </div>

                    <div id="revisi-row" class="verify-row verify-row-top" style="display: none;">
                        <label for="revisi" class="verify-label pt-4 font-medium">Catatan Revisi LPJ</label>
                        <textarea id="revisi" name="revisi" rows="4" placeholder="Tuliskan alasan revisi laporan kegiatan..." class="verify-value w-full rounded-2xl border border-slate-400 bg-white px-5 py-3 text-slate-800 focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100">{{ $lpj->catatan_admin }}</textarea>
                    </div>
                @endif
            </div>

            <div class="flex justify-end pt-4">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-full bg-red-700 px-8 py-3 text-xl font-semibold text-white transition hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-200"
                >
                    Submit
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12h12m0 0l-4.5-4.5M18 12l-4.5 4.5" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // File Feedback
        const lpjInput = document.getElementById('lpj-keuangan-input');
        if(lpjInput) {
            lpjInput.addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name;
                if (fileName) {
                    const text = document.getElementById('lpj-keuangan-text');
                    const zone = document.getElementById('lpj-keuangan-dropzone');
                    text.textContent = 'File terpilih: ' + fileName;
                    text.className = 'mt-2 text-blue-600 font-bold';
                    zone.classList.add('bg-blue-50', 'border-blue-300');
                }
            });
        }

        // Revisi Toggle
        const statusRadios = document.querySelectorAll('input[name="status"]');
        const revisiRow = document.getElementById('revisi-row');

        const toggleRevisiRow = function () {
            const selectedStatus = document.querySelector('input[name="status"]:checked');
            revisiRow.style.display = selectedStatus && selectedStatus.value === 'Revisi' ? 'flex' : 'none';
        };

        statusRadios.forEach(function (radio) {
            radio.addEventListener('change', toggleRevisiRow);
        });

        toggleRevisiRow();
    });
</script>

@endsection
