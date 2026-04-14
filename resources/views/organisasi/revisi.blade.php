@extends('layouts.app')

@section('title', 'Form Revisi')

@section('fullpage')
@endsection

@section('content')

<div class="min-h-screen bg-gray-100 flex justify-center px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Form Revisi</h1>
        </div>

        <form method="POST" enctype="multipart/form-data" class="mt-4 space-y-6 sm:mt-6">
            @csrf

            <!-- Ajuan TW -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                <label class="pt-3 text-sm font-medium text-gray-700">Ajuan TW</label>
                <div class="flex flex-wrap items-center gap-5 pt-2">
                    @foreach (['1', '2', '3', '4'] as $tw)
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="radio" name="ajuan_tw" value="{{ $tw }}"
                                {{ $tw == '1' ? 'checked' : '' }}
                                class="h-5 w-5 border-gray-400 accent-red-600">
                            <span>{{ $tw }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Resiko Proposal -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                <label class="pt-3 text-sm font-medium text-gray-700">Resiko Proposal</label>
                <div class="flex flex-wrap items-center gap-5 pt-2">
                    @foreach (['Rendah', 'Sedang', 'Tinggi'] as $r)
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="radio" name="resiko_proposal" value="{{ $r }}"
                                {{ $r == 'Rendah' ? 'checked' : '' }}
                                class="h-5 w-5 border-gray-400 accent-red-600">
                            <span>{{ $r }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Nama Kegiatan -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-center gap-3 md:gap-6">
                <label for="nama_kegiatan" class="text-sm font-medium text-gray-700">Nama Kegiatan</label>
                <input
                    id="nama_kegiatan"
                    type="text"
                    name="nama_kegiatan"
                    value="Buka Bersama Manggala"
                    class="w-full h-14 rounded-full border border-gray-400 bg-white px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
            </div>

            <!-- Waktu Kegiatan -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-center gap-3 md:gap-6">
                <label for="waktu_kegiatan" class="text-sm font-medium text-gray-700">Waktu Kegiatan</label>
                <input
                    id="waktu_kegiatan"
                    type="text"
                    name="waktu_kegiatan"
                    value="27/03/2026"
                    class="w-full h-14 rounded-full border border-gray-400 bg-white px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
            </div>

            <!-- Besar Ajuan -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-center gap-3 md:gap-6">
                <label for="besar_ajuan" class="text-sm font-medium text-gray-700">Besar Ajuan</label>
                <input
                    id="besar_ajuan"
                    type="text"
                    name="besar_ajuan"
                    value="Rp. 200.000"
                    class="w-full h-14 rounded-full border border-gray-400 bg-white px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
            </div>

            <!-- Honor Pelatih -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                <label class="text-sm font-medium leading-5 text-gray-700 pt-3 md:pt-0 md:leading-normal">
                    Apakah pengajuan dana untuk honor pelatih?
                </label>
                <div class="flex flex-wrap items-center gap-5 pt-3 md:pt-0">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="honor_pelatih" value="Ya" class="h-5 w-5 border-gray-400 accent-red-600">
                        <span>Ya</span>
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="honor_pelatih" value="Tidak" checked class="h-5 w-5 border-gray-400 accent-red-600">
                        <span>Tidak</span>
                    </label>
                </div>
            </div>

            <!-- Revisian -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                <label class="pt-3 text-sm font-medium text-gray-700">Revisian</label>
                <div class="w-full flex items-center min-h-[56px] rounded-2xl border border-gray-400 bg-white px-5 py-3 text-sm text-gray-700">
                    Lembar persetujuan dan lembar pengesahan silahkan bisa di lengkapi TTD sampai dengan pembina
                </div>
            </div>

            <!-- Proposal Kegiatan -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-center gap-3 md:gap-6">
                <p class="text-sm font-medium text-gray-700">Proposal Kegiatan</p>
                <div class="flex items-center gap-2 text-sm">
                    <a href="#" class="text-blue-600 hover:underline italic">BukaBersamaManggala.Pdf</a>
                    <button type="button" class="text-gray-500 hover:text-red-600 px-3 transition">x</button>
                </div>
            </div>

            <!-- LPJ Keuangan -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-center gap-3 md:gap-6">
                <p class="text-sm font-medium text-gray-700">LPJ Keuangan</p>
                <div class="text-sm text-gray-700">-</div>
            </div>

            <!-- LPJ Kegiatan -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                <label class="pt-3 text-sm font-medium text-gray-700">LPJ Kegiatan</label>
                <label class="block cursor-pointer w-full">
                    <input type="file" name="lpj_kegiatan" class="hidden" accept="application/pdf">
                    <div class="rounded-2xl border border-gray-400 p-5 sm:p-6">
                        <div class="flex h-32 flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 text-center bg-gray-50/50 hover:bg-gray-50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mb-2 h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 4v12m0-12l-4 4m4-4l4 4" />
                            </svg>
                            <p class="text-sm text-gray-600">Upload file di sini</p>
                        </div>
                    </div>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit"
                        class="w-full rounded-full bg-red-700 py-4 text-lg font-semibold text-white transition hover:bg-red-800">
                    Kirim
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
