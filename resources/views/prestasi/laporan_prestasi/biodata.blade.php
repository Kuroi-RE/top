@extends('layouts.app')

@section('title', 'Input Prestasi - Biodata')

@section('content')
<div class="laporan-prestasi-page min-h-screen bg-gray-100 flex justify-center items-start px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold leading-tight text-gray-800 sm:text-3xl">Input Prestasi - Biodata</h1>
        </div>

        <form method="POST" action="#" class="mt-4 space-y-6 sm:mt-8">
            @csrf

            <!-- Email -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="email" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Email</label>
                <div class="flex-1 min-w-0 w-full md:max-w-[640px]">
                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                </div>
            </div>

            <!-- Nama -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="nama" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Nama</label>
                <div class="flex-1 min-w-0 w-full md:max-w-[640px]">
                    <input
                        id="nama"
                        type="text"
                        name="nama"
                        class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                </div>
            </div>

            <!-- NIM -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="nim" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">NIM</label>
                <div class="flex-1 min-w-0 w-full md:max-w-[640px]">
                    <input
                        id="nim"
                        type="text"
                        name="nim"
                        class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                </div>
            </div>

            <!-- Program Studi -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="program_studi" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Program Studi</label>
                <div class="flex-1 min-w-0 w-full md:max-w-[640px]">
                    <select
                        id="program_studi"
                        name="program_studi"
                        class="w-full h-11 md:h-12 appearance-none rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm"
                        style="-webkit-appearance: none; -moz-appearance: none; appearance: none; padding-right: 3rem; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E&quot;); background-position: right 1.25rem center; background-repeat: no-repeat; background-size: 1rem;">
                        <option value="" disabled selected>---- Pilih Prodi ----</option>
                        <option value="D3 Teknik Telekomunikasi" class="text-gray-700">D3 Teknik Telekomunikasi</option>
                        <option value="S1 Teknik Telekomunikasi" class="text-gray-700">S1 Teknik Telekomunikasi</option>
                        <option value="S1 Teknik Elektro" class="text-gray-700">S1 Teknik Elektro</option>
                        <option value="S1 Teknik Biomedis" class="text-gray-700">S1 Teknik Biomedis</option>
                        <option value="S1 Teknologi Pangan" class="text-gray-700">S1 Teknologi Pangan</option>
                        <option value="S1 Desain Produk" class="text-gray-700">S1 Desain Produk</option>
                        <option value="S1 Teknik Logistik" class="text-gray-700">S1 Teknik Logistik</option>
                        <option value="S1 Desain Komunikasi Visual" class="text-gray-700">S1 Desain Komunikasi Visual</option>
                        <option value="S1 Teknik Informatika" class="text-gray-700">S1 Teknik Informatika</option>
                        <option value="S1 Sain Data" class="text-gray-700">S1 Sain Data</option>
                        <option value="S1 Rekayasa Perangkat Lunak" class="text-gray-700">S1 Rekayasa Perangkat Lunak</option>
                        <option value="S1 Sistem Informasi" class="text-gray-700">S1 Sistem Informasi</option>
                        <option value="S1 Teknik Industri" class="text-gray-700">S1 Teknik Industri</option>
                        <option value="S1 Bisnis Digital" class="text-gray-700">S1 Bisnis Digital</option>
                    </select>
                </div>
            </div>

            <!-- Mewakili Ormawa -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Mewakili Ormawa</label>
                <div class="flex-1 min-w-0 w-full md:max-w-[640px] flex items-center gap-6 pl-2">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="mewakili_ormawa" value="ya"
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Ya</span>
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="mewakili_ormawa" value="tidak"
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Tidak</span>
                    </label>
                </div>
            </div>

            <!-- Button Berikutnya -->
            <div class="flex justify-end pt-8">
                <a href="{{ route('prestasi.laporan_prestasi.detail_kompetisi') }}" class="inline-flex min-w-[140px] items-center justify-center rounded-full bg-red-700 px-5 py-2 text-sm font-medium text-white transition-all duration-300 hover:bg-red-800 hover:-translate-y-1 hover:shadow-lg hover:shadow-red-200 focus:ring-2 focus:ring-red-200">
                    Berikutnya
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
