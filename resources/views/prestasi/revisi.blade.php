@extends('layouts.app')

@section('title', 'Revisi Prestasi')

@section('content')
<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-white bg-opacity-95 z-50 flex flex-col items-center justify-center transition-all duration-300">
    <div class="flex flex-col items-center">
        <svg class="animate-spin h-12 w-12 text-red-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-sm font-semibold text-gray-700">Memuat Data Prestasi...</span>
    </div>
</div>

<div class="laporan-prestasi-page min-h-screen bg-gray-100 flex justify-center items-start px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold leading-tight text-gray-800 sm:text-3xl">Revisi Data Prestasi</h1>
            <p class="text-xs text-gray-500 mt-1">Perbaiki data prestasi sesuai dengan catatan revisi dari admin</p>
        </div>

        <!-- Admin Catatan Alert -->
        <div id="admin-catatan-alert" class="hidden mb-6 p-4 rounded-lg border-l-4 border-red-500 bg-red-50">
            <p class="text-xs font-bold text-red-700 mb-2">📌 Catatan Revisi dari Admin:</p>
            <p id="admin-catatan-text" class="text-sm text-red-800 leading-relaxed"></p>
        </div>

        <!-- Alert messages container -->
        <div id="alert-container" class="hidden mb-6"></div>

        <!-- Form -->
        <form id="revisi-prestasi-form" class="mt-4 sm:mt-8">
            @csrf

            <!-- Nama Kompetisi -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="nama_kompetisi" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Nama Kompetisi</label>
                <div class="flex-1 min-w-0 w-full">
                    <input
                        id="nama_kompetisi"
                        type="text"
                        name="nama_kompetisi"
                        class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm"
                        required>
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
                        class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm"
                        required>
                </div>
            </div>

            <!-- Pelaksanaan -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Pelaksanaan</label>
                <div class="flex-1 min-w-0 w-full flex items-center gap-6 pl-2">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="pelaksanaan" value="Luring" checked
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
                        class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm"
                        required>
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
                        class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm"
                        required>
                </div>
            </div>

            <!-- Tingkat Kompetisi -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Tingkat Kompetisi</label>
                <div class="flex-1 min-w-0 w-full flex items-center gap-6 pl-2">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="tingkat" value="Internasional"
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Internasional</span>
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="tingkat" value="Nasional"
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Nasional</span>
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="tingkat" value="Regional"
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Regional</span>
                    </label>
                </div>
            </div>

            <!-- Klaster (Conditional visible by JS) -->
            <div id="klaster-container" class="flex flex-row items-center w-full mb-6 gap-2" style="display: none;">
                <label for="klaster" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Klaster</label>
                <div class="flex-1 min-w-0 w-full">
                    <select id="klaster" name="klaster"
                        class="w-full h-11 md:h-12 appearance-none rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm"
                        style="-webkit-appearance: none; -moz-appearance: none; appearance: none; padding-right: 3rem; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E&quot;); background-position: right 1.25rem center; background-repeat: no-repeat; background-size: 1rem;">
                        <option value="" disabled selected>---- Pilih Klaster ----</option>
                    </select>
                </div>
            </div>

            <!-- Jumlah Negara (Conditional visible by JS for Internasional) -->
            <div id="negara-container" class="flex flex-row items-center w-full mb-6 gap-2" style="display: none;">
                <label for="jumlah_negara" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Jumlah Negara</label>
                <div class="flex-1 min-w-0 w-full">
                    <select id="jumlah_negara" name="jumlah_negara"
                        class="w-full h-11 md:h-12 appearance-none rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm"
                        style="-webkit-appearance: none; -moz-appearance: none; appearance: none; padding-right: 3rem; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E&quot;); background-position: right 1.25rem center; background-repeat: no-repeat; background-size: 1rem;">
                        <option value="" disabled selected>--- Pilih Jumlah Negara ---</option>
                        <option value="<11 negara">Jumlah negara &lt;11 negara</option>
                        <option value="11-20 negara">Jumlah negara 11-20 negara</option>
                        <option value=">20 negara">Jumlah negara &gt;20 negara</option>
                    </select>
                </div>
            </div>

            <!-- Prestasi Dicapai -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="capaian" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Prestasi Dicapai</label>
                <div class="flex-1 min-w-0 w-full">
                    <select
                        id="capaian"
                        name="capaian"
                        class="w-full h-11 md:h-12 appearance-none rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm"
                        style="-webkit-appearance: none; -moz-appearance: none; appearance: none; padding-right: 3rem; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E&quot;); background-position: right 1.25rem center; background-repeat: no-repeat; background-size: 1rem;"
                        required>
                        <option value="" disabled selected>---- Pilih Juara yang di Raih ----</option>
                        <option value="Juara 1">Juara 1</option>
                        <option value="Juara 2">Juara 2</option>
                        <option value="Juara 3">Juara 3</option>
                        <option value="Juara Harapan">Juara Harapan</option>
                        <option value="Finalis">Finalis</option>
                        <option value="Lolos Pendanaan">Lolos Pendanaan</option>
                        <option value="Penerima Hibah">Penerima Hibah</option>
                        <option value="Medali Emas">Medali Emas</option>
                        <option value="Medali Perak">Medali Perak</option>
                        <option value="Medali Perunggu">Medali Perunggu</option>
                        <option value="Most Inspiration/Kategori Lainnya">Most Inspiration/Kategori Lainnya</option>
                    </select>
                </div>
            </div>

            <!-- Kategori -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Kategori</label>
                <div class="flex-1 min-w-0 w-full flex items-center gap-6 pl-2">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="kategori" value="Individu"
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Individu</span>
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="kategori" value="Kelompok"
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Kelompok</span>
                    </label>
                </div>
            </div>

            <!-- Mewakili Ormawa -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label class="text-sm font-medium text-gray-700" style="width: 160px; min-width: 160px;">Mewakili Ormawa</label>
                <div class="flex-1 min-w-0 w-full flex items-center gap-6 pl-2">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="mewakili_ormawa" value="ya"
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Ya</span>
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="mewakili_ormawa" value="tidak" checked
                            class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                        <span>Tidak</span>
                    </label>
                </div>
            </div>

            <!-- Anggota Tim Section (Shown dynamically) -->
            <div class="flex-row items-start w-full mb-6 gap-2" id="anggota-section-wrapper" style="display: none;">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700 pt-3">Anggota Tim</label>
                <div class="flex-1 min-w-0 w-full">
                    <div class="space-y-3 mb-2" id="anggota-container">
                        <!-- populated by JS -->
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
                    <div class="space-y-3 mb-2" id="dosen-container">
                        <!-- populated by JS -->
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
                    <!-- Section for Uploading New Document -->
                    <div class="mb-4 p-4 rounded-xl border border-dashed border-gray-300 bg-[#fbfbfb] flex flex-col md:flex-row items-center gap-3">
                        <div class="w-full md:w-1/3">
                            <select id="new-dokumen-jenis" class="w-full h-10 rounded-full border border-gray-400 bg-white px-4 text-xs font-semibold text-gray-700 outline-none cursor-pointer">
                                <option value="" disabled selected>-- Pilih Jenis Dokumen --</option>
                                <option value="Surat Tugas Dosen">Surat Tugas Dosen</option>
                                <option value="Surat Tugas Mahasiswa">Surat Tugas Mahasiswa</option>
                                <option value="Sertifikat Juara">Sertifikat Juara</option>
                                <option value="Penyerahan Penghargaan">Penyerahan Penghargaan</option>
                                <option value="Bukti Keikutsertaan">Bukti Keikutsertaan</option>
                                <option value="URL / Link Informasi Kegiatan">URL / Link Informasi Kegiatan</option>
                                <option value="Foto Formal/Non Formal">Foto Formal/Non Formal</option>
                            </select>
                        </div>
                        <div class="flex-1 w-full flex items-center gap-2">
                            <input type="file" id="new-dokumen-file" class="hidden" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                            <label for="new-dokumen-file" class="flex-1 h-10 rounded-full border border-gray-400 bg-white hover:bg-gray-50 flex items-center justify-between px-4 text-xs text-gray-600 font-semibold cursor-pointer select-none">
                                <span id="new-dokumen-file-label">Pilih File...</span>
                                <svg class="h-4.5 w-4.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </label>
                            <button type="button" id="btn-upload-dokumen" class="h-10 px-5 rounded-full text-xs font-bold bg-red-700 text-white hover:bg-red-800 disabled:bg-gray-400 transition shadow-sm shrink-0">
                                Unggah
                            </button>
                        </div>
                    </div>

                    <div class="space-y-3" id="dokumen-container">
                        <!-- populated by JS -->
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-200 my-6"></div>

            <!-- Navigasi -->
            <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                <!-- Tombol Batal -->
                <a href="{{ route('prestasi.index') }}" class="inline-flex min-w-[140px] items-center justify-center rounded-full border border-gray-400 bg-white px-5 py-2 text-sm font-medium text-gray-700 transition-all duration-300 hover:bg-gray-100 hover:border-gray-500 hover:text-gray-900 hover:-translate-y-0.5 hover:shadow-md focus:ring-2 focus:ring-gray-200">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
                
                <!-- Tombol Kirim Revisi -->
                <button type="submit" id="btn-submit-revisi" class="inline-flex min-w-[140px] items-center justify-center rounded-full bg-red-700 px-5 py-2.5 text-sm font-medium text-white transition-all duration-300 hover:bg-red-800 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-200 focus:ring-2 focus:ring-red-200">
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
// Expose the Prestasi ID from Blade template
const PRESTASI_ID = {{ $id }};

document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const loadingOverlay = document.getElementById('loading-overlay');
    const form = document.getElementById('revisi-prestasi-form');
    const alertContainer = document.getElementById('alert-container');
    const adminAlert = document.getElementById('admin-catatan-alert');
    const adminText = document.getElementById('admin-catatan-text');
    const btnSubmit = document.getElementById('btn-submit-revisi');

    // Trackers for deletions
    const deletedAnggotaIds = [];
    const deletedDosenIds = [];

    // State
    let originalKategori = 'Individu';

    // Elements
    const klasterSelect = document.getElementById('klaster');
    const klasterContainer = document.getElementById('klaster-container');
    const negaraContainer = document.getElementById('negara-container');

    // Options for Klaster
    const optionsNasional = [
        { value: 'Klaster I Minimal 4 Provinsi', text: 'Klaster I Minimal 4 Provinsi' },
        { value: 'Klaster II minimal 2 provinsi', text: 'Klaster II minimal 2 provinsi' },
        { value: 'Klaster III minimal 4 PT di 1 provinsi', text: 'Klaster III minimal 4 PT di 1 provinsi' }
    ];

    const optionsRegional = [
        { value: 'Klaster I < 31 PT', text: 'Klaster I < 31 PT' },
        { value: 'Klaster II 31-50 PT', text: 'Klaster II 31-50 PT' },
        { value: 'Klaster III > 50 PT', text: 'Klaster III > 50 PT' }
    ];

    const optionsInternasional = [
        { value: 'Klaster I minimal 3 negara regional (ASEAN, Asia Pasifik...)', text: 'Klaster I minimal 3 negara regional (ASEAN, Asia Pasifik...)' },
        { value: 'Klaster II minimal 2 negara', text: 'Klaster II minimal 2 negara' },
        { value: 'Klaster III tidak keduanya', text: 'Klaster III tidak keduanya' }
    ];

    function updateTingkatVisibility() {
        const checkedVal = document.querySelector('input[name="tingkat"]:checked')?.value;
        const currentKlaster = klasterSelect.value;
        klasterSelect.innerHTML = '<option value="" disabled selected>---- Pilih Klaster ----</option>';

        if (checkedVal === 'Internasional') {
            klasterContainer.style.display = 'flex';
            negaraContainer.style.display = 'flex';
            optionsInternasional.forEach(opt => {
                const el = document.createElement('option');
                el.value = opt.value;
                el.textContent = opt.text;
                klasterSelect.appendChild(el);
            });
        } else if (checkedVal === 'Nasional') {
            klasterContainer.style.display = 'flex';
            negaraContainer.style.display = 'none';
            optionsNasional.forEach(opt => {
                const el = document.createElement('option');
                el.value = opt.value;
                el.textContent = opt.text;
                klasterSelect.appendChild(el);
            });
        } else if (checkedVal === 'Regional') {
            klasterContainer.style.display = 'flex';
            negaraContainer.style.display = 'none';
            optionsRegional.forEach(opt => {
                const el = document.createElement('option');
                el.value = opt.value;
                el.textContent = opt.text;
                klasterSelect.appendChild(el);
            });
        } else {
            klasterContainer.style.display = 'none';
            negaraContainer.style.display = 'none';
        }

        if (currentKlaster) {
            klasterSelect.value = currentKlaster;
        }
    }

    document.querySelectorAll('input[name="tingkat"]').forEach(radio => {
        radio.addEventListener('change', updateTingkatVisibility);
    });

    function showAlert(message, type = 'error') {
        alertContainer.classList.remove('hidden');
        alertContainer.className = `mb-6 rounded-xl border px-4 py-3 text-sm shadow-sm ${
            type === 'success' ? 'border-green-200 bg-green-50 text-green-800' : 'border-red-200 bg-red-50 text-red-800'
        }`;
        alertContainer.textContent = message;
        alertContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function hideAlert() {
        alertContainer.classList.add('hidden');
    }

    // Toggle Anggota Section based on selected Kategori
    const toggleAnggotaSection = () => {
        const isKelompok = document.querySelector('input[name="kategori"]:checked')?.value === 'Kelompok';
        const wrapper = document.getElementById('anggota-section-wrapper');
        if (isKelompok) {
            wrapper.style.display = 'flex';
        } else {
            wrapper.style.display = 'none';
        }
    };

    document.querySelectorAll('input[name="kategori"]').forEach(radio => {
        radio.addEventListener('change', toggleAnggotaSection);
    });

    // Handle Anggota Dynamic Items
    function checkAnggotaPlaceholder() {
        const container = document.getElementById('anggota-container');
        const placeholder = document.getElementById('anggota-placeholder');
        const items = container.querySelectorAll('.anggota-item');
        if (items.length === 0) {
            if (!placeholder) {
                const p = document.createElement('p');
                p.id = 'anggota-placeholder';
                p.className = 'text-sm text-gray-500 pl-2';
                p.textContent = 'Belum ada anggota tim tambahan';
                container.appendChild(p);
            }
        } else {
            if (placeholder) placeholder.remove();
        }
    }

    function createAnggotaBlock(nim = '', nama = '', prodi = '', idAnggota = null) {
        const container = document.getElementById('anggota-container');
        const div = document.createElement('div');
        div.className = 'anggota-item p-4 rounded-xl border border-gray-300 bg-white relative';
        if (idAnggota) {
            div.setAttribute('data-id', idAnggota);
        }

        div.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-2">
                <div>
                    <label class="text-xs font-semibold text-gray-500 block mb-1">NIM</label>
                    <input type="text" placeholder="NIM" name="anggota_nim[]" value="${nim}" required
                        class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 block mb-1">Nama Anggota</label>
                    <input type="text" placeholder="Nama Anggota" name="anggota_nama[]" value="${nama}" required
                        class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 block mb-1">Prodi</label>
                    <input type="text" placeholder="Prodi" name="anggota_prodi[]" value="${prodi}" required
                        class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                </div>
            </div>
            <button type="button" class="btn-remove-anggota text-xs text-red-600 font-semibold hover:text-red-800">
                Hapus Anggota
            </button>
        `;

        container.appendChild(div);
        checkAnggotaPlaceholder();

        div.querySelector('.btn-remove-anggota').addEventListener('click', function() {
            if (idAnggota) {
                deletedAnggotaIds.push(idAnggota);
            }
            div.remove();
            checkAnggotaPlaceholder();
        });
    }

    document.getElementById('btn-add-anggota').addEventListener('click', function(e) {
        e.preventDefault();
        createAnggotaBlock('', '', '');
    });

    // Handle Dosen Dynamic Items
    function checkDosenPlaceholder() {
        const container = document.getElementById('dosen-container');
        const placeholder = document.getElementById('dosen-placeholder');
        const items = container.querySelectorAll('.dosen-item');
        if (items.length === 0) {
            if (!placeholder) {
                const p = document.createElement('p');
                p.id = 'dosen-placeholder';
                p.className = 'text-sm text-gray-500 pl-2';
                p.textContent = 'Belum ada dosen pembimbing';
                container.appendChild(p);
            }
        } else {
            if (placeholder) placeholder.remove();
        }
    }

    function createDosenBlock(nama = '', nidn = '', nip = '', prodi = '', idDosen = null) {
        const container = document.getElementById('dosen-container');
        const div = document.createElement('div');
        div.className = 'dosen-item p-4 rounded-xl border border-gray-300 bg-white';
        if (idDosen) {
            div.setAttribute('data-id', idDosen);
        }

        div.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-2">
                <div>
                    <label class="text-xs font-semibold text-gray-500 block mb-1">Nama Dosen</label>
                    <input type="text" placeholder="Nama Dosen" name="dosen_nama[]" value="${nama}" required
                        class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 block mb-1">NIDN</label>
                    <input type="text" placeholder="NIDN" name="dosen_nidn[]" value="${nidn}" maxlength="10"
                        class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 block mb-1">NIP</label>
                    <input type="text" placeholder="NIP" name="dosen_nip[]" value="${nip}" maxlength="18"
                        class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 block mb-1">Prodi</label>
                    <input type="text" placeholder="Prodi" name="dosen_prodi[]" value="${prodi}" required
                        class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                </div>
            </div>
            <button type="button" class="btn-remove-dosen text-xs text-red-600 font-semibold hover:text-red-800">
                Hapus Dosen
            </button>
        `;

        container.appendChild(div);
        checkDosenPlaceholder();

        div.querySelector('.btn-remove-dosen').addEventListener('click', function() {
            if (idDosen) {
                deletedDosenIds.push(idDosen);
            }
            div.remove();
            checkDosenPlaceholder();
        });
    }

    document.getElementById('btn-add-dosen').addEventListener('click', function(e) {
        e.preventDefault();
        createDosenBlock('', '', '', '');
    });

    // Render Dokumen List Item
    function addDokumenItem(jenis, fileUrl, idDokumen) {
        const container = document.getElementById('dokumen-container');
        const div = document.createElement('div');
        div.className = 'p-4 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-between shadow-sm';
        
        const fileBase = fileUrl ? fileUrl.substring(fileUrl.lastIndexOf('/') + 1) : 'berkas_evidence';

        div.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center text-red-600 shrink-0">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-red-600 uppercase tracking-wider block">${jenis}</span>
                    <a href="${fileUrl}" target="_blank" class="text-sm font-semibold text-gray-700 hover:underline hover:text-red-700 break-all">${fileBase}</a>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="${fileUrl}" target="_blank" class="px-4 py-1.5 rounded-full text-xs font-bold bg-white text-gray-700 hover:bg-gray-100 border border-gray-200 transition shadow-sm">
                    Lihat
                </a>
                <button type="button" class="btn-delete-dokumen p-2 rounded-full text-red-600 hover:text-red-800 hover:bg-red-50 border border-transparent hover:border-red-200 transition shrink-0" data-id="${idDokumen}">
                    <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        `;
        container.appendChild(div);

        // Bind delete action
        const btnDelete = div.querySelector('.btn-delete-dokumen');
        btnDelete.addEventListener('click', async function() {
            if (!confirm(`Apakah Anda yakin ingin menghapus berkas "${jenis}" ini?`)) {
                return;
            }
            btnDelete.disabled = true;
            btnDelete.innerHTML = `
                <svg class="animate-spin h-4.5 w-4.5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;
            try {
                await window.axios.delete(`prestasi/${PRESTASI_ID}/dokumen/${idDokumen}`);
                div.remove();
                if (container.querySelectorAll('.p-4').length === 0) {
                    container.innerHTML = '<p class="text-sm text-gray-500 italic pl-2">Tidak ada dokumen bukti yang terunggah.</p>';
                }
            } catch (err) {
                console.error(err);
                alert('Gagal menghapus dokumen.');
                loadDocumentsOnly();
            }
        });
    }

    async function loadDocumentsOnly() {
        const dokumenContainer = document.getElementById('dokumen-container');
        dokumenContainer.innerHTML = `
            <div class="flex items-center gap-2 py-4 justify-center">
                <svg class="animate-spin h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs font-semibold text-gray-500">Memperbarui berkas...</span>
            </div>
        `;

        try {
            const res = await window.axios.get(`prestasi/${PRESTASI_ID}`);
            const data = res.data?.data;
            dokumenContainer.innerHTML = '';

            if (data && data.dokumen && data.dokumen.length > 0) {
                data.dokumen.forEach(doc => {
                    let fileUrl = doc.file;
                    if (fileUrl && !fileUrl.startsWith('http')) {
                        fileUrl = `/storage/${fileUrl.replace(/^\/?storage\//, '')}`;
                    }
                    addDokumenItem(doc.jenis_dokumen || 'Evidence', fileUrl, doc.id_dokumen);
                });
            } else {
                dokumenContainer.innerHTML = '<p class="text-sm text-gray-500 italic pl-2">Tidak ada dokumen bukti yang terunggah.</p>';
            }
        } catch (error) {
            console.error('Failed to reload documents:', error);
            dokumenContainer.innerHTML = '<p class="text-sm text-red-600 italic pl-2">Gagal memuat ulang berkas.</p>';
        }
    }

    // ================= UPLOAD NEW DOCUMENT ACTIONS =================
    const newDocFileInput = document.getElementById('new-dokumen-file');
    const newDocFileLabel = document.getElementById('new-dokumen-file-label');
    newDocFileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            newDocFileLabel.textContent = this.files[0].name;
            newDocFileLabel.classList.add('text-red-700');
        } else {
            newDocFileLabel.textContent = 'Pilih File...';
            newDocFileLabel.classList.remove('text-red-700');
        }
    });

    const btnUploadDoc = document.getElementById('btn-upload-dokumen');
    btnUploadDoc.addEventListener('click', async function() {
        const jenisSelect = document.getElementById('new-dokumen-jenis');
        const fileInput = document.getElementById('new-dokumen-file');
        
        const jenis = jenisSelect.value;
        const file = fileInput.files[0];

        if (!jenis) {
            alert('Harap pilih jenis dokumen terlebih dahulu.');
            return;
        }
        if (!file) {
            alert('Harap pilih file dokumen yang akan diunggah.');
            return;
        }

        btnUploadDoc.disabled = true;
        btnUploadDoc.innerHTML = `
            <svg class="animate-spin h-4 w-4 text-white inline mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Mengunggah...
        `;

        const formData = new FormData();
        formData.append('jenis_dokumen', jenis);
        formData.append('file', file);

        try {
            await window.axios.post(`prestasi/${PRESTASI_ID}/dokumen`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });

            // Reset form
            jenisSelect.value = '';
            fileInput.value = '';
            newDocFileLabel.textContent = 'Pilih File...';
            newDocFileLabel.classList.remove('text-red-700');

            // Reload documents
            await loadDocumentsOnly();
        } catch (error) {
            console.error('Upload failed:', error);
            alert(error.response?.data?.message || 'Gagal mengunggah dokumen.');
        } finally {
            btnUploadDoc.disabled = false;
            btnUploadDoc.innerHTML = 'Unggah';
        }
    });

    // ================= LOAD DATA FROM API =================
    async function loadPrestasiDetails() {
        try {
            const res = await window.axios.get(`prestasi/${PRESTASI_ID}`);
            const data = res.data?.data;

            if (!data) {
                throw new Error('Data prestasi tidak ditemukan.');
            }

            // Verify status: only Menunggu or Revisi allowed
            if (data.status_verifikasi !== 'Menunggu' && data.status_verifikasi !== 'Revisi') {
                alert('Anda hanya dapat merevisi prestasi dengan status "Menunggu" atau "Revisi".');
                window.location.href = '/prestasi';
                return;
            }

            // Admin Catatan Alert
            if (data.catatan_admin) {
                adminText.textContent = data.catatan_admin;
                adminAlert.classList.remove('hidden');
            }

            // Populate text inputs
            document.getElementById('nama_kompetisi').value = data.nama_kompetisi || '';
            document.getElementById('penyelenggara').value = data.penyelenggara || '';
            document.getElementById('capaian').value = data.capaian || '';

            // Set radio values
            const tingkatRadio = document.querySelector(`input[name="tingkat"][value="${data.tingkat}"]`);
            if (tingkatRadio) tingkatRadio.checked = true;

            const kategoriRadio = document.querySelector(`input[name="kategori"][value="${data.kategori}"]`);
            if (kategoriRadio) kategoriRadio.checked = true;

            const mewakiliRadio = document.querySelector(`input[name="mewakili_ormawa"][value="${data.mewakili_ormawa}"]`);
            if (mewakiliRadio) mewakiliRadio.checked = true;

            const pelaksanaanRadio = document.querySelector(`input[name="pelaksanaan"][value="${data.pelaksanaan}"]`);
            if (pelaksanaanRadio) pelaksanaanRadio.checked = true;

            document.getElementById('waktu_kompetisi').value = data.waktu_kompetisi || '';
            document.getElementById('tanggal_pengumuman').value = data.tanggal_pengumuman || '';

            // Setup Klaster & Negara dropdowns with current values
            updateTingkatVisibility();

            if (data.klaster) {
                klasterSelect.value = data.klaster;
            }

            if (data.jumlah_negara) {
                let negaraRaw = null;
                if (data.jumlah_negara === 10) negaraRaw = '<11 negara';
                else if (data.jumlah_negara === 15) negaraRaw = '11-20 negara';
                else if (data.jumlah_negara === 25) negaraRaw = '>20 negara';
                
                if (negaraRaw) {
                    document.getElementById('jumlah_negara').value = negaraRaw;
                }
            }

            originalKategori = data.kategori || 'Individu';

            // Render Anggota
            const anggotaContainer = document.getElementById('anggota-container');
            anggotaContainer.innerHTML = '';
            if (data.anggota && data.anggota.length > 0) {
                data.anggota.forEach(ang => {
                    createAnggotaBlock(ang.nim, ang.nama, ang.prodi, ang.id_anggota);
                });
            } else {
                checkAnggotaPlaceholder();
            }

            // Render Dosen
            const dosenContainer = document.getElementById('dosen-container');
            dosenContainer.innerHTML = '';
            if (data.dosen && data.dosen.length > 0) {
                data.dosen.forEach(dos => {
                    createDosenBlock(dos.nama_dosen || dos.nama || '', dos.nidn || '', dos.nip || '', dos.prodi || '', dos.id_dosen);
                });
            } else {
                checkDosenPlaceholder();
            }

            // Render Documents
            const dokumenContainer = document.getElementById('dokumen-container');
            dokumenContainer.innerHTML = '';
            if (data.dokumen && data.dokumen.length > 0) {
                data.dokumen.forEach(doc => {
                    let fileUrl = doc.file;
                    if (fileUrl && !fileUrl.startsWith('http')) {
                        fileUrl = `/storage/${fileUrl.replace(/^\/?storage\//, '')}`;
                    }
                    addDokumenItem(doc.jenis_dokumen || 'Evidence', fileUrl, doc.id_dokumen);
                });
            } else {
                dokumenContainer.innerHTML = '<p class="text-sm text-gray-500 italic pl-2">Tidak ada dokumen bukti yang terunggah.</p>';
            }

            // Trigger dynamic sections toggling
            toggleAnggotaSection();

            // Hide overlay
            loadingOverlay.classList.add('opacity-0');
            setTimeout(() => {
                loadingOverlay.style.display = 'none';
            }, 300);

        } catch (error) {
            console.error('Failed to load data:', error);
            alert(error.response?.data?.message || error.message || 'Gagal memuat data prestasi. Mengalihkan...');
            window.location.href = '/prestasi';
        }
    }

    // ================= SUBMIT REVISION =================
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        hideAlert();

        btnSubmit.disabled = true;
        btnSubmit.innerHTML = `
            Memproses...
            <svg class="animate-spin ml-2 h-4 w-4 text-white inline-block" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        `;

        const newKategori = document.querySelector('input[name="kategori"]:checked')?.value || 'Individu';

        // Extract Klaster & Jumlah Negara values
        const klasterVal = document.getElementById('klaster').value;
        const negaraRaw = document.getElementById('jumlah_negara').value;
        let negaraVal = null;
        if (negaraRaw === '<11 negara') negaraVal = 10;
        else if (negaraRaw === '11-20 negara') negaraVal = 15;
        else if (negaraRaw === '>20 negara') negaraVal = 25;

        try {
            // 1. Update basic fields using PUT /api/v1/prestasi/{id}
            await window.axios.put(`prestasi/${PRESTASI_ID}`, {
                nama_kompetisi: document.getElementById('nama_kompetisi').value,
                penyelenggara: document.getElementById('penyelenggara').value,
                tingkat: document.querySelector('input[name="tingkat"]:checked')?.value || 'Regional',
                capaian: document.getElementById('capaian').value,
                kategori: newKategori,
                mewakili_ormawa: document.querySelector('input[name="mewakili_ormawa"]:checked')?.value || 'tidak',
                pelaksanaan: document.querySelector('input[name="pelaksanaan"]:checked')?.value || 'Luring',
                waktu_kompetisi: document.getElementById('waktu_kompetisi').value || null,
                tanggal_pengumuman: document.getElementById('tanggal_pengumuman').value || null,
                klaster: klasterVal || null,
                jumlah_negara: negaraVal || null
            });

            const promises = [];

            // If category switched from Kelompok to Individu, delete ALL existing group members
            if (newKategori === 'Individu') {
                const existingItems = document.getElementById('anggota-container').querySelectorAll('.anggota-item');
                existingItems.forEach(item => {
                    const id = item.getAttribute('data-id');
                    if (id) {
                        promises.push(window.axios.delete(`prestasi/${PRESTASI_ID}/anggota/${id}`));
                    }
                });
            } else {
                // Perform Anggota deletions
                deletedAnggotaIds.forEach(id => {
                    promises.push(window.axios.delete(`prestasi/${PRESTASI_ID}/anggota/${id}`));
                });

                // Perform Anggota additions
                const newAnggotaItems = document.getElementById('anggota-container').querySelectorAll('.anggota-item:not([data-id])');
                newAnggotaItems.forEach(item => {
                    const nim = item.querySelector('input[name="anggota_nim[]"]').value;
                    const nama = item.querySelector('input[name="anggota_nama[]"]').value;
                    const prodi = item.querySelector('input[name="anggota_prodi[]"]').value;

                    if (nim && nama && prodi) {
                        promises.push(window.axios.post(`prestasi/${PRESTASI_ID}/anggota`, { nim, nama, prodi }));
                    }
                });
            }

            // Perform Dosen deletions
            deletedDosenIds.forEach(id => {
                promises.push(window.axios.delete(`prestasi/${PRESTASI_ID}/dosen/${id}`));
            });

            // Perform Dosen additions
            const newDosenItems = document.getElementById('dosen-container').querySelectorAll('.dosen-item:not([data-id])');
            newDosenItems.forEach(item => {
                const nama = item.querySelector('input[name="dosen_nama[]"]').value;
                const nidn = item.querySelector('input[name="dosen_nidn[]"]').value;
                const nip = item.querySelector('input[name="dosen_nip[]"]').value;
                const prodi = item.querySelector('input[name="dosen_prodi[]"]').value;

                if (nama && prodi) {
                    promises.push(window.axios.post(`prestasi/${PRESTASI_ID}/dosen`, {
                        nama: nama,
                        nidn: nidn || null,
                        nip: nip || null,
                        prodi: prodi
                    }));
                }
            });

            // Await all sub-requests
            if (promises.length > 0) {
                await Promise.all(promises);
            }

            showAlert('Selamat! Data revisi prestasi Anda berhasil disimpan.', 'success');

            // Redirect back to prestasi list
            setTimeout(function () {
                window.location.href = '/prestasi';
            }, 1500);

        } catch (error) {
            console.error('Revision failed:', error);
            showAlert(error.response?.data?.message || error.message || 'Gagal mengirim revisi prestasi. Silakan cek form input Anda.');
            
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = `
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Kirim Revisi
            `;
        }
    });

    // Start loading data
    loadPrestasiDetails();
});
</script>
@endpush
