@extends('layouts.app')

@section('title', 'Form Revisi')

@section('fullpage')
@endsection

@section('content')

<div class="min-h-screen bg-gray-100 flex justify-center px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

        @php
            $p = $proposal;
        @endphp

        <form method="POST" action="{{ route('organisasi.update', $p->id_proposal) }}" enctype="multipart/form-data" class="mt-4 space-y-6 sm:mt-6">
            @csrf
            @method('PUT')

            <!-- Ajuan TW -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                <label class="pt-3 text-sm font-medium text-gray-700">Ajuan TW</label>
                <div class="flex flex-wrap items-center gap-5 pt-2">
                    @foreach (['I', 'II', 'III', 'IV'] as $tw)
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="radio" name="ajuan_tw" value="{{ $tw }}"
                                {{ $p->ajuan_triwulan == $tw ? 'checked' : '' }}
                                class="h-5 w-5 border-gray-400 accent-red-600">
                            <span>{{ $tw }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Kategori Proposal (Only for Ormawa) -->
            @if(!auth()->user()->isMahasiswa())
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                <label class="pt-3 text-sm font-medium text-gray-700">Kategori Proposal</label>
                <div class="flex flex-wrap items-center gap-5 pt-2">
                    @foreach (['Ormawa', 'Prestasi'] as $cat)
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="radio" name="category" value="{{ $cat }}" {{ (old('category', $p->category) ?? 'Ormawa') == $cat ? 'checked' : '' }}
                                class="h-5 w-5 border-gray-400 accent-red-600">
                            <span>{{ $cat }}</span>
                        </label>
                    @endforeach
                    @error('category') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            @else
            <input type="hidden" name="category" value="Prestasi">
            @endif

            <!-- Resiko Proposal -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                <label class="pt-3 text-sm font-medium text-gray-700">Resiko Proposal</label>
                <div class="flex flex-wrap items-center gap-5 pt-2">
                    @foreach (['Rendah', 'Sedang', 'Tinggi'] as $r)
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="radio" name="risiko_proposal" value="{{ $r }}"
                                {{ $p->risiko_proposal == $r ? 'checked' : '' }}
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
                    value="{{ old('nama_kegiatan', $p->nama_kegiatan) }}"
                    class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-white px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
            </div>

            <!-- Waktu Kegiatan -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-center gap-3 md:gap-6">
                <label for="waktu_kegiatan" class="text-sm font-medium text-gray-700">Waktu Kegiatan</label>
                <input
                    id="waktu_kegiatan"
                    type="date"
                    name="waktu_kegiatan"
                    value="{{ old('waktu_kegiatan', \Carbon\Carbon::parse($p->waktu_kegiatan)->format('Y-m-d')) }}"
                    class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-white px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
            </div>

            <!-- Besar Ajuan -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-center gap-3 md:gap-6">
                <label for="besar_ajuan" class="text-sm font-medium text-gray-700">Besar Ajuan</label>
                <input
                    id="besar_ajuan"
                    type="number"
                    name="besar_ajuan"
                    value="{{ old('besar_ajuan', $p->besar_ajuan) }}"
                    class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-white px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
            </div>

            <!-- Honor Pelatih -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                <label class="text-sm font-medium leading-5 text-gray-700 pt-3 md:pt-0 md:leading-normal">
                    Apakah pengajuan dana untuk honor pelatih?
                </label>
                <div class="flex flex-wrap items-center gap-5 pt-3 md:pt-0">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="honor_pelatih" value="Ya" {{ $p->honor_pelatih == 'Ya' ? 'checked' : '' }} class="h-5 w-5 border-gray-400 accent-red-600">
                        <span>Ya</span>
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="honor_pelatih" value="Tidak" {{ $p->honor_pelatih == 'Tidak' ? 'checked' : '' }} class="h-5 w-5 border-gray-400 accent-red-600">
                        <span>Tidak</span>
                    </label>
                </div>
            </div>

            <!-- Revisian -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                <label class="pt-3 text-sm font-medium text-gray-700">Catatan Revisi</label>
                <div class="w-full flex items-center min-h-[56px] rounded-2xl border border-red-200 bg-red-50 px-5 py-3 text-sm text-red-700 font-medium">
                    {{ $p->catatan_admin ?? 'Tidak ada catatan revisi.' }}
                </div>
            </div>

            <!-- Proposal Kegiatan -->
            <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-center gap-3 md:gap-6">
                <p class="text-sm font-medium text-gray-700">Proposal Kegiatan</p>
                <div class="flex items-center gap-4">
                    <div id="current-file-wrapper" class="flex items-center gap-2 text-sm">
                        <a href="{{ asset('storage/' . $p->file) }}" target="_blank" class="text-blue-600 hover:underline italic font-medium">Lihat File Saat Ini</a>
                    </div>
                    <div id="file-divider" class="text-xs text-gray-400">|</div>
                    <label class="cursor-pointer text-xs font-semibold text-red-600 hover:text-red-700">
                        <span id="file-label-text">Ganti File</span>
                        <input type="file" id="proposal-input" name="proposal" class="hidden" accept="application/pdf">
                    </label>
                </div>
            </div>

            <script>
                document.getElementById('proposal-input').addEventListener('change', function(e) {
                    const fileName = e.target.files[0]?.name;
                    if (fileName) {
                        document.getElementById('file-label-text').textContent = 'File terpilih: ' + fileName;
                        document.getElementById('file-label-text').className = 'text-green-600 font-bold';
                        document.getElementById('current-file-wrapper').style.display = 'none';
                        document.getElementById('file-divider').style.display = 'none';
                    }
                });
            </script>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4">
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
