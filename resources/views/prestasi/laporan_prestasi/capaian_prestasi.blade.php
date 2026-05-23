@extends('layouts.app')

@section('title', 'Input Prestasi - Capaian Prestasi')

@section('content')
<div class="laporan-prestasi-page min-h-screen bg-gray-100 flex justify-center items-start px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold leading-tight text-gray-800 sm:text-3xl">Input Prestasi - Capaian Prestasi</h1>
        </div>

        <form method="POST" action="#" class="mt-4 sm:mt-8">
            @csrf

            <!-- Prestasi dicapai -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="prestasi_dicapai" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Prestasi dicapai</label>
                <div class="flex-1 min-w-0 w-full">
                    <select
                        id="prestasi_dicapai"
                        name="prestasi_dicapai"
                        class="w-full h-11 md:h-12 appearance-none rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm"
                        style="-webkit-appearance: none; -moz-appearance: none; appearance: none; padding-right: 3rem; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E&quot;); background-position: right 1.25rem center; background-repeat: no-repeat; background-size: 1rem;">
                        <option value="" disabled selected>---- Pilih Juara yang di Raih ----</option>
                        <option value="Juara 1" class="text-gray-700">Juara 1</option>
                        <option value="Juara 2" class="text-gray-700">Juara 2</option>
                        <option value="Juara 3" class="text-gray-700">Juara 3</option>
                        <option value="Juara Harapan" class="text-gray-700">Juara Harapan</option>
                        <option value="Finalis" class="text-gray-700">Finalis</option>
                        <option value="Lolos Pendanaan" class="text-gray-700">Lolos Pendanaan</option>
                        <option value="Penerima Hibah" class="text-gray-700">Penerima Hibah</option>
                        <option value="Medali Emas" class="text-gray-700">Medali Emas</option>
                        <option value="Medali Perak" class="text-gray-700">Medali Perak</option>
                        <option value="Medali Perunggu" class="text-gray-700">Medali Perunggu</option>
                        <option value="Most Inspiration/Kategori Lainnya" class="text-gray-700">Most Inspiration/Kategori Lainnya</option>
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

            <div id="anggota-container">
                <!-- Anggota 1 -->
                <div class="flex flex-row items-center w-full mb-6 gap-2">
                    <label for="nim_anggota_1" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">NIM Anggota 1</label>
                    <div class="flex-1 min-w-0 w-full">
                        <input id="nim_anggota_1" type="text" name="nim_anggota_1" class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                    </div>
                </div>
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="nama_anggota_1" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Nama Anggota 1</label>
                <div class="flex-1 min-w-0 w-full">
                    <input id="nama_anggota_1" type="text" name="nama_anggota_1" class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                </div>
            </div>
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="prodi_anggota_1" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Prodi Anggota 1</label>
                <div class="flex-1 min-w-0 w-full">
                    <input id="prodi_anggota_1" type="text" name="prodi_anggota_1" class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                </div>
            </div>

            <!-- Anggota 2 -->
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="nim_anggota_2" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Nim Anggota 2</label>
                <div class="flex-1 min-w-0 w-full">
                    <input id="nim_anggota_2" type="text" name="nim_anggota_2" class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                </div>
            </div>
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="nama_anggota_2" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Nama Anggota 2</label>
                <div class="flex-1 min-w-0 w-full">
                    <input id="nama_anggota_2" type="text" name="nama_anggota_2" class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                </div>
            </div>
            <div class="flex flex-row items-center w-full mb-6 gap-2">
                <label for="prodi_anggota_2" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Prodi Anggota 2</label>
                <div class="flex-1 min-w-0 w-full">
                    <input id="prodi_anggota_2" type="text" name="prodi_anggota_2" class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                </div>
            </div>
            </div>

            <!-- Tambah/Kurang Anggota Buttons -->
            <div class="mb-8 flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end sm:gap-4">
                <button type="button" id="btn-kurang-anggota" style="display: none;" class="inline-flex min-w-[128px] items-center justify-center rounded-full border border-red-700 bg-white px-5 py-2.5 text-sm font-medium text-red-700 transition hover:bg-red-50 focus:ring-2 focus:ring-red-200">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                    </svg>
                    Anggota
                </button>
                <button type="button" id="btn-tambah-anggota" class="inline-flex min-w-[128px] items-center justify-center rounded-full bg-red-700 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-red-800 focus:ring-2 focus:ring-red-200">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Anggota
                </button>
            </div>

            <div class="border-t border-gray-200 my-6"></div>

            <!-- Navigasi -->
            <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                <!-- Tombol Kembali -->
                <a href="{{ route('prestasi.laporan_prestasi.detail_kompetisi') }}" class="inline-flex min-w-[140px] items-center justify-center rounded-full border border-gray-400 bg-white px-5 py-2 text-sm font-medium text-gray-700 transition-all duration-300 hover:bg-gray-100 hover:border-gray-500 hover:text-gray-900 hover:-translate-y-1 hover:shadow-md focus:ring-2 focus:ring-gray-200">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    Kembali
                </a>
                
                <!-- Tombol Berikutnya -->
                <a href="{{ route('prestasi.laporan_prestasi.informasi_dosen_pembimbing') }}" class="inline-flex min-w-[140px] items-center justify-center rounded-full bg-red-700 px-5 py-2 text-sm font-medium text-white transition-all duration-300 hover:bg-red-800 hover:-translate-y-1 hover:shadow-lg hover:shadow-red-200 focus:ring-2 focus:ring-red-200">
                    Berikutnya
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const container = document.getElementById("anggota-container");
        const btnTambah = document.getElementById("btn-tambah-anggota");
        const btnKurang = document.getElementById("btn-kurang-anggota");
        let anggotaCount = 2; // Mulai dari anggota ke-3 dst

        function updateKurangButton() {
            if (anggotaCount > 2) {
                btnKurang.style.display = "inline-flex";
            } else {
                btnKurang.style.display = "none";
            }
        }

        btnTambah.addEventListener("click", function () {
            anggotaCount++;

            const html = `
            <div id="anggota-block-${anggotaCount}" class="mt-4 pt-4 border-t border-gray-100 anggota-block">
                <!-- Anggota ${anggotaCount} -->
                <div class="flex flex-row items-center w-full mb-6 gap-2">
                    <label for="nim_anggota_${anggotaCount}" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">NIM Anggota ${anggotaCount}</label>
                    <div class="flex-1 min-w-0 w-full">
                        <input id="nim_anggota_${anggotaCount}" type="text" name="nim_anggota_${anggotaCount}" class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                    </div>
                </div>
                <div class="flex flex-row items-center w-full mb-6 gap-2">
                    <label for="nama_anggota_${anggotaCount}" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Nama Anggota ${anggotaCount}</label>
                    <div class="flex-1 min-w-0 w-full">
                        <input id="nama_anggota_${anggotaCount}" type="text" name="nama_anggota_${anggotaCount}" class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                    </div>
                </div>
                <div class="flex flex-row items-center w-full mb-6 gap-2">
                    <label for="prodi_anggota_${anggotaCount}" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Prodi Anggota ${anggotaCount}</label>
                    <div class="flex-1 min-w-0 w-full">
                        <input id="prodi_anggota_${anggotaCount}" type="text" name="prodi_anggota_${anggotaCount}" class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm">
                    </div>
                </div>
            </div>`;

            container.insertAdjacentHTML("beforeend", html);
            updateKurangButton();
        });

        btnKurang.addEventListener("click", function () {
            if (anggotaCount > 2) {
                const lastAnggota = document.getElementById(`anggota-block-${anggotaCount}`);
                if (lastAnggota) {
                    lastAnggota.remove();
                    anggotaCount--;
                    updateKurangButton();
                }
            }
        });
    });
</script>
@endsection
