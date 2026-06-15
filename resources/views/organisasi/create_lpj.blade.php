@extends('layouts.app')

@section('title', 'Upload LPJ Kegiatan')

@section('fullpage')
@endsection

@section('content')

<div class="min-h-screen bg-gray-100 flex justify-center px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Upload LPJ Kegiatan</h1>
            <a href="{{ route('organisasi.create_lpj', ['type' => request()->query('type')]) }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-red-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('organisasi.lpj.store', [$proposal->id_proposal, 'type' => request()->query('type')]) }}" enctype="multipart/form-data" class="mt-4 space-y-6 sm:mt-6">
            @csrf

            @if ($errors->any())
                <div class="rounded-xl bg-red-50 p-4 border border-red-200 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat beberapa kesalahan:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @php
                $lpj = $proposal->lpj->first();
                $isRevision = $lpj && $lpj->status_lpj == 'Revisi';
            @endphp

            @if($isRevision)
                <div class="rounded-xl bg-red-50 border border-red-200 p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 text-red-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-red-800 uppercase tracking-wider">Catatan Revisi Admin</h3>
                            <p class="mt-1 text-sm text-red-700 leading-relaxed">{{ $lpj->catatan_admin }}</p>
                            <div class="mt-3">
                                <a href="{{ asset('storage/' . $lpj->file_lpj) }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold text-blue-700 hover:underline">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H8.25m0 12.75h7.5m-7.5 3h4.5M5.625 2.25h5.603c.895 0 1.754.356 2.386.988l4.773 4.773c.632.632.988 1.49.988 2.386v8.853a2.25 2.25 0 0 1-2.25 2.25H5.625a2.25 2.25 0 0 1-2.25-2.25V4.5a2.25 2.25 0 0 1 2.25-2.25z" />
                                    </svg>
                                    Lihat LPJ yang salah sebelumnya
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- TW -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                <label class="pt-3 text-sm font-medium text-gray-700">TW</label>
                <div class="flex flex-wrap items-center gap-5 pt-2">
                    @foreach (['I', 'II', 'III', 'IV'] as $tw)
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="radio" name="tw" value="{{ $tw }}"
                                {{ $proposal->ajuan_triwulan == $tw ? 'checked' : '' }}
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
                        value="{{ $proposal->nama_kegiatan }}"
                        readonly
                        class="w-full h-10 md:h-11 rounded-full border border-gray-200 bg-gray-50 px-4 text-sm text-gray-500 outline-none">
            </div>

            <!-- Laporan -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                <label class="pt-3 text-sm font-medium text-gray-700">Laporan</label>

                <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                    <input type="file" id="lpj-input" name="laporan" class="hidden" accept="application/pdf">

                    <div id="lpj-dropzone" class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                        </svg>
                        <span id="lpj-text" class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload LPJ Kegiatan disini</span>
                        <p id="lpj-hint" class="text-xs text-gray-500">Hanya menerima ekstensi .pdf</p>
                    </div>
                </label>
            </div>

            <script>
                document.getElementById('lpj-input').addEventListener('change', function(e) {
                    const fileName = e.target.files[0]?.name;
                    if (fileName) {
                        document.getElementById('lpj-text').textContent = 'File terpilih: ' + fileName;
                        document.getElementById('lpj-text').className = 'text-sm font-bold text-green-600';
                        document.getElementById('lpj-dropzone').style.borderColor = '#16a34a';
                        document.getElementById('lpj-dropzone').style.backgroundColor = '#f0fdf4';
                        document.getElementById('lpj-hint').style.display = 'none';
                    }
                });
            </script>

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
