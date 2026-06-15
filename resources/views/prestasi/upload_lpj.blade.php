@extends('layouts.app')

@section('title', 'Upload LPJ Kegiatan')

@section('content')

<div class="min-h-screen bg-gray-100 flex justify-center px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Upload LPJ Kegiatan</h1>
            <p class="mt-1 text-sm text-gray-500">Upload Laporan Pertanggungjawaban untuk proposal yang sudah disetujui</p>
        </div>

        <!-- Alert Container -->
        <div id="alert-container" class="hidden mb-5"></div>

        <form id="lpj-form" class="mt-4 space-y-6 sm:mt-6" enctype="multipart/form-data">
            @csrf

            <!-- Proposal -->
            <div class="grid grid-cols-1 md:grid-cols-[160px_1fr] items-center gap-3 md:gap-6">
                <label for="id_proposal" class="text-sm font-medium text-gray-700">Proposal Disetujui <span class="text-red-500">*</span></label>
                <select
                    id="id_proposal"
                    name="id_proposal"
                    class="w-full h-11 rounded-full border border-gray-400 bg-white px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200"
                    required
                >
                    <option value="" selected disabled>Memuat daftar proposal disetujui...</option>
                </select>
            </div>

            <!-- Tanggal Upload -->
            <div class="grid grid-cols-1 md:grid-cols-[160px_1fr] items-center gap-3 md:gap-6">
                <label for="tanggal_upload" class="text-sm font-medium text-gray-700">Tanggal Upload <span class="text-red-500">*</span></label>
                <input
                    id="tanggal_upload"
                    type="date"
                    name="tanggal_upload"
                    value="{{ now()->format('Y-m-d') }}"
                    required
                    class="w-full h-11 rounded-full border border-gray-400 bg-white px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
            </div>

            <!-- File LPJ -->
            <div class="grid grid-cols-1 md:grid-cols-[160px_1fr] items-start gap-3 md:gap-6">
                <label class="pt-3 text-sm font-medium text-gray-700">File LPJ <span class="text-red-500">*</span></label>

                <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                    <input type="file" id="lpj-file" name="file_lpj" class="hidden" accept="application/pdf" required>

                    <div id="upload-area" class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                        </svg>
                        <span id="lpj-file-name" class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload File LPJ disini (PDF, maks. 5MB)</span>
                    </div>
                </label>
            </div>

            <!-- Button -->
            <div class="flex justify-end pt-4">
                <button type="submit" id="btn-kirim"
                    class="inline-flex min-w-[140px] items-center justify-center rounded-full bg-red-700 px-6 py-2.5 text-sm font-semibold text-white transition-all duration-300 hover:bg-red-800 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-200 focus:ring-2 focus:ring-red-200">
                    Kirim LPJ
                    <svg class="ml-2 h-4 w-4 -rotate-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
    'use strict';

    const CURRENT_USER_ID = {{ auth()->user()->id_user }};

    const form = document.getElementById('lpj-form');
    const selectProposal = document.getElementById('id_proposal');
    const fileInput = document.getElementById('lpj-file');
    const fileNameEl = document.getElementById('lpj-file-name');
    const uploadArea = document.getElementById('upload-area');
    const alertContainer = document.getElementById('alert-container');
    const btnKirim = document.getElementById('btn-kirim');

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

    // Dynamic file upload design
    if (fileInput && fileNameEl && uploadArea) {
        fileInput.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (file) {
                fileNameEl.textContent = 'Terpilih: ' + file.name;
                fileNameEl.classList.add('text-red-600', 'font-bold');
                uploadArea.style.borderColor = '#c1121f';
                uploadArea.style.background = '#fef2f2';
            } else {
                fileNameEl.textContent = 'Upload File LPJ disini (PDF, maks. 5MB)';
                fileNameEl.classList.remove('text-red-600', 'font-bold');
                uploadArea.style.borderColor = '#9ca3af';
                uploadArea.style.background = '#fcfcfc';
            }
        });
    }

    // ================= LOAD APPROVED PROPOSALS =================
    async function loadApprovedProposals() {
        try {
            const res = await window.axios.get('proposal?status=Disetujui');
            let proposals = res.data?.data || [];

            // Filter only user's own approved proposals
            proposals = proposals.filter(function (p) {
                return p.id_user == CURRENT_USER_ID;
            });

            selectProposal.innerHTML = '';
            
            if (proposals.length === 0) {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'Tidak ada proposal disetujui yang siap diunggah LPJ.';
                opt.disabled = true;
                opt.selected = true;
                selectProposal.appendChild(opt);
                btnKirim.disabled = true;
                btnKirim.classList.add('opacity-50', 'cursor-not-allowed');
                return;
            }

            // Populate select dropdown
            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = 'Pilih proposal yang sudah disetujui...';
            defaultOpt.disabled = true;
            defaultOpt.selected = true;
            selectProposal.appendChild(defaultOpt);

            proposals.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id_proposal;
                opt.textContent = `${p.nama_kegiatan} — TW${p.ajuan_triwulan || '-'}`;
                selectProposal.appendChild(opt);
            });

        } catch (error) {
            console.error('Failed to load proposals:', error);
            selectProposal.innerHTML = '<option value="" disabled selected>Gagal memuat proposal</option>';
            showAlert('Gagal mengambil daftar proposal dari server. Silakan muat ulang halaman.');
        }
    }

    // ================= SUBMIT LPJ FORM =================
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            hideAlert();

            const proposalId = selectProposal.value;
            if (!proposalId) {
                showAlert('Harap pilih proposal yang disetujui.');
                return;
            }

            const file = fileInput.files[0];
            if (!file) {
                showAlert('Harap pilih file LPJ (PDF) untuk diunggah.');
                return;
            }

            btnKirim.disabled = true;
            btnKirim.innerHTML = `
                Mengirim...
                <svg class="animate-spin ml-2 h-4 w-4 text-white inline-block" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;

            try {
                const fd = new FormData();
                fd.append('id_proposal', proposalId);
                fd.append('file_lpj', file);
                fd.append('tanggal_upload', document.getElementById('tanggal_upload').value);

                const res = await window.axios.post('lpj', fd, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });

                showAlert('LPJ Kegiatan berhasil diunggah dan sedang menunggu verifikasi.', 'success');

                setTimeout(() => {
                    window.location.href = '/prestasi';
                }, 1500);

            } catch (error) {
                console.error('LPJ Upload failed:', error);
                
                const msg = error.response?.data?.message 
                    || error.message 
                    || 'Gagal mengunggah LPJ kegiatan. Silakan coba lagi.';
                showAlert(msg);

                btnKirim.disabled = false;
                btnKirim.innerHTML = `
                    Kirim LPJ
                    <svg class="ml-2 h-4 w-4 -rotate-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                `;
            }
        });
    }

    // Load initial list of proposals
    loadApprovedProposals();
});
</script>
@endpush
