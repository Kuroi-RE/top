@extends('layouts.app')

@section('title', 'Input Prestasi - Detail Kompetisi')

@section('content')
<div class="laporan-prestasi-page min-h-screen bg-gray-100 flex justify-center items-start px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold leading-tight text-gray-800 sm:text-3xl">Input Prestasi - Detail Kompetisi</h1>
        </div>

        <form method="POST" action="#" class="mt-4 sm:mt-8">
            @csrf

            <!-- Nama Kompetisi -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="nama_kompetisi" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Nama Kompetisi</label>
                <div class="flex-1 min-w-0 w-full">
                    <input
                        id="nama_kompetisi"
                        type="text"
                        name="nama_kompetisi"
                        class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                </div>
            </div>

            <!-- Penyelenggara -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="penyelenggara" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Penyelenggara</label>
                <div class="flex-1 min-w-0 w-full">
                    <input
                        id="penyelenggara"
                        type="text"
                        name="penyelenggara"
                        class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                </div>
            </div>

            <!-- Pelaksanaan -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Pelaksanaan</label>
                <div class="flex-1 min-w-0 w-full flex items-center gap-6 pl-2">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="pelaksanaan" value="Luring"
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Luring</span>
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="pelaksanaan" value="Daring"
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Daring</span>
                    </label>
                </div>
            </div>

            <!-- Waktu Kompetisi -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="waktu_kompetisi" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Waktu Kompetisi</label>
                <div class="flex-1 min-w-0 w-full">
                    <input
                        id="waktu_kompetisi"
                        type="date"
                        name="waktu_kompetisi"
                        class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                </div>
            </div>

            <!-- Tanggal Pengumuman -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="tanggal_pengumuman" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Tanggal Pengumuman</label>
                <div class="flex-1 min-w-0 w-full">
                    <input
                        id="tanggal_pengumuman"
                        type="date"
                        name="tanggal_pengumuman"
                        class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                </div>
            </div>

            <!-- Tingkat Kompetisi -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Tingkat Kompetisi</label>
                <div class="flex-1 min-w-0 w-full flex items-center gap-6 pl-2">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="tingkat_kompetisi" value="Internasional"
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Internasional</span>
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="tingkat_kompetisi" value="Nasional"
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Nasional</span>
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="tingkat_kompetisi" value="Regional"
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Regional</span>
                    </label>
                </div>
            </div>

            <!-- Klaster -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="klaster" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Klaster</label>
                <div class="flex-1 min-w-0 w-full">
                    <select
                        id="klaster"
                        name="klaster"
                        class="w-full h-11 md:h-12 appearance-none rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm"
                        style="-webkit-appearance: none; -moz-appearance: none; appearance: none; padding-right: 3rem; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E&quot;); background-position: right 1.25rem center; background-repeat: no-repeat; background-size: 1rem;">
                        <option value="" disabled selected>---- Pilih Klaster ----</option>
                        <option value="Klaster I Minimal 4 Provinsi" class="text-gray-700">Klaster I Minimal 4 Provinsi</option>
                        <option value="Klaster II minimal 2 provinsi" class="text-gray-700">Klaster II minimal 2 provinsi</option>
                        <option value="Klaster III minimal 4 PT di 1 provinsi" class="text-gray-700">Klaster III minimal 4 PT di 1 provinsi</option>
                    </select>
                </div>
            </div>

            <!-- Jumlah Negara -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="jumlah_negara" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Jumlah Negara</label>
                <div class="flex-1 min-w-0 w-full">
                    <select
                        id="jumlah_negara"
                        name="jumlah_negara"
                        class="w-full h-11 md:h-12 appearance-none rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm"
                        style="-webkit-appearance: none; -moz-appearance: none; appearance: none; padding-right: 3rem; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E&quot;); background-position: right 1.25rem center; background-repeat: no-repeat; background-size: 1rem;">
                        <option value="" disabled selected>--- Pilih Jumlah Negara ---</option>
                        <option value="<11 negara" class="text-gray-700">Jumlah negara &lt;11 negara</option>
                        <option value="11-20 negara" class="text-gray-700">Jumlah negara 11-20 negara</option>
                        <option value=">20 negara" class="text-gray-700">Jumlah negara &gt;20 negara</option>
                    </select>
                </div>
            </div>

            <!-- Navigasi -->
            <div class="flex flex-col-reverse gap-3 pt-8 sm:flex-row sm:items-center sm:justify-between">
                <!-- Tombol Kembali -->
                <a href="{{ route('prestasi.laporan_prestasi.biodata') }}" class="inline-flex min-w-[140px] items-center justify-center rounded-full border border-gray-400 bg-white px-5 py-2 text-sm font-medium text-gray-700 transition-all duration-300 hover:bg-gray-100 hover:border-gray-500 hover:text-gray-900 hover:-translate-y-1 hover:shadow-md focus:ring-2 focus:ring-gray-200">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    Kembali
                </a>
                
                <!-- Tombol Berikutnya -->
                <a href="{{ route('prestasi.laporan_prestasi.capaian_prestasi') }}" class="inline-flex min-w-[140px] items-center justify-center rounded-full bg-red-700 px-5 py-2 text-sm font-medium text-white transition-all duration-300 hover:bg-red-800 hover:-translate-y-1 hover:shadow-lg hover:shadow-red-200 focus:ring-2 focus:ring-red-200">
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
