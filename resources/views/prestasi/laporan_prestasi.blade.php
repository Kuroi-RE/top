@extends('layouts.app')

@section('title', 'Lapor Prestasi Baru')

@section('content')
<div class="laporan-prestasi-page min-h-screen bg-gray-100 flex justify-center items-start px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

        <!-- Progress Steps Indicator -->
        <div class="mb-10 border-b border-gray-100 pb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Formulir Lapor Prestasi Baru</h1>
            <div class="flex items-center justify-between max-w-3xl mx-auto relative px-4">
                <!-- Line background -->
                <div class="absolute left-10 right-10 top-1/2 -translate-y-1/2 h-[3px] bg-gray-200 z-0" id="progress-line-bg"></div>
                <div class="absolute left-10 top-1/2 -translate-y-1/2 h-[3px] bg-red-600 z-0 transition-all duration-300" id="progress-line-fill" style="width: 0%;"></div>

                <!-- Step 1 -->
                <div class="step-indicator flex flex-col items-center z-10" data-step="1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300 bg-red-600 text-white shadow-md shadow-red-200">1</div>
                    <span class="text-xs font-semibold text-red-600 mt-2">Biodata</span>
                </div>
                <!-- Step 2 -->
                <div class="step-indicator flex flex-col items-center z-10" data-step="2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300 bg-gray-200 text-gray-500">2</div>
                    <span class="text-xs font-semibold text-gray-500 mt-2">Kompetisi</span>
                </div>
                <!-- Step 3 -->
                <div class="step-indicator flex flex-col items-center z-10" data-step="3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300 bg-gray-200 text-gray-500">3</div>
                    <span class="text-xs font-semibold text-gray-500 mt-2">Capaian & Tim</span>
                </div>
                <!-- Step 4 -->
                <div class="step-indicator flex flex-col items-center z-10" data-step="4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300 bg-gray-200 text-gray-500">4</div>
                    <span class="text-xs font-semibold text-gray-500 mt-2">Dosen</span>
                </div>
                <!-- Step 5 -->
                <div class="step-indicator flex flex-col items-center z-10" data-step="5">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300 bg-gray-200 text-gray-500">5</div>
                    <span class="text-xs font-semibold text-gray-500 mt-2">Evidence</span>
                </div>
            </div>
        </div>

        <!-- Alert messages container -->
        <div id="alert-container" class="hidden mb-6"></div>

        <form id="lapor-prestasi-wizard-form" class="mt-4 space-y-6" enctype="multipart/form-data">
            @csrf

            <!-- ================= STEP 1: BIODATA ================= -->
            <div class="wizard-step space-y-6" id="wizard-step-1">
                <div class="border-b border-gray-100 pb-2 mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Langkah 1: Biodata Mahasiswa</h2>
                    <p class="text-xs text-gray-400">Pastikan data pribadi Anda sudah sesuai.</p>
                </div>

                <!-- Email -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label for="email" class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Email</label>
                    <div class="flex-1 w-full md:max-w-2xl">
                        <input id="email" type="email" value="{{ auth()->user()->email }}" readonly
                            class="w-full h-11 md:h-12 rounded-full border border-gray-300 bg-gray-100 px-5 text-sm text-gray-500 outline-none cursor-not-allowed shadow-sm">
                    </div>
                </div>

                <!-- Nama -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label for="nama" class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Nama Lengkap</label>
                    <div class="flex-1 w-full md:max-w-2xl">
                        <input id="nama" type="text" value="{{ trim((auth()->user()->nama_depan ?? '') . ' ' . (auth()->user()->nama_belakang ?? '')) }}" readonly
                            class="w-full h-11 md:h-12 rounded-full border border-gray-300 bg-gray-100 px-5 text-sm text-gray-500 outline-none cursor-not-allowed shadow-sm">
                    </div>
                </div>

                <!-- NIM -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label for="nim" class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">NIM</label>
                    <div class="flex-1 w-full md:max-w-2xl">
                        <input id="nim" type="text" value="{{ auth()->user()->nim }}" readonly
                            class="w-full h-11 md:h-12 rounded-full border border-gray-300 bg-gray-100 px-5 text-sm text-gray-500 outline-none cursor-not-allowed shadow-sm">
                    </div>
                </div>

                <!-- Program Studi -->
                @php $userProdi = auth()->user()->prodi ?? ''; @endphp
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label for="program_studi" class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Program Studi</label>
                    <div class="flex-1 w-full md:max-w-2xl">
                        @if($userProdi)
                            <input id="program_studi" name="program_studi" type="text" value="{{ $userProdi }}" readonly
                                class="w-full h-11 md:h-12 rounded-full border border-gray-300 bg-gray-100 px-5 text-sm text-gray-500 outline-none cursor-not-allowed shadow-sm">
                        @else
                            <select id="program_studi" name="program_studi" required
                                class="w-full h-11 md:h-12 appearance-none rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm"
                                style="-webkit-appearance: none; -moz-appearance: none; appearance: none; padding-right: 3rem; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E&quot;); background-position: right 1.25rem center; background-repeat: no-repeat; background-size: 1rem;">
                                <option value="" disabled selected>---- Pilih Prodi ----</option>
                                <option value="D3 Teknik Telekomunikasi">D3 Teknik Telekomunikasi</option>
                                <option value="S1 Teknik Telekomunikasi">S1 Teknik Telekomunikasi</option>
                                <option value="S1 Teknik Elektro">S1 Teknik Elektro</option>
                                <option value="S1 Teknik Biomedis">S1 Teknik Biomedis</option>
                                <option value="S1 Teknologi Pangan">S1 Teknologi Pangan</option>
                                <option value="S1 Desain Produk">S1 Desain Produk</option>
                                <option value="S1 Teknik Logistik">S1 Teknik Logistik</option>
                                <option value="S1 Desain Komunikasi Visual">S1 Desain Komunikasi Visual</option>
                                <option value="S1 Teknik Informatika">S1 Teknik Informatika</option>
                                <option value="S1 Sains Data">S1 Sains Data</option>
                                <option value="S1 Rekayasa Perangkat Lunak">S1 Rekayasa Perangkat Lunak</option>
                                <option value="S1 Sistem Informasi">S1 Sistem Informasi</option>
                                <option value="S1 Teknik Industri">S1 Teknik Industri</option>
                                <option value="S1 Bisnis Digital">S1 Bisnis Digital</option>
                            </select>
                        @endif
                    </div>
                </div>

                <!-- Mewakili Ormawa -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Mewakili Ormawa</label>
                    <div class="flex-1 w-full md:max-w-2xl flex items-center gap-6 pl-2">
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
            </div>

            <!-- ================= STEP 2: DETAIL KOMPETISI ================= -->
            <div class="wizard-step hidden space-y-6" id="wizard-step-2">
                <div class="border-b border-gray-100 pb-2 mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Langkah 2: Detail Kompetisi</h2>
                    <p class="text-xs text-gray-400">Masukkan rincian kompetisi yang telah Anda ikuti.</p>
                </div>

                <!-- Nama Kompetisi -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label for="nama_kompetisi" class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Nama Kompetisi</label>
                    <div class="flex-1 w-full">
                        <input id="nama_kompetisi" type="text" name="nama_kompetisi" required
                            class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                    </div>
                </div>

                <!-- Penyelenggara -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label for="penyelenggara" class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Penyelenggara</label>
                    <div class="flex-1 w-full">
                        <input id="penyelenggara" type="text" name="penyelenggara" required
                            class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                    </div>
                </div>

                <!-- Pelaksanaan -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Pelaksanaan</label>
                    <div class="flex-1 w-full flex items-center gap-6 pl-2">
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
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label for="waktu_kompetisi" class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Waktu Kompetisi</label>
                    <div class="flex-1 w-full">
                        <input id="waktu_kompetisi" type="date" name="waktu_kompetisi" required
                            class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                    </div>
                </div>

                <!-- Tanggal Pengumuman -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label for="tanggal_pengumuman" class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Tanggal Pengumuman</label>
                    <div class="flex-1 w-full">
                        <input id="tanggal_pengumuman" type="date" name="tanggal_pengumuman" required
                            class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                    </div>
                </div>

                <!-- Tingkat Kompetisi -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Tingkat Kompetisi</label>
                    <div class="flex-1 w-full flex items-center gap-6 pl-2">
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
                            <input type="radio" name="tingkat_kompetisi" value="Regional" checked
                                class="h-[18px] w-[18px] border-gray-400 accent-[#B30000]">
                            <span>Regional</span>
                        </label>
                    </div>
                </div>

                <!-- Klaster (Conditional visible by JS) -->
                <div id="klaster-container" class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2" style="display: none;">
                    <label for="klaster" class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Klaster</label>
                    <div class="flex-1 w-full">
                        <select id="klaster" name="klaster"
                            class="w-full h-11 md:h-12 appearance-none rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm"
                            style="-webkit-appearance: none; -moz-appearance: none; appearance: none; padding-right: 3rem; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E&quot;); background-position: right 1.25rem center; background-repeat: no-repeat; background-size: 1rem;">
                            <option value="" disabled selected>---- Pilih Klaster ----</option>
                        </select>
                    </div>
                </div>

                <!-- Jumlah Negara (Conditional visible by JS for Internasional) -->
                <div id="negara-container" class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2" style="display: none;">
                    <label for="jumlah_negara" class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Jumlah Negara</label>
                    <div class="flex-1 w-full">
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
            </div>

            <!-- ================= STEP 3: CAPAIAN PRESTASI & ANGGOTA TIM ================= -->
            <div class="wizard-step hidden space-y-6" id="wizard-step-3">
                <div class="border-b border-gray-100 pb-2 mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Langkah 3: Capaian & Kategori Tim</h2>
                    <p class="text-xs text-gray-400">Pilih pencapaian yang diperoleh dan rincian anggota tim jika kompetisi kelompok.</p>
                </div>

                <!-- Prestasi dicapai -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label for="prestasi_dicapai" class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Prestasi dicapai</label>
                    <div class="flex-1 w-full">
                        <select id="prestasi_dicapai" name="prestasi_dicapai" required
                            class="w-full h-11 md:h-12 appearance-none rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm"
                            style="-webkit-appearance: none; -moz-appearance: none; appearance: none; padding-right: 3rem; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E&quot;); background-position: right 1.25rem center; background-repeat: no-repeat; background-size: 1rem;">
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
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Kategori</label>
                    <div class="flex-1 w-full flex items-center gap-6 pl-2">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="radio" name="kategori" value="Individu" checked
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

                <!-- Anggota List Container (Only show for Kelompok) -->
                @php
                    $user = auth()->user();
                    $userNama = trim(($user->nama_depan ?? '') . ' ' . ($user->nama_belakang ?? ''));
                    $userProdi = $user->prodi ?? '';
                @endphp
                <div id="anggota-wrapper" class="space-y-6">
                    <!-- Anggota 1 is ALWAYS the logged-in user and read-only -->
                    <div id="anggota-block-1" class="p-4 rounded-2xl bg-gray-50 border border-gray-200">
                        <h4 class="font-bold text-sm text-gray-800 mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-red-100 text-red-700 flex items-center justify-center text-xs">1</span>
                            Anggota Pertama (Diri Sendiri)
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-400 block mb-1">NIM</label>
                                <input type="text" name="nim_anggota_1" value="{{ $user->nim }}" readonly
                                    class="w-full h-10 rounded-full border border-gray-300 bg-gray-100 px-4 text-sm text-gray-500 cursor-not-allowed outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-400 block mb-1">Nama</label>
                                <input type="text" name="nama_anggota_1" value="{{ $userNama }}" readonly
                                    class="w-full h-10 rounded-full border border-gray-300 bg-gray-100 px-4 text-sm text-gray-500 cursor-not-allowed outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-400 block mb-1">Prodi</label>
                                <input type="text" name="prodi_anggota_1" value="{{ $userProdi }}" readonly
                                    class="w-full h-10 rounded-full border border-gray-300 bg-gray-100 px-4 text-sm text-gray-500 cursor-not-allowed outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tambah / Kurang Anggota Buttons (visible by JS only if Kelompok selected) -->
                <div id="anggota-buttons-wrapper" class="flex justify-end gap-3 pt-2" style="display: none;">
                    <button type="button" id="btn-kurang-anggota" style="display: none;" class="inline-flex min-w-[128px] items-center justify-center rounded-full border border-red-700 bg-white px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-50 focus:ring-2 focus:ring-red-200">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                        </svg>
                        Hapus Anggota
                    </button>
                    <button type="button" id="btn-tambah-anggota" class="inline-flex min-w-[128px] items-center justify-center rounded-full bg-red-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-800 focus:ring-2 focus:ring-red-200">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Anggota
                    </button>
                </div>
            </div>

            <!-- ================= STEP 4: DOSEN PEMBIMBING ================= -->
            <div class="wizard-step hidden space-y-6" id="wizard-step-4">
                <div class="border-b border-gray-100 pb-2 mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Langkah 4: Informasi Dosen Pembimbing</h2>
                    <p class="text-xs text-gray-400">Isi jika memiliki dosen pembimbing untuk kompetisi ini (Opsional).</p>
                </div>

                <!-- Nama Dosen -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label for="nama_dosen" class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Nama Dosen</label>
                    <div class="flex-1 w-full">
                        <input id="nama_dosen" type="text" name="nama_dosen"
                            class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                    </div>
                </div>

                <!-- NIDN Dosen -->
                <div class="flex flex-col md:flex-row md:items-start w-full mb-6 gap-2">
                    <label for="nidn_dosen" class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px] md:pt-3">NIDN Dosen</label>
                    <div class="flex-1 w-full">
                        <input id="nidn_dosen" type="text" name="nidn_dosen" maxlength="10"
                            class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                        <p class="mt-1.5 pl-4 text-xs text-gray-400">Maks. 10 digit angka</p>
                    </div>
                </div>

                <!-- NIP Dosen -->
                <div class="flex flex-col md:flex-row md:items-start w-full mb-6 gap-2">
                    <label for="nip_dosen" class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px] md:pt-3">NIP Dosen</label>
                    <div class="flex-1 w-full">
                        <input id="nip_dosen" type="text" name="nip_dosen" maxlength="18"
                            class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                        <p class="mt-1.5 pl-4 text-xs text-gray-400">Maks. 18 digit angka</p>
                    </div>
                </div>

                <!-- Prodi Dosen -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label for="prodi_dosen" class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Prodi Dosen</label>
                    <div class="flex-1 w-full">
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
                            <option value="S1 Desain Komunikasi Visual">S1 Desain Komunikasi Visual</option>
                            <option value="S1 Desain Produk">S1 Desain Produk</option>
                            <option value="S1 Bisnis Digital">S1 Bisnis Digital</option>
                            <option value="D3 Teknik Telekomunikasi">D3 Teknik Telekomunikasi</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- ================= STEP 5: EVIDENCE ================= -->
            <div class="wizard-step hidden space-y-6" id="wizard-step-5">
                <div class="border-b border-gray-100 pb-2 mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Langkah 5: Bukti Evidence / Dokumen Pendukung</h2>
                    <p class="text-xs text-gray-400">Unggah minimal 1 dokumen pendukung untuk memverifikasi prestasi Anda.</p>
                </div>

                <!-- Surat Tugas Dosen -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Surat Tugas Dosen</label>
                    <div class="flex-1 w-full">
                        <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                            <input type="file" name="surat_tugas_dosen" class="hidden file-evidence" accept=".pdf,.doc,.docx">
                            <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                                </svg>
                                <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidence disini</span>
                                <p class="text-xs text-gray-400">.pdf, .doc, .docx &nbsp;·&nbsp; Maks. 5 MB</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Surat Tugas Mahasiswa -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Surat Tugas Mahasiswa</label>
                    <div class="flex-1 w-full">
                        <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                            <input type="file" name="surat_tugas_mahasiswa" class="hidden file-evidence" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                            <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                                </svg>
                                <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidence disini</span>
                                <p class="text-xs text-gray-400">.pdf, .doc, .docx, .png, .jpg, .jpeg &nbsp;·&nbsp; Maks. 5 MB</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Sertifikat Juara -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Sertifikat Juara</label>
                    <div class="flex-1 w-full">
                        <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                            <input type="file" name="sertifikat_juara" class="hidden file-evidence" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                            <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                                </svg>
                                <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidence disini</span>
                                <p class="text-xs text-gray-400">.pdf, .doc, .docx, .png, .jpg, .jpeg &nbsp;·&nbsp; Maks. 5 MB</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Penyerahan Penghargaan -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Penyerahan Penghargaan</label>
                    <div class="flex-1 w-full">
                        <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                            <input type="file" name="penyerahan_penghargaan" class="hidden file-evidence" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                            <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                                </svg>
                                <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidence disini</span>
                                <p class="text-xs text-gray-400">.pdf, .doc, .docx, .png, .jpg, .jpeg &nbsp;·&nbsp; Maks. 5 MB</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Bukti Keikutsertaan -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Bukti Keikutsertaan</label>
                    <div class="flex-1 w-full">
                        <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                            <input type="file" name="bukti_keikutsertaan" class="hidden file-evidence" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                            <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                                </svg>
                                <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidence disini</span>
                                <p class="text-xs text-gray-400">.pdf, .doc, .docx, .png, .jpg, .jpeg &nbsp;·&nbsp; Maks. 5 MB</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- URL / Link Informasi Kegiatan -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">URL / Link Informasi</label>
                    <div class="flex-1 w-full">
                        <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                            <input type="file" name="url_kegiatan" class="hidden file-evidence" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                            <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                                </svg>
                                <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidence disini</span>
                                <p class="text-xs text-gray-400">.pdf, .doc, .docx, .png, .jpg, .jpeg &nbsp;·&nbsp; Maks. 5 MB</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Foto Formal/Non Formal -->
                <div class="flex flex-col md:flex-row md:items-center w-full mb-6 gap-2">
                    <label class="text-sm font-medium text-gray-700 md:w-44 md:min-w-[176px]">Foto Formal/Non Formal</label>
                    <div class="flex-1 w-full">
                        <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                            <input type="file" name="foto_kegiatan" class="hidden file-evidence" accept=".png,.jpg,.jpeg">
                            <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                                </svg>
                                <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidence disini</span>
                                <p class="text-xs text-gray-400">.png, .jpg, .jpeg &nbsp;·&nbsp; Maks. 5 MB</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 my-6 pt-6"></div>

            <!-- Wizard Navigation Action Bar -->
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                <!-- Tombol Kembali -->
                <button type="button" id="btn-wizard-prev" style="display: none;"
                    class="inline-flex min-w-[140px] items-center justify-center rounded-full border border-gray-400 bg-white px-5 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 hover:border-gray-500 hover:-translate-y-0.5 focus:ring-2 focus:ring-gray-200">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    Kembali
                </button>

                <!-- Spacer -->
                <div id="wizard-nav-spacer" class="hidden sm:block"></div>

                <!-- Tombol Berikutnya / Kirim -->
                <button type="button" id="btn-wizard-next"
                    class="inline-flex min-w-[140px] items-center justify-center rounded-full bg-red-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-red-800 hover:-translate-y-0.5 hover:shadow-lg focus:ring-2 focus:ring-red-200 ml-auto">
                    Berikutnya
                    <svg class="ml-2 h-4 w-4" id="next-icon" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    let currentStep = 1;
    const totalSteps = 5;
    const form = document.getElementById('lapor-prestasi-wizard-form');
    const btnPrev = document.getElementById('btn-wizard-prev');
    const btnNext = document.getElementById('btn-wizard-next');
    const nextIcon = document.getElementById('next-icon');
    const alertContainer = document.getElementById('alert-container');

    // Step elements
    const stepIndicators = document.querySelectorAll('.step-indicator');
    const progressFill = document.getElementById('progress-line-fill');

    // Sub-elements detail kompetisi
    const tingkatRadios = document.querySelectorAll('input[name="tingkat_kompetisi"]');
    const klasterContainer = document.getElementById('klaster-container');
    const negaraContainer = document.getElementById('negara-container');
    const klasterSelect = document.getElementById('klaster');

    // Sub-elements capaian & kelompok
    const kategoriRadios = document.querySelectorAll('input[name="kategori"]');
    const anggotaWrapper = document.getElementById('anggota-wrapper');
    const anggotaButtonsWrapper = document.getElementById('anggota-buttons-wrapper');
    const btnTambahAnggota = document.getElementById('btn-tambah-anggota');
    const btnKurangAnggota = document.getElementById('btn-kurang-anggota');
    let anggotaCount = 1;

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

    // ================= DYNAMIC FORM VISIBILITY LOGIC =================

    // 1. Klaster and Negara Visibility
    function updateTingkatVisibility() {
        const checkedVal = document.querySelector('input[name="tingkat_kompetisi"]:checked')?.value;
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
    }

    tingkatRadios.forEach(radio => {
        radio.addEventListener('change', updateTingkatVisibility);
    });
    updateTingkatVisibility();

    // 2. Kategori (Individu vs Kelompok) dynamic elements
    function updateKategoriVisibility() {
        const checkedVal = document.querySelector('input[name="kategori"]:checked')?.value;
        if (checkedVal === 'Kelompok') {
            anggotaButtonsWrapper.style.display = 'flex';
        } else {
            anggotaButtonsWrapper.style.display = 'none';
            // Remove additional members
            document.querySelectorAll('.extra-anggota-block').forEach(el => el.remove());
            anggotaCount = 1;
            updateKurangButton();
        }
    }

    kategoriRadios.forEach(radio => {
        radio.addEventListener('change', updateKategoriVisibility);
    });

    function updateKurangButton() {
        if (anggotaCount > 1) {
            btnKurangAnggota.style.display = 'inline-flex';
        } else {
            btnKurangAnggota.style.display = 'none';
        }
    }

    btnTambahAnggota.addEventListener('click', function () {
        anggotaCount++;
        const html = `
        <div id="anggota-block-${anggotaCount}" class="p-4 rounded-2xl bg-gray-50 border border-gray-200 mt-4 extra-anggota-block">
            <h4 class="font-bold text-sm text-gray-800 mb-4 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-red-100 text-red-700 flex items-center justify-center text-xs">${anggotaCount}</span>
                Anggota ${anggotaCount}
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="nim_anggota_${anggotaCount}" class="text-xs font-semibold text-gray-400 block mb-1">NIM</label>
                    <input type="text" id="nim_anggota_${anggotaCount}" name="nim_anggota_${anggotaCount}" required
                        class="w-full h-10 rounded-full border border-gray-400 bg-white px-4 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                </div>
                <div>
                    <label for="nama_anggota_${anggotaCount}" class="text-xs font-semibold text-gray-400 block mb-1">Nama</label>
                    <input type="text" id="nama_anggota_${anggotaCount}" name="nama_anggota_${anggotaCount}" required
                        class="w-full h-10 rounded-full border border-gray-400 bg-white px-4 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                </div>
                <div>
                    <label for="prodi_anggota_${anggotaCount}" class="text-xs font-semibold text-gray-400 block mb-1">Prodi</label>
                    <select id="prodi_anggota_${anggotaCount}" name="prodi_anggota_${anggotaCount}" required
                        class="w-full h-10 rounded-full border border-gray-400 bg-white px-4 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                        <option value="" disabled selected>---- Pilih Prodi ----</option>
                        <option value="D3 Teknik Telekomunikasi">D3 Teknik Telekomunikasi</option>
                        <option value="S1 Teknik Telekomunikasi">S1 Teknik Telekomunikasi</option>
                        <option value="S1 Teknik Elektro">S1 Teknik Elektro</option>
                        <option value="S1 Teknik Biomedis">S1 Teknik Biomedis</option>
                        <option value="S1 Teknologi Pangan">S1 Teknologi Pangan</option>
                        <option value="S1 Desain Produk">S1 Desain Produk</option>
                        <option value="S1 Teknik Logistik">S1 Teknik Logistik</option>
                        <option value="S1 Desain Komunikasi Visual">S1 Desain Komunikasi Visual</option>
                        <option value="S1 Teknik Informatika">S1 Teknik Informatika</option>
                        <option value="S1 Sains Data">S1 Sains Data</option>
                        <option value="S1 Rekayasa Perangkat Lunak">S1 Rekayasa Perangkat Lunak</option>
                        <option value="S1 Sistem Informasi">S1 Sistem Informasi</option>
                        <option value="S1 Teknik Industri">S1 Teknik Industri</option>
                        <option value="S1 Bisnis Digital">S1 Bisnis Digital</option>
                    </select>
                </div>
            </div>
        </div>`;

        anggotaWrapper.insertAdjacentHTML('beforeend', html);
        updateKurangButton();
    });

    btnKurangAnggota.addEventListener('click', function () {
        if (anggotaCount > 1) {
            const block = document.getElementById(`anggota-block-${anggotaCount}`);
            if (block) {
                block.remove();
                anggotaCount--;
                updateKurangButton();
            }
        }
    });

    // 3. File upload visual feedback
    form.querySelectorAll('input[type="file"]').forEach(function (input) {
        input.addEventListener('change', function () {
            const labelSpan = input.closest('label')?.querySelector('span');
            const extText = input.closest('label')?.querySelector('p');
            if (labelSpan && this.files?.[0]?.name) {
                labelSpan.textContent = `File dipilih: ${this.files[0].name}`;
                labelSpan.classList.add('text-red-700', 'font-bold');
                if (extText) extText.style.display = 'none';
            }
        });
    });

    // ================= WIZARD ENGINE =================

    function showAlert(message, type = 'error') {
        alertContainer.classList.remove('hidden');
        alertContainer.className = `mb-6 rounded-xl border px-4 py-3 text-sm shadow-sm ${
            type === 'success' ? 'border-green-200 bg-green-50 text-green-800' : 'border-red-200 bg-red-50 text-red-800'
        }`;
        alertContainer.textContent = message;
        // Scroll to alert
        alertContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function hideAlert() {
        alertContainer.classList.add('hidden');
    }

    function validateStep(step) {
        hideAlert();
        const container = document.getElementById(`wizard-step-${step}`);
        if (!container) return true;

        const inputs = container.querySelectorAll('input[required], select[required]');
        let valid = true;
        let firstInvalid = null;

        inputs.forEach(input => {
            // Check visibility (e.g. Klaster/Negara might be hidden but have required if they are in the step)
            let isVisible = input.offsetWidth > 0 && input.offsetHeight > 0;
            if (isVisible && !input.value) {
                valid = false;
                input.classList.add('border-red-500', 'ring-2', 'ring-red-100');
                if (!firstInvalid) firstInvalid = input;

                input.addEventListener('input', function removeErr() {
                    input.classList.remove('border-red-500', 'ring-2', 'ring-red-100');
                    input.removeEventListener('input', removeErr);
                });
            }
        });

        if (!valid) {
            showAlert('Lengkapi semua data bertanda merah sebelum melanjutkan.');
            if (firstInvalid) firstInvalid.focus();
            return false;
        }

        // Additional validation
        if (step === 5) {
            // Must upload at least 1 document
            const files = form.querySelectorAll('input[type="file"]');
            let hasFile = false;
            files.forEach(f => {
                if (f.files && f.files.length > 0) hasFile = true;
            });

            if (!hasFile) {
                showAlert('Harap unggah minimal 1 dokumen evidence (misalnya Sertifikat Juara atau Surat Tugas).');
                return false;
            }
        }

        return true;
    }

    function updateWizardProgress() {
        // Show/hide step panels
        for (let i = 1; i <= totalSteps; i++) {
            const stepPanel = document.getElementById(`wizard-step-${i}`);
            if (stepPanel) {
                if (i === currentStep) {
                    stepPanel.classList.remove('hidden');
                } else {
                    stepPanel.classList.add('hidden');
                }
            }
        }

        // Update progress bar at the top
        stepIndicators.forEach(ind => {
            const stepNum = parseInt(ind.dataset.step);
            const circle = ind.querySelector('div');
            const label = ind.querySelector('span');

            if (stepNum < currentStep) {
                // Completed
                circle.className = 'w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300 bg-red-100 text-red-700 border-2 border-red-500';
                circle.textContent = '✓';
                label.className = 'text-xs font-semibold text-red-700 mt-2';
            } else if (stepNum === currentStep) {
                // Active
                circle.className = 'w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300 bg-red-600 text-white shadow-md shadow-red-200 border-2 border-red-600';
                circle.textContent = stepNum;
                label.className = 'text-xs font-semibold text-red-600 mt-2';
            } else {
                // Unvisited
                circle.className = 'w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300 bg-gray-100 text-gray-400 border border-gray-200';
                circle.textContent = stepNum;
                label.className = 'text-xs font-semibold text-gray-400 mt-2';
            }
        });

        // Fill line
        const fillPercent = ((currentStep - 1) / (totalSteps - 1)) * 100;
        progressFill.style.width = `${fillPercent}%`;

        // Update navigation buttons
        if (currentStep === 1) {
            btnPrev.style.display = 'none';
        } else {
            btnPrev.style.display = 'inline-flex';
        }

        if (currentStep === totalSteps) {
            btnNext.innerHTML = `
                Kirim Laporan
                <svg class="ml-2 h-4 w-4 -rotate-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                </svg>
            `;
            btnNext.classList.remove('bg-red-700', 'hover:bg-red-800');
            btnNext.classList.add('bg-green-700', 'hover:bg-green-800');
        } else {
            btnNext.innerHTML = `
                Berikutnya
                <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            `;
            btnNext.classList.remove('bg-green-700', 'hover:bg-green-800');
            btnNext.classList.add('bg-red-700', 'hover:bg-red-800');
        }

        // Scroll to top of form
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    btnPrev.addEventListener('click', function () {
        if (currentStep > 1) {
            currentStep--;
            updateWizardProgress();
        }
    });

    btnNext.addEventListener('click', async function () {
        if (!validateStep(currentStep)) {
            return;
        }

        if (currentStep < totalSteps) {
            currentStep++;
            updateWizardProgress();
        } else {
            // Final submit
            await submitWizardForm();
        }
    });

    // ================= FORM SUBMISSION THROUGH API =================

    async function submitWizardForm() {
        btnNext.disabled = true;
        btnNext.innerHTML = `
            Memproses...
            <svg class="animate-spin ml-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        `;

        try {
            const createdPrestasi = await createMainPrestasi();
            const idPrestasi = createdPrestasi.data?.id_prestasi;

            if (!idPrestasi) {
                throw new Error('Gagal mendapatkan ID Prestasi yang baru dibuat.');
            }

            // Create Anggota and Dosen
            await Promise.all([
                createAnggotaTim(idPrestasi),
                createDosenPembimbing(idPrestasi)
            ]);

            showAlert('Selamat! Laporan prestasi Anda berhasil dikirim dan sedang menunggu verifikasi.', 'success');

            // Redirect after success
            setTimeout(function () {
                window.location.href = '/prestasi';
            }, 1500);

        } catch (error) {
            console.error('Submission failed:', error);
            showAlert(error.response?.data?.message || error.message || 'Gagal mengirim laporan prestasi. Silakan coba lagi.');
            
            // Re-enable button
            btnNext.disabled = false;
            btnNext.innerHTML = `
                Kirim Laporan
                <svg class="ml-2 h-4 w-4 -rotate-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                </svg>
            `;
        }
    }

    async function createMainPrestasi() {
        const formData = new FormData();
        
        // Basic fields
        formData.append('nama_kompetisi', document.getElementById('nama_kompetisi').value);
        formData.append('penyelenggara', document.getElementById('penyelenggara').value);
        formData.append('tingkat', document.querySelector('input[name="tingkat_kompetisi"]:checked')?.value || 'Regional');
        formData.append('capaian', document.getElementById('prestasi_dicapai').value);
        formData.append('kategori', document.querySelector('input[name="kategori"]:checked')?.value || 'Individu');
        formData.append('mewakili_ormawa', document.querySelector('input[name="mewakili_ormawa"]:checked')?.value || 'tidak');
        formData.append('pelaksanaan', document.querySelector('input[name="pelaksanaan"]:checked')?.value || 'Luring');
        formData.append('waktu_kompetisi', document.getElementById('waktu_kompetisi').value || '');
        formData.append('tanggal_pengumuman', document.getElementById('tanggal_pengumuman').value || '');

        const klasterVal = document.getElementById('klaster').value;
        if (klasterVal) {
            formData.append('klaster', klasterVal);
        }

        const negaraRaw = document.getElementById('jumlah_negara').value;
        let negaraVal = null;
        if (negaraRaw === '<11 negara') negaraVal = 10;
        else if (negaraRaw === '11-20 negara') negaraVal = 15;
        else if (negaraRaw === '>20 negara') negaraVal = 25;

        if (negaraVal) {
            formData.append('jumlah_negara', negaraVal);
        }

        // Document mappings
        const mapping = [
            ['Surat Tugas Dosen', form.querySelector('[name="surat_tugas_dosen"]').files[0]],
            ['Surat Tugas Mahasiswa', form.querySelector('[name="surat_tugas_mahasiswa"]').files[0]],
            ['Sertifikat Juara', form.querySelector('[name="sertifikat_juara"]').files[0]],
            ['Penyerahan Penghargaan', form.querySelector('[name="penyerahan_penghargaan"]').files[0]],
            ['Bukti Keikutsertaan', form.querySelector('[name="bukti_keikutsertaan"]').files[0]],
            ['URL / Link Informasi Kegiatan', form.querySelector('[name="url_kegiatan"]').files[0]],
            ['Foto Formal/Non Formal', form.querySelector('[name="foto_kegiatan"]').files[0]]
        ];

        let index = 0;
        mapping.forEach(([jenisDokumen, file]) => {
            if (file) {
                formData.append(`dokumen[${index}][jenis_dokumen]`, jenisDokumen);
                formData.append(`dokumen[${index}][file]`, file);
                index++;
            }
        });

        // Submit via Axios (token is handled in interceptor defaults)
        const res = await window.axios.post('prestasi', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        return res.data;
    }

    async function createAnggotaTim(idPrestasi) {
        const kategori = document.querySelector('input[name="kategori"]:checked')?.value;
        if (kategori !== 'Kelompok') return;

        const promises = [];
        // Loop from anggota index 2 to current anggotaCount (anggota 1 is self, backend registers automatically)
        for (let i = 2; i <= anggotaCount; i++) {
            const nim = document.getElementById(`nim_anggota_${i}`)?.value;
            const nama = document.getElementById(`nama_anggota_${i}`)?.value;
            const prodi = document.getElementById(`prodi_anggota_${i}`)?.value;

            if (nim && nama && prodi) {
                promises.push(
                    window.axios.post(`prestasi/${idPrestasi}/anggota`, {
                        nama: nama,
                        nim: nim,
                        prodi: prodi
                    })
                );
            }
        }

        if (promises.length > 0) {
            await Promise.all(promises);
        }
    }

    async function createDosenPembimbing(idPrestasi) {
        const nama = document.getElementById('nama_dosen')?.value;
        const prodi = document.getElementById('prodi_dosen')?.value;
        const nidn = document.getElementById('nidn_dosen')?.value;
        const nip = document.getElementById('nip_dosen')?.value;

        if (!nama || !prodi) return; // Dosen is optional

        const fd = new FormData();
        fd.append('nama', nama);
        fd.append('prodi', prodi);
        if (nidn) fd.append('nidn', nidn);
        if (nip) fd.append('nip', nip);

        // Surat Tugas Dosen (from Step 5) is also uploaded here if present
        const suratTugasFile = form.querySelector('[name="surat_tugas_dosen"]').files[0];
        if (suratTugasFile) {
            fd.append('surat_tugas', suratTugasFile);
        } else {
            // Create a dummy small blob as the API requires surat_tugas file
            // Let's create an empty 1-byte PDF blob if not uploaded
            const blob = new Blob(["%PDF-1.5"], { type: "application/pdf" });
            fd.append('surat_tugas', blob, 'surat_tugas_placeholder.pdf');
        }

        await window.axios.post(`prestasi/${idPrestasi}/dosen`, fd, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
    }

    // Init progress indicators
    updateWizardProgress();
});
</script>
@endpush
