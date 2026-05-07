@extends('layouts.app')

@section('title', 'Upload LPJ Kegiatan')

@section('fullpage')
@endsection

@section('content')

<div class="min-h-screen bg-gray-100 flex justify-center px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Upload LPJ Kegiatan</h1>
        </div>

        <form method="POST" enctype="multipart/form-data" class="mt-4 space-y-6 sm:mt-6">
            @csrf

            <!-- TW -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                <label class="pt-3 text-sm font-medium text-gray-700">TW</label>
                <div class="flex flex-wrap items-center gap-5 pt-2">
                    @foreach (['I', 'II', 'III', 'IV'] as $tw)
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="radio" name="tw" value="{{ $tw }}"
                                class="h-5 w-5 border-gray-400 accent-red-600">
                            <span>{{ $tw }}</span>
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
                        class="w-full h-10 md:h-11 rounded-full border border-gray-400 bg-white px-4 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
            </div>

            <!-- Laporan -->
            <div class="grid grid-cols-1 md:grid-cols-[120px_1fr] items-start gap-3 md:gap-6">
                <label class="pt-3 text-sm font-medium text-gray-700">Laporan</label>

                <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                    <input type="file" name="laporan" class="hidden" accept="application/pdf">

                    <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                        </svg>
                        <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload LPJ Kegiatan disini</span>
                        <p class="text-xs text-gray-500">Hanya menerima ekstensi .pdf</p>
                    </div>
                </label>
            </div>

            <!-- Button -->
            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="inline-flex min-w-[140px] items-center justify-center rounded-full bg-red-700 px-6 py-2 text-sm font-medium text-white transition-all duration-300 hover:bg-red-800 hover:-translate-y-1 hover:shadow-lg hover:shadow-red-200 focus:ring-2 focus:ring-red-200">
                    Kirim
                    <svg class="ml-1 h-4 w-4 -rotate-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                </button>
            </div>

        </form>
    </div>
</div>

@endsection
