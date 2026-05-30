@extends('layouts.app')

@section('title', 'Input Template Dokumen')

@section('content')
<style>
    .template-shell {
        max-width: 800px;
        margin: 0 auto;
        animation: fade-in 0.6s ease-out both;
    }

    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .premium-card {
        background: linear-gradient(135deg, #ffffff 0%, #fbfdff 100%);
        border-radius: 32px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.04);
        border: 1px solid rgba(2,6,23,0.05);
        padding: 40px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 8px;
        margin-left: 4px;
    }

    .form-input-premium {
        width: 100%;
        background: #f8fafc;
        border: 1px solid rgba(2,6,23,0.08);
        border-radius: 16px;
        padding: 14px 20px;
        transition: all 0.2s ease;
        outline: none;
        color: #1e293b;
        font-weight: 500;
    }

    .form-input-premium:focus {
        background: #ffffff;
        border-color: #c1121f;
        box-shadow: 0 0 0 4px rgba(193, 18, 31, 0.08);
    }

    /* Modern Upload Area */
    .upload-container {
        position: relative;
        width: 100%;
    }

    .upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 20px;
        padding: 32px;
        text-align: center;
        background: #f8fafc;
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .upload-area:hover {
        border-color: #c1121f;
        background: rgba(193, 18, 31, 0.02);
    }

    .upload-icon {
        width: 48px;
        height: 48px;
        background: #fff;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #c1121f;
        box-shadow: 0 8px 20px rgba(0,0,0,0.04);
        margin-bottom: 4px;
    }

    .btn-submit-premium {
        width: 100%;
        background: linear-gradient(135deg, #c1121f 0%, #780116 100%);
        color: white;
        padding: 16px;
        border-radius: 18px;
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(193, 18, 31, 0.2);
        border: none;
        cursor: pointer;
    }

    .btn-submit-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(193, 18, 31, 0.3);
    }
</style>

<div class="template-shell">
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Tambah Template Baru</h1>
        <p class="text-slate-500 mt-2">Unggah berkas panduan atau formulir untuk digunakan Ormawa</p>
    </div>

    <div class="premium-card">
        <form id="template-form" method="POST" action="#" enctype="multipart/form-data" data-endpoint="/api/v1/template" data-redirect="{{ route('admin.template_proposal') }}">
            @csrf



            <div class="form-group">
                <label for="jenis_template" class="form-label">Jenis Template</label>
                <select
                    id="jenis_template"
                    name="jenis_template"
                    class="form-input-premium"
                    required
                >
                    <option value="" selected disabled>Pilih jenis template</option>
                    <option value="proposal kegiatan resiko tinggi">Proposal Kegiatan Resiko Tinggi</option>
                    <option value="proposal kegiatan resiko sedang/rendah">Proposal Kegiatan Resiko Sedang/Rendah</option>
                    <option value="proposal prestasi">Proposal Prestasi</option>
                    <option value="lpj kegiatan resiko tinggi">LPJ Kegiatan Resiko Tinggi</option>
                    <option value="lpj kegiatan resiko sedang/rendah">LPJ Kegiatan Resiko Sedang/Rendah</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Berkas Dokumen</label>
                <div class="upload-container">
                    <input type="file" id="dokumen" name="dokumen" class="hidden" required onchange="updateFileName(this)">
                    <label for="dokumen" class="upload-area" id="drop-area">
                        <div class="upload-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-slate-900 font-bold text-lg" id="file-name-display">Klik untuk pilih berkas</span>
                            <span class="text-slate-400 text-sm">Seret & letakkan file atau klik area ini (Maks. 10MB)</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="mt-10 flex gap-4">
                <a href="{{ route('admin.template_proposal') }}" class="flex-1 px-6 py-4 bg-slate-100 text-slate-600 font-bold rounded-18px text-center hover:bg-slate-200 transition-colors" style="border-radius: 18px;">
                    Batal
                </a>
                <button type="submit" class="flex-1 btn-submit-premium">
                    <span>Simpan Template</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Help Card -->
    <div class="mt-8 bg-blue-50/50 border border-blue-100 rounded-2xl p-6 flex gap-4 items-start">
        <div class="bg-blue-100 p-2 rounded-xl text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <h4 class="font-bold text-blue-900">Ketentuan Berkas</h4>
            <p class="text-sm text-blue-800/80 leading-relaxed mt-1">
                Gunakan format yang umum digunakan seperti <strong>.pdf, .docx,</strong> atau <strong>.zip</strong> untuk kumpulan dokumen. Pastikan nama dokumen deskriptif agar memudahkan Ormawa mencarinya.
            </p>
        </div>
    </div>
</div>

<script>
    function updateFileName(input) {
        const display = document.getElementById('file-name-display');
        if (input.files && input.files[0]) {
            display.textContent = input.files[0].name;
            display.classList.add('text-red-600');
        } else {
            display.textContent = 'Klik untuk pilih berkas';
            display.classList.remove('text-red-600');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('template-form');
        if (!form) return;

        const endpoint = form.dataset.endpoint;
        const redirectUrl = form.dataset.redirect;
        const submitButton = form.querySelector('button[type="submit"]');

        function showFlash(type, message) {
            const existing = document.getElementById('template-flash');
            if (existing) existing.remove();

            const flash = document.createElement('div');
            flash.id = 'template-flash';
            flash.className = 'fixed left-1/2 top-6 z-50 w-[min(92vw,640px)] -translate-x-1/2 rounded-2xl border px-4 py-3 text-sm shadow-lg';
            flash.setAttribute('role', 'status');
            flash.setAttribute('aria-live', 'polite');

            if (type === 'success') {
                flash.classList.add('border-green-200', 'bg-green-50', 'text-green-800');
            } else {
                flash.classList.add('border-red-200', 'bg-red-50', 'text-red-800');
            }

            flash.textContent = message;
            document.body.appendChild(flash);

            setTimeout(function () {
                flash.remove();
            }, 2600);
        }

        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<span>Menyimpan...</span>';
            }

            try {
                const formData = new FormData(form);
                
                const fileInput = document.getElementById('dokumen');
                let fileName = '';
                if (fileInput.files && fileInput.files[0]) {
                    const originalName = fileInput.files[0].name;
                    fileName = originalName.substring(0, originalName.lastIndexOf('.')) || originalName;
                }
                
                const selectEl = document.getElementById('jenis_template');
                const jenisText = selectEl.options[selectEl.selectedIndex].text;
                
                const templateName = `${jenisText} - ${fileName}`;
                formData.set('nama_template', templateName);
                formData.set('file', formData.get('dokumen'));

                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                    credentials: 'same-origin',
                });

                const payload = await response.json().catch(() => ({}));

                if (!response.ok) {
                    showFlash('error', payload.message || 'Gagal menyimpan template dokumen.');

                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.innerHTML = `
                            <span>Simpan Template</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        `;
                    }

                    return;
                }

                showFlash('success', payload.message || 'Template dokumen berhasil dibuat.');
                setTimeout(function () {
                    window.location.href = redirectUrl;
                }, 900);
            } catch (error) {
                showFlash('error', 'Terjadi gangguan saat mengirim template dokumen.');

                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = `
                        <span>Simpan Template</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    `;
                }
            }
        });
    });
</script>
@endsection
