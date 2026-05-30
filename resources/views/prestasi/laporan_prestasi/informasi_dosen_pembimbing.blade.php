@extends('layouts.app')

@section('title', 'Input Prestasi - Informasi Dosen Pembimbing')

@section('content')
<div class="laporan-prestasi-page min-h-screen bg-gray-100 flex justify-center items-start px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold leading-tight text-gray-800 sm:text-3xl">Input Prestasi - Informasi Dosen Pembimbing</h1>
        </div>

        @if (session('success'))
            <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="prestasi-dosen-form" method="POST" action="#" class="mt-4 sm:mt-8" data-prev-url="{{ route('prestasi.laporan_prestasi.capaian_prestasi') }}" data-next-url="{{ route('prestasi.laporan_prestasi.evidance') }}">
            @csrf

            <!-- Nama Dosen Pembimbing -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="nama_dosen" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Nama Dosen Pembimbing</label>
                <div class="flex-1 min-w-0 w-full">
                    <input id="nama_dosen" type="text" name="nama_dosen" class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                </div>
            </div>

            <!-- NIDN Dosen Pembimbing -->
            <div class="flex flex-row items-start w-full mb-6 gap-2">
                <label for="nidn_dosen" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700 pt-3">NIDN Dosen Pembimbing</label>
                <div class="flex-1 min-w-0 w-full">
                    <input id="nidn_dosen" type="text" name="nidn_dosen" maxlength="10" class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                    <p class="mt-1.5 pl-4 text-xs text-gray-400">Maks. 10 digit angka</p>
                </div>
            </div>

            <!-- NIP Dosen Pembimbing -->
            <div class="flex flex-row items-start w-full mb-6 gap-2">
                <label for="nip_dosen" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700 pt-3">NIP Dosen Pembimbing</label>
                <div class="flex-1 min-w-0 w-full">
                    <input id="nip_dosen" type="text" name="nip_dosen" maxlength="18" class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                    <p class="mt-1.5 pl-4 text-xs text-gray-400">Maks. 18 digit angka</p>
                </div>
            </div>

            <!-- Prodi Dosen Pembimbing -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="prodi_dosen" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Prodi Dosen Pembimbing</label>
                <div class="flex-1 min-w-0 w-full">
                    <select id="prodi_dosen" name="prodi_dosen"
                        class="w-full h-11 md:h-12 appearance-none rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm"
                        style="-webkit-appearance:none;background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E&quot;);background-position:right 1.25rem center;background-repeat:no-repeat;background-size:1rem;padding-right:3rem;">
                        <option value="" disabled selected>---- Pilih Prodi ----</option>
                        <option value="S1 Teknik Telekomunikasi">S1 Teknik Telekomunikasi</option>
                        <option value="S1 Teknik Elektro">S1 Teknik Elektro</option>
                        <option value="S1 Teknik Biomedis">S1 Teknik Biomedis</option>
                        <option value="S1 Teknologi Pangan">S1 Teknologi Pangan</option>
                        <option value="S1 Teknik Industri">S1 Teknik Industri</option>
                        <option value="S1 Teknik Logistik">S1 Teknik Logistik</option>
                        <option value="S1 Teknik Informatika">S1 Teknik Informatika</option>
                        <option value="S1 Sistem Informasi">S1 Sistem Informasi</option>
                        <option value="S1 Rekayasa Perangkat Lunak">S1 Rekayasa Perangkat Lunak</option>
                        <option value="S1 Sains Data">S1 Sains Data</option>
                        <option value="S1 Desain Komunikasi Visual (DKV)">S1 Desain Komunikasi Visual (DKV)</option>
                        <option value="S1 Desain Produk">S1 Desain Produk</option>
                        <option value="S1 Bisnis Digital">S1 Bisnis Digital</option>
                        <option value="D3 Teknik Telekomunikasi">D3 Teknik Telekomunikasi</option>
                    </select>
                </div>
            </div>


            <div class="border-t border-gray-200 my-6"></div>

            <!-- Navigasi -->
            <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                <!-- Tombol Kembali -->
                <button type="button" id="prestasi-dosen-prev" class="inline-flex min-w-[140px] items-center justify-center rounded-full border border-gray-400 bg-white px-5 py-2 text-sm font-medium text-gray-700 transition-all duration-300 hover:bg-gray-100 hover:border-gray-500 hover:text-gray-900 hover:-translate-y-1 hover:shadow-md focus:ring-2 focus:ring-gray-200">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    Kembali
                </button>
                
                <!-- Tombol Berikutnya -->
                <button type="button" id="prestasi-dosen-next" class="inline-flex min-w-[140px] items-center justify-center rounded-full bg-red-700 px-5 py-2 text-sm font-medium text-white transition-all duration-300 hover:bg-red-800 hover:-translate-y-1 hover:shadow-lg hover:shadow-red-200 focus:ring-2 focus:ring-red-200">
                    Berikutnya
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const storageKey = 'prestasi-laporan-wizard';
    const form = document.getElementById('prestasi-dosen-form');
    const prevButton = document.getElementById('prestasi-dosen-prev');
    const nextButton = document.getElementById('prestasi-dosen-next');

    function loadState() {
        try {
            return JSON.parse(localStorage.getItem(storageKey) || '{}');
        } catch (error) {
            return {};
        }
    }

    function saveState(partial) {
        const current = loadState();
        const merged = { ...current, ...partial };
        localStorage.setItem(storageKey, JSON.stringify(merged));
        return merged;
    }

    function collectDosen() {
        const dosen = {};
        form.querySelectorAll('input, select').forEach(function (field) {
            if (!field.name) return;
            if (field.type === 'radio' && !field.checked) return;
            dosen[field.name] = field.value;
        });
        return dosen;
    }

    function fillFields() {
        const state = loadState().informasi_dosen || {};
        ['nama_dosen', 'nidn_dosen', 'nip_dosen', 'prodi_dosen'].forEach(function (name) {
            const input = form.querySelector(`[name="${name}"]`);
            if (input && state[name]) input.value = state[name];
        });
    }

    form.addEventListener('input', function () {
        saveState({ informasi_dosen: collectDosen() });
    });

    form.addEventListener('change', function () {
        saveState({ informasi_dosen: collectDosen() });
    });

    prevButton.addEventListener('click', function () {
        saveState({ informasi_dosen: collectDosen() });
        window.location.href = form.dataset.prevUrl;
    });

    nextButton.addEventListener('click', function () {
        saveState({ informasi_dosen: collectDosen() });
        window.location.href = form.dataset.nextUrl;
    });

    fillFields();
});
</script>
@endsection
