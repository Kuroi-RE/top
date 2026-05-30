@extends('layouts.app')

@section('title', 'Revisi LPJ Kegiatan')

@section('fullpage')
@endsection

@section('content')

<div class="min-h-screen bg-gray-100 flex justify-center px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

        @php
            $p = $proposal;
            $lpj = $p->lpj->first();
        @endphp

        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Revisi LPJ Kegiatan</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $p->nama_kegiatan }}</p>
            </div>
            <a href="{{ route('organisasi.create_lpj') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-red-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('organisasi.lpj.update', $p->id_proposal) }}" enctype="multipart/form-data" class="mt-4 space-y-6 sm:mt-6">
            @csrf
            @method('PUT')

            <!-- Informasi Proposal -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                <label class="pt-3 text-sm font-medium text-gray-700">Nama Kegiatan</label>
                <div class="w-full flex items-center min-h-[44px] rounded-full border border-gray-300 bg-gray-50 px-5 text-sm text-gray-600 font-medium">
                    {{ $p->nama_kegiatan }}
                </div>
            </div>

            <!-- TW -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                <label class="pt-3 text-sm font-medium text-gray-700">TW</label>
                <div class="flex flex-wrap items-center gap-5 pt-2">
                    @foreach (['I', 'II', 'III', 'IV'] as $tw)
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="radio" name="tw" value="{{ $tw }}"
                                {{ $p->ajuan_triwulan == $tw ? 'checked' : '' }}
                                class="h-5 w-5 border-gray-400 accent-red-600">
                            <span>{{ $tw }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Catatan Revisi -->
            @if($lpj && $lpj->catatan_admin)
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                <label class="pt-3 text-sm font-medium text-gray-700">Catatan Revisi</label>
                <div class="w-full flex items-center min-h-[56px] rounded-2xl border border-red-200 bg-red-50 px-5 py-3 text-sm text-red-700 font-medium">
                    {{ $lpj->catatan_admin }}
                </div>
            </div>
            @endif

            <!-- File LPJ Kegiatan -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                <p class="text-sm font-medium text-gray-700 pt-3">File LPJ</p>
                <div class="flex flex-col gap-4">
                    @if($lpj && $lpj->file_lpj)
                    <div id="current-file-wrapper" class="flex items-center gap-2 text-sm">
                        <a href="{{ asset('storage/' . $lpj->file_lpj) }}" target="_blank" class="text-blue-600 hover:underline italic font-medium">Lihat File Saat Ini</a>
                    </div>
                    @endif

                    <label class="cursor-pointer text-xs font-semibold text-red-600 hover:text-red-700">
                        <span id="file-label-text">Pilih File Baru (PDF)</span>
                        <input type="file" id="lpj-input" name="laporan" class="hidden" accept="application/pdf">
                    </label>
                </div>
            </div>

            <script>
                document.getElementById('lpj-input').addEventListener('change', function(e) {
                    const fileName = e.target.files[0]?.name;
                    if (fileName) {
                        document.getElementById('file-label-text').textContent = 'File terpilih: ' + fileName;
                        document.getElementById('file-label-text').className = 'text-green-600 font-bold';
                        if (document.getElementById('current-file-wrapper')) {
                            document.getElementById('current-file-wrapper').style.display = 'none';
                        }
                    }
                });
            </script>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('organisasi.create_lpj') }}"
                    class="inline-flex min-w-[140px] items-center justify-center rounded-full border border-gray-300 bg-white px-8 py-3 text-base font-semibold text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:border-gray-400">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex min-w-[140px] items-center justify-center rounded-full bg-red-700 px-8 py-3 text-base font-semibold text-white transition-all duration-300 hover:bg-red-800 hover:-translate-y-1 hover:shadow-lg hover:shadow-red-200 focus:ring-2 focus:ring-red-200">
                    Kirim Revisi
                    <svg class="ml-2 h-5 w-5 -rotate-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
