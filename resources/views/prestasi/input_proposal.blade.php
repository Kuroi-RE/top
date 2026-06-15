@extends('layouts.app')

@section('title', 'Input Proposal Ajuan Dana Prestasi')

@section('content')

<div class="space-y-6 px-4 py-6 sm:px-6 lg:px-8">
    <div class="rounded-2xl border border-red-200 bg-gradient-to-r from-red-50 to-white p-5 shadow-sm">
        <h1 class="text-2xl font-semibold text-gray-800">Input Proposal Ajuan Dana Prestasi</h1>
        <p class="mt-1 text-sm text-gray-500">Isi data proposal di bawah ini untuk mengajukan dana prestasi.</p>
    </div>

    <!-- Alert Container -->
    <div id="alert-container" class="hidden"></div>

    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="p-6 sm:p-8 lg:p-10">

            @php
                $fields = [
                    ['label' => 'Nama Unit/Tim/Komunitas', 'name' => 'nama_unit', 'type' => 'text', 'placeholder' => 'Misal: Tim Robotika TOP', 'required' => true],
                    ['label' => 'Nama Mahasiswa (PIC)', 'name' => 'nama_mahasiswa', 'type' => 'text', 'placeholder' => 'Nama PIC proposal', 'required' => true],
                    ['label' => 'Nomor Whatsapp PIC', 'name' => 'nomor_whatsapp', 'type' => 'text', 'placeholder' => 'Misal: 081234567890', 'required' => true],
                    ['label' => 'Nama Kegiatan / Kompetisi', 'name' => 'nama_kegiatan', 'type' => 'text', 'placeholder' => 'Nama kegiatan/event', 'required' => true],
                    ['label' => 'Penyelenggara Event', 'name' => 'penyelenggara_event', 'type' => 'text', 'placeholder' => 'Penyelenggara kompetisi', 'required' => true],
                    ['label' => 'Tanggal Pelaksanaan', 'name' => 'tanggal_pelaksanaan', 'type' => 'date', 'placeholder' => '', 'required' => true],
                    ['label' => 'Total Ajuan Anggaran', 'name' => 'total_anggaran', 'type' => 'text', 'placeholder' => 'Misal: 1500000', 'required' => true],
                    ['label' => 'Nama Bank Penerima', 'name' => 'nama_bank', 'type' => 'text', 'placeholder' => 'Misal: Bank Mandiri / BNI', 'required' => true],
                    ['label' => 'Nomor Rekening', 'name' => 'nomor_rekening', 'type' => 'text', 'placeholder' => 'Nomor rekening PIC', 'required' => true],
                    ['label' => 'Atas Nama Rekening', 'name' => 'atas_nama_rekening', 'type' => 'text', 'placeholder' => 'Nama pemilik rekening', 'required' => true],
                ];
            @endphp

            <form id="proposal-form" class="mt-4 space-y-6 sm:mt-6" enctype="multipart/form-data">
                @csrf

                <!-- Input Fields -->
                @foreach ($fields as $field)
                    <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-center gap-3 md:gap-6">
                        <label for="{{ $field['name'] }}" class="text-sm font-medium text-gray-700">
                            {{ $field['label'] }} @if($field['required']) <span class="text-red-500">*</span> @endif
                        </label>

                        <input
                            id="{{ $field['name'] }}"
                            type="{{ $field['type'] }}"
                            name="{{ $field['name'] }}"
                            placeholder="{{ $field['placeholder'] }}"
                            @if($field['required']) required @endif
                            class="w-full h-11 rounded-full border border-gray-400 bg-white px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                    </div>
                @endforeach

                <!-- Upload Proposal -->
                <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-start gap-3 md:gap-6">
                    <label class="pt-3 text-sm font-medium text-gray-700">File Proposal <span class="text-red-500">*</span></label>

                    <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                        <input type="file" id="proposal-input" name="proposal" class="hidden" accept="application/pdf" required>

                        <div id="upload-area" class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                            </svg>
                            <span id="filename-display" class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Proposal disini</span>
                            <p class="text-xs text-gray-500">Hanya menerima ekstensi .pdf (Maks. 5MB)</p>
                        </div>
                    </label>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" id="btn-kirim"
                        class="inline-flex min-w-[140px] items-center justify-center rounded-full bg-red-700 px-6 py-2.5 text-sm font-semibold text-white transition-all duration-300 hover:bg-red-800 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-200 focus:ring-2 focus:ring-red-200">
                        Kirim Proposal
                        <svg class="ml-2 h-4 w-4 -rotate-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const form = document.getElementById('proposal-form');
    const alertContainer = document.getElementById('alert-container');
    const btnKirim = document.getElementById('btn-kirim');

    // File upload change handler
    const fileInput = document.getElementById('proposal-input');
    const uploadArea = document.getElementById('upload-area');
    const filenameDisplay = document.getElementById('filename-display');

    if (fileInput && uploadArea && filenameDisplay) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const name = this.files[0].name;
                filenameDisplay.textContent = 'Terpilih: ' + name;
                filenameDisplay.classList.add('text-red-600', 'font-bold');
                uploadArea.style.borderColor = '#c1121f';
                uploadArea.style.background = '#fef2f2';
            } else {
                filenameDisplay.textContent = 'Upload Proposal disini';
                filenameDisplay.classList.remove('text-red-600', 'font-bold');
                uploadArea.style.borderColor = '#9ca3af';
                uploadArea.style.background = '#fcfcfc';
            }
        });
    }

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

    // Intercept form submit and call API
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            hideAlert();

            // Disable submit button
            btnKirim.disabled = true;
            btnKirim.innerHTML = `
                Memproses...
                <svg class="animate-spin ml-2 h-4 w-4 text-white inline-block" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;

            try {
                const totalAnggaranRaw = document.getElementById('total_anggaran').value;
                // Sanitize total anggaran to a purely numeric float string
                const totalAnggaran = parseFloat(totalAnggaranRaw.replace(/[^0-9]/g, '')) || 0;

                if (totalAnggaran < 100000) {
                    throw new Error('Total ajuan anggaran minimal adalah Rp 100.000.');
                }

                // File validation
                const file = fileInput.files[0];
                if (!file) {
                    throw new Error('Harap unggah berkas proposal (PDF).');
                }

                // Construct multipart form data matching POST /api/v1/proposal
                const fd = new FormData();
                fd.append('ajuan_triwulan', 'I'); // Default to Triwulan I
                fd.append('risiko_proposal', 'Sedang'); // Default to Sedang
                fd.append('no_telepon', document.getElementById('nomor_whatsapp').value);
                fd.append('nama_kegiatan', document.getElementById('nama_kegiatan').value);
                fd.append('waktu_kegiatan', document.getElementById('tanggal_pelaksanaan').value);
                fd.append('tempat_kegiatan', document.getElementById('penyelenggara_event').value);
                fd.append('besar_ajuan', totalAnggaran);
                fd.append('nomor_rekening', document.getElementById('nomor_rekening').value);
                fd.append('nama_rekening', document.getElementById('atas_nama_rekening').value);
                fd.append('nama_bank', document.getElementById('nama_bank').value);
                fd.append('honor_pelatih', 'Tidak'); // Default to Tidak
                fd.append('file', file);

                const res = await window.axios.post('proposal', fd, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });

                showAlert('Proposal dana prestasi berhasil diajukan dan sedang menunggu verifikasi.', 'success');

                // Redirect after success
                setTimeout(() => {
                    window.location.href = '/prestasi';
                }, 1500);

            } catch (error) {
                console.error('Proposal submission failed:', error);
                
                // Extract error message
                const msg = error.response?.data?.message 
                    || error.message 
                    || 'Gagal mengirim proposal kegiatan. Silakan coba lagi.';
                showAlert(msg);

                // Reset button state
                btnKirim.disabled = false;
                btnKirim.innerHTML = `
                    Kirim Proposal
                    <svg class="ml-2 h-4 w-4 -rotate-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                `;
            }
        });
    }
});
</script>
@endpush
