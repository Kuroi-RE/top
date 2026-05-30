@extends('layouts.app')

@section('title', 'Input Prestasi - Capaian Prestasi')

@section('content')
<div class="laporan-prestasi-page min-h-screen bg-gray-100 flex justify-center items-start px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold leading-tight text-gray-800 sm:text-3xl">Input Prestasi - Capaian Prestasi</h1>
        </div>

        <form id="prestasi-capaian-form" method="POST" action="#" class="mt-4 sm:mt-8" data-prev-url="{{ route('prestasi.laporan_prestasi.detail_kompetisi') }}" data-next-url="{{ route('prestasi.laporan_prestasi.informasi_dosen_pembimbing') }}">
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

            @php
                $user = auth()->user();
                $userNama = trim(($user->nama_depan ?? '') . ' ' . ($user->nama_belakang ?? ''));
                $userProdi = $user->prodi ?? '';
            @endphp
            <div id="anggota-container">
                <!-- Anggota 1 (auto-fill dari data user login) -->
                <div class="flex flex-row items-center w-full mb-6 gap-2">
                    <label for="nim_anggota_1" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">NIM Anggota 1</label>
                    <div class="flex-1 min-w-0 w-full">
                        <input id="nim_anggota_1" type="text" name="nim_anggota_1"
                            value="{{ $user->nim }}"
                            readonly
                            class="w-full h-11 md:h-12 rounded-full border border-gray-300 bg-gray-100 px-5 text-sm text-gray-500 outline-none cursor-not-allowed shadow-sm">
                    </div>
                </div>
                <div class="flex flex-row items-center w-full mb-6 gap-2">
                    <label for="nama_anggota_1" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Nama Anggota 1</label>
                    <div class="flex-1 min-w-0 w-full">
                        <input id="nama_anggota_1" type="text" name="nama_anggota_1"
                            value="{{ $userNama }}"
                            readonly
                            class="w-full h-11 md:h-12 rounded-full border border-gray-300 bg-gray-100 px-5 text-sm text-gray-500 outline-none cursor-not-allowed shadow-sm">
                    </div>
                </div>
                <div class="flex flex-row items-center w-full mb-6 gap-2">
                    <label for="prodi_anggota_1" style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Prodi Anggota 1</label>
                    <div class="flex-1 min-w-0 w-full">
                        @if($userProdi)
                            <input id="prodi_anggota_1" type="text" name="prodi_anggota_1"
                                value="{{ $userProdi }}"
                                readonly
                                class="w-full h-11 md:h-12 rounded-full border border-gray-300 bg-gray-100 px-5 text-sm text-gray-500 outline-none cursor-not-allowed shadow-sm">
                        @else
                            <select id="prodi_anggota_1" name="prodi_anggota_1"
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
                                <option value="S1 Desain Komunikasi Visual (DKV)">S1 Desain Komunikasi Visual (DKV)</option>
                                <option value="S1 Desain Produk">S1 Desain Produk</option>
                                <option value="S1 Bisnis Digital">S1 Bisnis Digital</option>
                                <option value="D3 Teknik Telekomunikasi">D3 Teknik Telekomunikasi</option>
                            </select>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tambah/Kurang Anggota Buttons -->
            <div id="anggota-buttons-wrapper" class="mb-8 flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end sm:gap-4" style="display: none !important;">
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
                <button type="button" id="prestasi-capaian-prev" class="inline-flex min-w-[140px] items-center justify-center rounded-full border border-gray-400 bg-white px-5 py-2 text-sm font-medium text-gray-700 transition-all duration-300 hover:bg-gray-100 hover:border-gray-500 hover:text-gray-900 hover:-translate-y-1 hover:shadow-md focus:ring-2 focus:ring-gray-200">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    Kembali
                </button>
                
                <!-- Tombol Berikutnya -->
                <button type="button" id="prestasi-capaian-next" class="inline-flex min-w-[140px] items-center justify-center rounded-full bg-red-700 px-5 py-2 text-sm font-medium text-white transition-all duration-300 hover:bg-red-800 hover:-translate-y-1 hover:shadow-lg hover:shadow-red-200 focus:ring-2 focus:ring-red-200">
                    Berikutnya
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const storageKey = 'prestasi-laporan-wizard';
        const form = document.getElementById('prestasi-capaian-form');
        const prevButton = document.getElementById('prestasi-capaian-prev');
        const nextButton = document.getElementById('prestasi-capaian-next');
        const container = document.getElementById("anggota-container");
        const btnTambah = document.getElementById("btn-tambah-anggota");
        const btnKurang = document.getElementById("btn-kurang-anggota");
        const anggotaButtonsWrapper = document.getElementById("anggota-buttons-wrapper");
        let anggotaCount = 1;

        // ── Tampilkan/sembunyikan tombol anggota berdasarkan kategori ──
        function updateAnggotaButtons() {
            const kategori = form.querySelector('[name="kategori"]:checked')?.value;
            if (kategori === 'Kelompok') {
                anggotaButtonsWrapper.style.removeProperty('display');
                anggotaButtonsWrapper.style.setProperty('display', 'flex', 'important');
            } else {
                anggotaButtonsWrapper.style.setProperty('display', 'none', 'important');
                document.querySelectorAll('.anggota-block').forEach(function (el) { el.remove(); });
                anggotaCount = 1;
                btnKurang.style.display = 'none';
            }
        }

        form.querySelectorAll('[name="kategori"]').forEach(function (radio) {
            radio.addEventListener('change', updateAnggotaButtons);
        });

        // Jalankan saat halaman dimuat
        updateAnggotaButtons();

        function updateKurangButton() {
            if (anggotaCount > 1) {
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
                        <select id="prodi_anggota_${anggotaCount}" name="prodi_anggota_${anggotaCount}"
                            class="w-full h-11 md:h-12 appearance-none rounded-full border border-gray-400 bg-[#f9f9f9] px-5 text-sm text-gray-700 outline-none cursor-pointer transition focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm"
                            style="-webkit-appearance:none;background-image:url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%236b7280\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2.5\' d=\'M19 9l-7 7-7-7\'/%3E%3C/svg%3E');background-position:right 1.25rem center;background-repeat:no-repeat;background-size:1rem;padding-right:3rem;">
                            <option value="">---- Pilih Prodi ----</option>
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
                            <option value="S1 Desain Komunikasi Visual (DKV)">S1 Desain Komunikasi Visual (DKV)</option>
                            <option value="S1 Desain Produk">S1 Desain Produk</option>
                            <option value="S1 Bisnis Digital">S1 Bisnis Digital</option>
                            <option value="D3 Teknik Telekomunikasi">D3 Teknik Telekomunikasi</option>
                        </select>
                    </div>
                </div>
            </div>`;

            container.insertAdjacentHTML("beforeend", html);
            updateKurangButton();
        });

        btnKurang.addEventListener("click", function () {
            if (anggotaCount > 1) {
                const lastAnggota = document.getElementById(`anggota-block-${anggotaCount}`);
                if (lastAnggota) {
                    lastAnggota.remove();
                    anggotaCount--;
                    updateKurangButton();
                }
            }
        });

        function loadState() {
            try {
                return JSON.parse(localStorage.getItem(storageKey) || '{}');
            } catch (error) {
                return {};
            }
        }

        function saveState(partial) {
            const current = loadState();
            const merged = { ...current, ...partial };
            localStorage.setItem(storageKey, JSON.stringify(merged));
            return merged;
        }

        function collectCapaian() {
            const capaian = { anggota: [] };

            form.querySelectorAll('input, select').forEach(function (field) {
                if (!field.name) return;
                if (field.type === 'radio' && !field.checked) return;
                if (field.name.startsWith('nim_anggota_') || field.name.startsWith('nama_anggota_') || field.name.startsWith('prodi_anggota_')) return;
                capaian[field.name] = field.value;
            });

            let index = 1;
            while (true) {
                const nim = form.querySelector(`[name="nim_anggota_${index}"]`);
                const nama = form.querySelector(`[name="nama_anggota_${index}"]`);
                const prodi = form.querySelector(`[name="prodi_anggota_${index}"]`);

                if (!nim && !nama && !prodi) break;

                if ([nim?.value, nama?.value, prodi?.value].some(Boolean)) {
                    capaian.anggota.push({
                        nim: nim?.value || '',
                        nama: nama?.value || '',
                        prodi: prodi?.value || '',
                    });
                }

                index++;
            }

            return capaian;
        }

        function hydrate() {
            const state = loadState().capaian_prestasi || {};

            if (state.prestasi_dicapai) {
                const select = form.querySelector('[name="prestasi_dicapai"]');
                if (select) select.value = state.prestasi_dicapai;
            }

            if (state.kategori) {
                const radio = form.querySelector(`[name="kategori"][value="${state.kategori}"]`);
                if (radio) radio.checked = true;
            }

            // Panggil updateAnggotaButtons setelah kategori di-restore dari localStorage
            updateAnggotaButtons();

            if (Array.isArray(state.anggota) && state.anggota.length) {
                // Anggota 1 is already in the DOM; add extra slots for anggota 2+
                while (document.querySelectorAll('.anggota-block').length < state.anggota.length - 1) {
                    btnTambah.click();
                }

                state.anggota.forEach(function (member, index) {
                    const slot = index + 1;
                    const nim = form.querySelector(`[name="nim_anggota_${slot}"]`);
                    const nama = form.querySelector(`[name="nama_anggota_${slot}"]`);
                    const prodi = form.querySelector(`[name="prodi_anggota_${slot}"]`);

                    if (nim) nim.value = member.nim || '';
                    if (nama) nama.value = member.nama || '';
                    if (prodi) prodi.value = member.prodi || '';
                });
            }
        }

        form.addEventListener('input', function () {
            saveState({ capaian_prestasi: collectCapaian() });
        });

        form.addEventListener('change', function () {
            saveState({ capaian_prestasi: collectCapaian() });
        });

        prevButton.addEventListener('click', function () {
            saveState({ capaian_prestasi: collectCapaian() });
            window.location.href = form.dataset.prevUrl;
        });

        nextButton.addEventListener('click', function () {
            saveState({ capaian_prestasi: collectCapaian() });
            window.location.href = form.dataset.nextUrl;
        });

        hydrate();
    });
</script>
@endsection
