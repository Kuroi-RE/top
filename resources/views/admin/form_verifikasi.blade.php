@extends('layouts.kema')

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
    $proposal = [
        'tw' => 'I',
        'resiko_proposal' => 'Rendah',
        'nama_kegiatan' => 'Buka Bersama Menggala',
        'waktu_kegiatan' => '27/03/2026',
        'besar_ajuan' => 'Rp. 200.000',
        'honor_pelatih' => 'Tidak',
        'besar_anggaran' => 'Rp. 200.000',
        'proposal_file' => 'ProposalKegiatan.pdf',
        'lpj_kegiatan_file' => 'ProposalKegiatan.pdf',
        'status' => 'revisi',
        'revisi' => 'comment',
    ];
@endphp

<div class="mx-auto max-w-4xl">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
        <h1 class="text-4xl font-bold tracking-tight text-slate-900">Form Verifikasi</h1>

        <br>

        <form class="mt-8 space-y-6" method="POST" action="#" enctype="multipart/form-data">
            @csrf

            <div class="verify-form text-slate-700">
                <div class="verify-row">
                    <p class="verify-label font-medium">Ajuan TW</p>
                    <p class="verify-value">{{ $proposal['tw'] }}</p>
                </div>

                <div class="verify-row">
                    <p class="verify-label font-medium">Resiko Proposal</p>
                    <p class="verify-value">{{ $proposal['resiko_proposal'] }}</p>
                </div>

                <div class="verify-row">
                    <p class="verify-label font-medium">Nama Kegiatan</p>
                    <p class="verify-value">{{ $proposal['nama_kegiatan'] }}</p>
                </div>

                <div class="verify-row">
                    <p class="verify-label font-medium">Waktu Kegiatan</p>
                    <p class="verify-value">{{ $proposal['waktu_kegiatan'] }}</p>
                </div>

                <div class="verify-row">
                    <p class="verify-label font-medium">Besar Ajuan</p>
                    <p class="verify-value">{{ $proposal['besar_ajuan'] }}</p>
                </div>

                <div class="verify-row">
                    <p class="verify-label font-medium">Apakah pengajuan dana untuk honor pelatih?</p>
                    <p class="verify-value">{{ $proposal['honor_pelatih'] }}</p>
                </div>

                <div class="verify-row">
                    <p class="verify-label font-medium">Besar Anggaran</p>
                    <input
                        id="anggaran"
                        name="besar_anggaran"
                        type="number"
                        value="{{ $proposal['besar_anggaran'] }}"
                        class="verify-value w-full rounded-full border border-slate-400 bg-white px-5 py-3 text-slate-800 focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100"
                    >
                </div>

                <div class="verify-row">
                    <p class="verify-label italic">Proposal Kegiatan</p>
                    <a href="#" class="verify-value inline-flex w-fit items-center gap-2 rounded-full border border-blue-700 px-4 py-2 text-sm font-medium text-blue-700 transition hover:bg-blue-50">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H8.25m0 12.75h7.5m-7.5 3h4.5M5.625 2.25h5.603c.895 0 1.754.356 2.386.988l4.773 4.773c.632.632.988 1.49.988 2.386v8.853a2.25 2.25 0 01-2.25 2.25H5.625a2.25 2.25 0 01-2.25-2.25V4.5a2.25 2.25 0 012.25-2.25z" />
                        </svg>
                        {{ $proposal['proposal_file'] }}
                    </a>
                </div>

                <div class="verify-row verify-row-top">
                    <p class="verify-label pt-4 italic">LPJ Keuangan</p>

                    <label class="verify-value flex cursor-pointer flex-col items-center justify-center rounded-xl border border-dashed border-slate-400 bg-slate-50 p-6 text-center text-slate-600 transition hover:bg-slate-100">
                        <input type="file" name="lpj_keuangan" class="hidden">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                            class="mb-2 h-5 w-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                        </svg>

                        <span>Upload gambar disini</span>
                    </label>
                </div>

                <div class="verify-row">
                    <p class="verify-label italic">LPJ Kegiatan</p>
                    <a href="#" class="verify-value inline-flex w-fit items-center gap-2 rounded-full border border-blue-700 px-4 py-2 text-sm font-medium text-blue-700 transition hover:bg-blue-50">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H8.25m0 12.75h7.5m-7.5 3h4.5M5.625 2.25h5.603c.895 0 1.754.356 2.386.988l4.773 4.773c.632.632.988 1.49.988 2.386v8.853a2.25 2.25 0 01-2.25 2.25H5.625a2.25 2.25 0 01-2.25-2.25V4.5a2.25 2.25 0 012.25-2.25z" />
                        </svg>
                        {{ $proposal['lpj_kegiatan_file'] }}
                    </a>
                </div>

                <div class="verify-row">
                    <p class="verify-label font-medium">Status</p>
                    <div class="verify-value verify-status-wrap">
                        <label class="verify-status-item">
                            <input type="radio" name="status" value="revisi" class="verify-radio">
                            <span>Revisi</span>
                        </label>
                        <label class="verify-status-item">
                            <input type="radio" name="status" value="acc" class="verify-radio">
                            <span>ACC</span>
                        </label>
                        <label class="verify-status-item">
                            <input type="radio" name="status" value="selesai" class="verify-radio">
                            <span>Selesai</span>
                        </label>
                    </div>
                </div>

                <div id="revisi-row" class="verify-row" style="display: none;">
                    <label for="revisi" class="verify-label font-medium">Revisian</label>
                    <input
                        id="revisi"
                        name="revisi"
                        type="text"
                        value="{{ $proposal['revisi'] }}"
                        class="verify-value w-full rounded-full border border-slate-400 bg-white px-5 py-3 text-slate-800 focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100"
                    >
                </div>
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
        const statusRadios = document.querySelectorAll('input[name="status"]');
        const revisiRow = document.getElementById('revisi-row');

        const toggleRevisiRow = function () {
            const selectedStatus = document.querySelector('input[name="status"]:checked');
            revisiRow.style.display = selectedStatus && selectedStatus.value === 'revisi' ? 'flex' : 'none';
        };

        statusRadios.forEach(function (radio) {
            radio.addEventListener('change', toggleRevisiRow);
        });

        toggleRevisiRow();
    });
</script>

@endsection
