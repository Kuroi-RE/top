@extends('layouts.app')

@section('title', 'Input Prestasi - Evidence')

@section('content')
<div class="laporan-prestasi-page min-h-screen bg-gray-100 flex justify-center items-start px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold leading-tight text-gray-800 sm:text-3xl">Input Prestasi - Evidence</h1>
        </div>

        @if (session('success'))
            <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form id="prestasi-evidance-form" method="POST" action="#" class="mt-4 sm:mt-8" enctype="multipart/form-data" data-endpoint="{{ route('prestasi.laporan_prestasi.submit') }}" data-redirect="{{ route('prestasi.beranda') }}">
            @csrf

            <!-- Surat Tugas Dosen (dipindah dari halaman Dosen Pembimbing) -->
            <div class="flex flex-row items-center w-full mb-8 gap-2">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Surat Tugas Dosen</label>
                <div class="flex-1 min-w-0 w-full">
                    <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                        <input type="file" name="surat_tugas_dosen" class="hidden" accept=".pdf,.doc,.docx">
                        <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                            </svg>
                            <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidence disini</span>
                            <p class="text-xs text-gray-400">.pdf, .doc, .docx &nbsp;·&nbsp; Maks. 10 MB</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Surat Tugas Mahasiswa -->
            <div class="flex flex-row items-center w-full mb-8 gap-2">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Surat Tugas Mahasiswa</label>
                <div class="flex-1 min-w-0 w-full">
                    <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                        <input type="file" name="surat_tugas_mahasiswa" class="hidden" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                        <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                            </svg>
                            <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidence disini</span>
                            <p class="text-xs text-gray-400">.pdf, .doc, .docx, .png, .jpg, .jpeg &nbsp;·&nbsp; Maks. 10 MB</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Sertifikat Juara -->
            <div class="flex flex-row items-center w-full mb-8 gap-2">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Sertifikat Juara</label>
                <div class="flex-1 min-w-0 w-full">
                    <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                        <input type="file" name="sertifikat_juara" class="hidden" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                        <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                            </svg>
                            <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidence disini</span>
                            <p class="text-xs text-gray-400">.pdf, .doc, .docx, .png, .jpg, .jpeg &nbsp;·&nbsp; Maks. 10 MB</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Penyerahan Penghargaan -->
            <div class="flex flex-row items-center w-full mb-8 gap-2">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Penyerahan Penghargaan</label>
                <div class="flex-1 min-w-0 w-full">
                    <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                        <input type="file" name="penyerahan_penghargaan" class="hidden" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                        <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                            </svg>
                            <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidence disini</span>
                            <p class="text-xs text-gray-400">.pdf, .doc, .docx, .png, .jpg, .jpeg &nbsp;·&nbsp; Maks. 10 MB</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Bukti Keikutsertaan -->
            <div class="flex flex-row items-center w-full mb-8 gap-2">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Bukti Keikutsertaan</label>
                <div class="flex-1 min-w-0 w-full">
                    <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                        <input type="file" name="bukti_keikutsertaan" class="hidden" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                        <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                            </svg>
                            <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidence disini</span>
                            <p class="text-xs text-gray-400">.pdf, .doc, .docx, .png, .jpg, .jpeg &nbsp;·&nbsp; Maks. 10 MB</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- URL / Link Informasi Kegiatan -->
            <div class="flex flex-row items-center w-full mb-8 gap-2">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">URL / Link Informasi Kegiatan</label>
                <div class="flex-1 min-w-0 w-full">
                    <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                        <input type="file" name="url_kegiatan" class="hidden" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                        <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                            </svg>
                            <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidence disini</span>
                            <p class="text-xs text-gray-400">.pdf, .doc, .docx, .png, .jpg, .jpeg &nbsp;·&nbsp; Maks. 10 MB</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Foto Formal/Non Formal -->
            <div class="flex flex-row items-center w-full mb-8 gap-2">
                <label style="width: 160px; min-width: 160px;" class="text-sm font-medium text-gray-700">Foto Formal/Non Formal</label>
                <div class="flex-1 min-w-0 w-full">
                    <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                        <input type="file" name="foto_kegiatan" class="hidden" accept=".png,.jpg,.jpeg">
                        <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                            </svg>
                            <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidence disini</span>
                            <p class="text-xs text-gray-400">.png, .jpg, .jpeg &nbsp;·&nbsp; Maks. 10 MB</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="border-t border-gray-200 my-6"></div>

            <!-- Navigasi -->
            <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                <!-- Tombol Kembali -->
                <a href="{{ route('prestasi.laporan_prestasi.informasi_dosen_pembimbing') }}" class="inline-flex min-w-[140px] items-center justify-center rounded-full border border-gray-400 bg-white px-5 py-2 text-sm font-medium text-gray-700 transition-all duration-300 hover:bg-gray-100 hover:border-gray-500 hover:text-gray-900 hover:-translate-y-1 hover:shadow-md focus:ring-2 focus:ring-gray-200">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    Kembali
                </a>
                
                <!-- Tombol Kirim / Submit -->
                <button type="submit" id="prestasi-evidance-submit" class="inline-flex min-w-[140px] items-center justify-center rounded-full bg-red-700 px-6 py-2 text-sm font-medium text-white transition-all duration-300 hover:bg-red-800 hover:-translate-y-1 hover:shadow-lg hover:shadow-red-200 focus:ring-2 focus:ring-red-200">
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

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const storageKey = 'prestasi-laporan-wizard';
        const form = document.getElementById('prestasi-evidance-form');
        const submitButton = document.getElementById('prestasi-evidance-submit');

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

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function showMessage(type, message) {
            const existing = document.getElementById('prestasi-evidance-feedback');
            if (existing) existing.remove();

            const feedback = document.createElement('div');
            feedback.id = 'prestasi-evidance-feedback';
            feedback.className = 'mb-5 rounded-xl border px-4 py-3 text-sm shadow-sm';
            feedback.textContent = message;

            if (type === 'success') {
                feedback.classList.add('border-green-200', 'bg-green-50', 'text-green-800');
            } else {
                feedback.classList.add('border-red-200', 'bg-red-50', 'text-red-800');
            }

            form.parentElement.insertBefore(feedback, form);
        }

        function collectWizardPayload() {
            const state = loadState();
            const biodata = state.biodata || {};
            const detail = state.detail_kompetisi || {};
            const capaian = state.capaian_prestasi || {};
            const dosen = state.informasi_dosen || {};

            const tingkatMap = {
                Internasional: 'Internasional',
                Nasional: 'Nasional',
                Regional: 'Regional',
            };

            return {
                nama_kompetisi: detail.nama_kompetisi || '',
                penyelenggara: detail.penyelenggara || '',
                tingkat: tingkatMap[detail.tingkat_kompetisi] || detail.tingkat_kompetisi || 'Regional',
                capaian: capaian.prestasi_dicapai || '',
                kategori: capaian.kategori || 'Individu',
                anggota: Array.isArray(capaian.anggota) ? capaian.anggota : [],
                dosen,
                evidance: {
                    surat_tugas_dosen: form.querySelector('[name="surat_tugas_dosen"]')?.files?.[0] || null,
                    surat_tugas_mahasiswa: form.querySelector('[name="surat_tugas_mahasiswa"]')?.files?.[0] || null,
                    sertifikat_juara: form.querySelector('[name="sertifikat_juara"]')?.files?.[0] || null,
                    penyerahan_penghargaan: form.querySelector('[name="penyerahan_penghargaan"]')?.files?.[0] || null,
                    bukti_keikutsertaan: form.querySelector('[name="bukti_keikutsertaan"]')?.files?.[0] || null,
                    url_kegiatan: form.querySelector('[name="url_kegiatan"]')?.files?.[0] || null,
                    foto_kegiatan: form.querySelector('[name="foto_kegiatan"]')?.files?.[0] || null,
                },
                biodata,
            };
        }

        function buildDokumenEntries(evidance) {
            const mapping = [
                ['Surat Tugas Dosen', evidance.surat_tugas_dosen],
                ['Surat Tugas Mahasiswa', evidance.surat_tugas_mahasiswa],
                ['Sertifikat Juara', evidance.sertifikat_juara],
                ['Penyerahan Penghargaan', evidance.penyerahan_penghargaan],
                ['Bukti Keikutsertaan', evidance.bukti_keikutsertaan],
                ['URL / Link Informasi Kegiatan', evidance.url_kegiatan],
                ['Foto Formal/Non Formal', evidance.foto_kegiatan],
            ];

            return mapping.filter(function ([, file]) { return !!file; }).map(function ([jenisDokumen, file], index) {
                return { index, jenisDokumen, file };
            });
        }

        async function postJson(url, body) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                              document.querySelector('input[name="_token"]')?.value;
            const headers = {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            };
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }

            const response = await fetch(url, {
                method: 'POST',
                headers: headers,
                body,
                credentials: 'same-origin',
            });

            const payload = await response.json().catch(() => ({}));
            if (!response.ok) {
                // Laravel ValidationException (422) returns { errors: { field: [msg] } }
                if (payload.errors) {
                    const messages = Object.values(payload.errors).flat();
                    throw new Error(messages.join(' | '));
                }
                throw new Error(payload.message || `Error ${response.status}: Permintaan gagal.`);
            }
            return payload;
        }

        async function rollbackPrestasi(prestasiId) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                                  document.querySelector('input[name="_token"]')?.value;
                await fetch(`/prestasi/laporan-prestasi/rollback/${prestasiId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });
            } catch (_) {
                // rollback best-effort, abaikan error
            }
        }

        async function submitPrestasi() {
            const payload = collectWizardPayload();

            if (!payload.nama_kompetisi || !payload.penyelenggara || !payload.capaian) {
                throw new Error('Lengkapi data langkah sebelumnya dulu sebelum mengirim.');
            }

            const dokumenEntries = buildDokumenEntries(payload.evidance);
            if (!dokumenEntries.length) {
                throw new Error('Minimal satu berkas evidence harus dipilih.');
            }

            const formData = new FormData();
            formData.append('nama_kompetisi', payload.nama_kompetisi);
            formData.append('penyelenggara', payload.penyelenggara);
            formData.append('tingkat', payload.tingkat);
            formData.append('capaian', payload.capaian);
            formData.append('kategori', payload.kategori);
            formData.append('mewakili_ormawa', payload.biodata.mewakili_ormawa === 'ya' ? 'ya' : 'tidak');

            dokumenEntries.forEach(function (entry) {
                formData.append(`dokumen[${entry.index}][jenis_dokumen]`, entry.jenisDokumen);
                formData.append(`dokumen[${entry.index}][file]`, entry.file);
            });

            const created = await postJson(form.dataset.endpoint, formData);
            const prestasiId = created?.data?.id_prestasi;

            if (!prestasiId) {
                return created;
            }

            const requests = [];

            if (payload.kategori === 'Kelompok') {
                payload.anggota.forEach(function (anggota) {
                    if (!anggota.nama || !anggota.nim || !anggota.prodi) return;

                    const anggotaData = new FormData();
                    anggotaData.append('nama', anggota.nama);
                    anggotaData.append('nim', anggota.nim);
                    anggotaData.append('prodi', anggota.prodi);
                    requests.push(postJson(`/api/v1/prestasi/${prestasiId}/anggota`, anggotaData));
                });
            }

            if (payload.dosen && payload.dosen.nama_dosen && payload.dosen.prodi_dosen) {
                const dosenData = new FormData();
                dosenData.append('nama', payload.dosen.nama_dosen || '');
                dosenData.append('nidn', payload.dosen.nidn_dosen || '');
                dosenData.append('nip', payload.dosen.nip_dosen || '');
                dosenData.append('prodi', payload.dosen.prodi_dosen || '');

                requests.push(postJson(`/api/v1/prestasi/${prestasiId}/dosen`, dosenData));
            }

            if (requests.length) {
                try {
                    await Promise.all(requests);
                } catch (err) {
                    // Salah satu request gagal → rollback hapus prestasi
                    await rollbackPrestasi(prestasiId);
                    throw err;
                }
            }

            return created;
        }

        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = 'Mengirim...';
            }

            try {
                const result = await submitPrestasi();
                showMessage('success', result.message || 'Prestasi berhasil dikirim.');
                localStorage.removeItem(storageKey);

                setTimeout(function () {
                    window.location.href = form.dataset.redirect;
                }, 900);
            } catch (error) {
                showMessage('error', error.message || 'Gagal mengirim prestasi.');
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = `
                        Kirim
                        <svg class="ml-1 h-4 w-4 -rotate-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                        </svg>
                    `;
                }
            }
        });

        function hydrate() {
            form.querySelectorAll('input[type="file"]').forEach(function (input) {
                input.addEventListener('change', function () {
                    const label = input.closest('label')?.querySelector('span');
                    if (label && this.files?.[0]?.name) {
                        label.textContent = `File dipilih: ${this.files[0].name}`;
                        label.classList.add('text-red-600', 'font-bold');
                    }
                });
            });
        }

        hydrate();
    });
    </script>
