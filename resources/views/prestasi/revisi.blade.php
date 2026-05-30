@extends('layouts.app')

@section('title', 'Revisi Prestasi')

@section('content')
<div class="laporan-prestasi-page min-h-screen bg-gray-100 flex justify-center items-start px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold leading-tight text-gray-800 sm:text-3xl">Revisi Data Prestasi</h1>
            <p class="text-xs text-gray-500 mt-1">Perbaiki data prestasi sesuai dengan catatan revisi dari admin</p>
        </div>

        <!-- Admin Catatan Alert -->
        @if($prestasi->catatan_admin)
        <div class="mb-6 p-4 rounded-lg border-l-4 border-red-500 bg-red-50">
            <p class="text-xs font-bold text-red-700 mb-2">📌 Catatan Revisi dari Admin:</p>
            <p class="text-sm text-red-800 leading-relaxed">{{ $prestasi->catatan_admin }}</p>
        </div>
        @endif

        <!-- Form -->
        <form action="{{ route('prestasi.submit_revisi', $prestasi->id_prestasi) }}" method="POST" class="mt-4 sm:mt-8" enctype="multipart/form-data">
            @csrf

            <!-- Nama Kompetisi -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="nama_kompetisi" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Nama Kompetisi</label>
                <div class="flex-1 min-w-0 w-full">
                    <input
                        id="nama_kompetisi"
                        type="text"
                        name="nama_kompetisi"
                        value="{{ old('nama_kompetisi', $prestasi->nama_kompetisi) }}"
                        class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm @error('nama_kompetisi') border-red-500 @enderror"
                        required>
                </div>
            </div>
            @error('nama_kompetisi')
            <p class="text-red-600 text-xs mt-1 ml-[180px]">{{ $message }}</p>
            @enderror

            <!-- Penyelenggara -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="penyelenggara" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Penyelenggara</label>
                <div class="flex-1 min-w-0 w-full">
                    <input
                        id="penyelenggara"
                        type="text"
                        name="penyelenggara"
                        value="{{ old('penyelenggara', $prestasi->penyelenggara) }}"
                        class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm @error('penyelenggara') border-red-500 @enderror"
                        required>
                </div>
            </div>
            @error('penyelenggara')
            <p class="text-red-600 text-xs mt-1 ml-[180px]">{{ $message }}</p>
            @enderror

            <!-- Tingkat Kompetisi -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Tingkat Kompetisi</label>
                <div class="flex-1 min-w-0 w-full flex items-center gap-6 pl-2">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="tingkat" value="Internasional"
                            {{ old('tingkat', $prestasi->tingkat) === 'Internasional' ? 'checked' : '' }}
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Internasional</span>
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="tingkat" value="Nasional"
                            {{ old('tingkat', $prestasi->tingkat) === 'Nasional' ? 'checked' : '' }}
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Nasional</span>
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="tingkat" value="Regional"
                            {{ old('tingkat', $prestasi->tingkat) === 'Regional' ? 'checked' : '' }}
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Regional</span>
                    </label>
                </div>
            </div>
            @error('tingkat')
            <p class="text-red-600 text-xs mt-1 ml-[180px]">{{ $message }}</p>
            @enderror

            <!-- Prestasi Dicapai -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="capaian" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Prestasi Dicapai</label>
                <div class="flex-1 min-w-0 w-full">
                    <select
                        id="capaian"
                        name="capaian"
                        class="w-full h-11 md:h-12 appearance-none rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm @error('capaian') border-red-500 @enderror"
                        style="-webkit-appearance: none; -moz-appearance: none; appearance: none; padding-right: 3rem; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E&quot;); background-position: right 1.25rem center; background-repeat: no-repeat; background-size: 1rem;"
                        required>
                        <option value="" disabled {{ old('capaian', $prestasi->capaian) === '' ? 'selected' : '' }}>---- Pilih Juara yang di Raih ----</option>
                        <option value="Juara 1" {{ old('capaian', $prestasi->capaian) === 'Juara 1' ? 'selected' : '' }}>Juara 1</option>
                        <option value="Juara 2" {{ old('capaian', $prestasi->capaian) === 'Juara 2' ? 'selected' : '' }}>Juara 2</option>
                        <option value="Juara 3" {{ old('capaian', $prestasi->capaian) === 'Juara 3' ? 'selected' : '' }}>Juara 3</option>
                        <option value="Juara Harapan" {{ old('capaian', $prestasi->capaian) === 'Juara Harapan' ? 'selected' : '' }}>Juara Harapan</option>
                        <option value="Finalis" {{ old('capaian', $prestasi->capaian) === 'Finalis' ? 'selected' : '' }}>Finalis</option>
                        <option value="Lolos Pendanaan" {{ old('capaian', $prestasi->capaian) === 'Lolos Pendanaan' ? 'selected' : '' }}>Lolos Pendanaan</option>
                        <option value="Penerima Hibah" {{ old('capaian', $prestasi->capaian) === 'Penerima Hibah' ? 'selected' : '' }}>Penerima Hibah</option>
                        <option value="Medali Emas" {{ old('capaian', $prestasi->capaian) === 'Medali Emas' ? 'selected' : '' }}>Medali Emas</option>
                        <option value="Medali Perak" {{ old('capaian', $prestasi->capaian) === 'Medali Perak' ? 'selected' : '' }}>Medali Perak</option>
                        <option value="Medali Perunggu" {{ old('capaian', $prestasi->capaian) === 'Medali Perunggu' ? 'selected' : '' }}>Medali Perunggu</option>
                        <option value="Most Inspiration/Kategori Lainnya" {{ old('capaian', $prestasi->capaian) === 'Most Inspiration/Kategori Lainnya' ? 'selected' : '' }}>Most Inspiration/Kategori Lainnya</option>
                    </select>
                </div>
            </div>
            @error('capaian')
            <p class="text-red-600 text-xs mt-1 ml-[180px]">{{ $message }}</p>
            @enderror

            <!-- Kategori -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Kategori</label>
                <div class="flex-1 min-w-0 w-full flex items-center gap-6 pl-2">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="kategori" value="Individu"
                            {{ old('kategori', $prestasi->kategori) === 'Individu' ? 'checked' : '' }}
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Individu</span>
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="kategori" value="Kelompok"
                            {{ old('kategori', $prestasi->kategori) === 'Kelompok' ? 'checked' : '' }}
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Kelompok</span>
                    </label>
                </div>
            </div>
            @error('kategori')
            <p class="text-red-600 text-xs mt-1 ml-[180px]">{{ $message }}</p>
            @enderror

            <!-- Anggota Tim -->
            <div class="flex flex-row items-start w-full mb-6 gap-2">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700 pt-3">Anggota Tim</label>
                <div class="flex-1 min-w-0 w-full">
                    <div class="space-y-3" id="anggota-container">
                        @forelse($prestasi->anggota as $idx => $anggota)
                        <div class="anggota-item p-4 rounded-xl border border-gray-300 bg-white">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-2">
                                <input type="text" placeholder="NIM" name="anggota_nim[]" value="{{ $anggota->nim }}"
                                    class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                                <input type="text" placeholder="Nama Anggota" name="anggota_nama[]" value="{{ $anggota->nama }}"
                                    class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                                <input type="text" placeholder="Prodi" name="anggota_prodi[]" value="{{ $anggota->prodi }}"
                                    class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                            </div>
                            <button type="button" class="btn-remove-anggota text-xs text-red-600 font-semibold hover:text-red-800" data-id="{{ $anggota->id_anggota }}">Hapus Anggota</button>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500">Belum ada anggota tim</p>
                        @endforelse
                    </div>
                    <button type="button" id="btn-add-anggota" class="mt-3 px-4 py-2 rounded-full text-sm font-semibold text-red-700 border border-red-700 bg-red-50 hover:bg-red-100 transition">
                        + Tambah Anggota
                    </button>
                </div>
            </div>

            <!-- Dosen Pembimbing -->
            <div class="flex flex-row items-start w-full mb-6 gap-2">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700 pt-3">Dosen Pembimbing</label>
                <div class="flex-1 min-w-0 w-full">
                    <div class="space-y-3" id="dosen-container">
                        @forelse($prestasi->dosen as $idx => $dosen)
                        <div class="dosen-item p-4 rounded-xl border border-gray-300 bg-white">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-2">
                                <input type="text" placeholder="Nama Dosen" name="dosen_nama[]" value="{{ $dosen->nama_dosen }}"
                                    class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                                <input type="text" placeholder="NIDN" name="dosen_nidn[]" value="{{ $dosen->nidn }}" maxlength="10"
                                    class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                                <input type="text" placeholder="NIP" name="dosen_nip[]" value="{{ $dosen->nip }}" maxlength="18"
                                    class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                                <input type="text" placeholder="Prodi" name="dosen_prodi[]" value="{{ $dosen->prodi }}"
                                    class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                            </div>
                            <button type="button" class="btn-remove-dosen text-xs text-red-600 font-semibold hover:text-red-800" data-id="{{ $dosen->id_dosen }}">Hapus Dosen</button>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500">Belum ada dosen pembimbing</p>
                        @endforelse
                    </div>
                    <button type="button" id="btn-add-dosen" class="mt-3 px-4 py-2 rounded-full text-sm font-semibold text-red-700 border border-red-700 bg-red-50 hover:bg-red-100 transition">
                        + Tambah Dosen Pembimbing
                    </button>
                </div>
            </div>

            <!-- Dokumen & Evidence -->
            <div class="flex flex-row items-start w-full mb-6 gap-2">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700 pt-3">Dokumen & Evidence</label>
                <div class="flex-1 min-w-0 w-full">
                    <div class="space-y-3" id="dokumen-container">
                        @forelse($prestasi->dokumen as $doc)
                        <div class="dokumen-item p-4 rounded-xl border border-gray-300 bg-white">
                            <input type="hidden" name="dokumen_id[]" value="{{ $doc->id_dokumen }}">
                            <div class="mb-2">
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Jenis Dokumen</label>
                                <input type="text" placeholder="Misal: Sertifikat, Foto, dll" name="dokumen_jenis[{{ $doc->id_dokumen }}]" value="{{ $doc->jenis_dokumen }}"
                                    class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                            </div>
                            <div class="mb-2">
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">File Saat Ini: <a href="{{ asset('storage/' . $doc->file) }}" target="_blank" class="text-blue-600 hover:underline">{{ basename($doc->file) }}</a></label>
                                <label class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-white p-2 hover:bg-gray-50 transition">
                                    <input type="file" name="dokumen_file[{{ $doc->id_dokumen }}]" class="hidden" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                                    <div class="flex items-center justify-center bg-[#fcfcfc] min-h-[50px] gap-2 text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                                        </svg>
                                        <span class="text-xs text-gray-600">Klik untuk upload file baru (opsional)</span>
                                    </div>
                                </label>
                            </div>
                            <button type="button" class="btn-remove-dokumen text-xs text-red-600 font-semibold hover:text-red-800">Hapus Dokumen</button>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500">Belum ada dokumen</p>
                        @endforelse
                    </div>
                    <button type="button" id="btn-add-dokumen" class="mt-3 px-4 py-2 rounded-full text-sm font-semibold text-red-700 border border-red-700 bg-red-50 hover:bg-red-100 transition">
                        + Tambah Dokumen
                    </button>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-200 my-6"></div>

            <!-- Navigasi -->
            <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                <!-- Tombol Batal -->
                <a href="{{ route('prestasi.beranda') }}" class="inline-flex min-w-[140px] items-center justify-center rounded-full border border-gray-400 bg-white px-5 py-2 text-sm font-medium text-gray-700 transition-all duration-300 hover:bg-gray-100 hover:border-gray-500 hover:text-gray-900 hover:-translate-y-1 hover:shadow-md focus:ring-2 focus:ring-gray-200">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
                
                <!-- Tombol Kirim Revisi -->
                <button type="submit" class="inline-flex min-w-[140px] items-center justify-center rounded-full bg-red-700 px-5 py-2 text-sm font-medium text-white transition-all duration-300 hover:bg-red-800 hover:-translate-y-1 hover:shadow-lg hover:shadow-red-200 focus:ring-2 focus:ring-red-200">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Kirim Revisi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Tambah Anggota
    document.getElementById('btn-add-anggota').addEventListener('click', function(e) {
        e.preventDefault();
        const container = document.getElementById('anggota-container');
        const newItem = document.createElement('div');
        newItem.className = 'anggota-item p-4 rounded-xl border border-gray-300 bg-white';
        newItem.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-2">
                <input type="text" placeholder="NIM" name="anggota_nim[]" 
                    class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                <input type="text" placeholder="Nama Anggota" name="anggota_nama[]" 
                    class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                <input type="text" placeholder="Prodi" name="anggota_prodi[]" 
                    class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
            </div>
            <button type="button" class="btn-remove-anggota text-xs text-red-600 font-semibold hover:text-red-800">Hapus Anggota</button>
        `;
        container.appendChild(newItem);
        attachRemoveListener(newItem.querySelector('.btn-remove-anggota'));
    });

    // Tambah Dosen
    document.getElementById('btn-add-dosen').addEventListener('click', function(e) {
        e.preventDefault();
        const container = document.getElementById('dosen-container');
        const newItem = document.createElement('div');
        newItem.className = 'dosen-item p-4 rounded-xl border border-gray-300 bg-white';
        newItem.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-2">
                <input type="text" placeholder="Nama Dosen" name="dosen_nama[]" 
                    class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                <input type="text" placeholder="NIDN" name="dosen_nidn[]" maxlength="10"
                    class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                <input type="text" placeholder="NIP" name="dosen_nip[]" maxlength="18"
                    class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                <input type="text" placeholder="Prodi" name="dosen_prodi[]" 
                    class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
            </div>
            <button type="button" class="btn-remove-dosen text-xs text-red-600 font-semibold hover:text-red-800">Hapus Dosen</button>
        `;
        container.appendChild(newItem);
        attachRemoveListener(newItem.querySelector('.btn-remove-dosen'));
    });

    // Tambah Dokumen
    document.getElementById('btn-add-dokumen').addEventListener('click', function(e) {
        e.preventDefault();
        const container = document.getElementById('dokumen-container');
        const newItem = document.createElement('div');
        newItem.className = 'dokumen-item p-4 rounded-xl border border-gray-300 bg-white';
        newItem.innerHTML = `
            <div class="mb-2">
                <label class="text-xs font-semibold text-gray-600 mb-1 block">Jenis Dokumen</label>
                <input type="text" placeholder="Misal: Sertifikat, Foto, dll" name="dokumen_jenis_new[]" 
                    class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
            </div>
            <div class="mb-2">
                <label class="text-xs font-semibold text-gray-600 mb-1 block">Upload File (required)</label>
                <label class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-white p-2 hover:bg-gray-50 transition">
                    <input type="file" name="dokumen_file_new[]" class="hidden" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" required>
                    <div class="flex items-center justify-center bg-[#fcfcfc] min-h-[50px] gap-2 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                        </svg>
                        <span class="text-xs text-gray-600">Klik untuk upload file</span>
                    </div>
                </label>
            </div>
            <button type="button" class="btn-remove-dokumen text-xs text-red-600 font-semibold hover:text-red-800">Hapus Dokumen</button>
        `;
        container.appendChild(newItem);
        attachRemoveListener(newItem.querySelector('.btn-remove-dokumen'));
    });

    function attachRemoveListener(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            this.closest('.anggota-item, .dosen-item, .dokumen-item').remove();
        });
    }

    // Attach listeners to existing remove buttons
    document.querySelectorAll('.btn-remove-anggota, .btn-remove-dosen, .btn-remove-dokumen').forEach(btn => {
        attachRemoveListener(btn);
    });
});
</script>
@endpush
