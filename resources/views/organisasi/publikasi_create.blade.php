@extends('layouts.app')

@section('title', 'Input Publikasi Kegiatan Ormawa')

@section('content')

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<div class="min-h-screen bg-gray-100 flex justify-center px-4 py-8">
    <div class="w-full max-w-5xl space-y-6">
        <!-- Kuota Informasi -->
        @php
            $maxQuota = 3;
            $remaining = max(0, $maxQuota - $weekCount);
        @endphp
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-base font-semibold text-gray-800">Kuota Informasi</p>
                    <p class="mt-1 text-sm text-gray-500">Kegiatan Mingguan</p>
                </div>
                <div class="text-base font-semibold text-red-600">
                    <span>{{ $weekCount }}</span>/<span>{{ $maxQuota }}</span>
                </div>
            </div>
            <div class="mt-3">
                <div class="h-2 w-full rounded-full bg-gray-200">
                    <div class="h-2 rounded-full bg-red-600" style="width: {{ ($weekCount / $maxQuota) * 100 }}%"></div>
                </div>
                <p class="mt-3 text-xs text-gray-500">
                    {{ $remaining > 0 ? "Tersisa $remaining slot pengunggahan poster kegiatan minggu ini." : "Kuota minggu ini sudah habis." }}
                </p>
                @if($remaining <= 0)
                    <p class="mt-1 text-xs font-semibold text-red-600">Kuota minggu ini sudah habis.</p>
                @endif
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">
            <div class="mb-5">
                <h1 class="text-2xl font-semibold text-gray-800">Input Publikasi Kegiatan Ormawa</h1>
            </div>

            @if($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="publikasi-form" method="POST" enctype="multipart/form-data" class="mt-4 space-y-6 sm:mt-6">
                @csrf

                {{-- Judul --}}
                <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-center gap-3 md:gap-6">
                    <label for="judul" class="text-sm font-medium text-gray-700">Judul</label>
                    <input id="judul" type="text" name="judul" value="{{ old('judul') }}"
                        class="w-full h-10 md:h-11 rounded-full border border-gray-400 bg-white px-4 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                </div>

                {{-- Ormawa --}}
                <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-center gap-3 md:gap-6">
                    <label for="ormawa" class="text-sm font-medium text-gray-700">Ormawa</label>
                    <div class="relative">
                        <input id="ormawa" type="text" name="ormawa"
                            value="{{ old('ormawa', $ormawaName) }}"
                            readonly
                            class="w-full h-10 md:h-11 rounded-full border border-gray-300 bg-gray-50 px-4 text-sm text-gray-600 outline-none cursor-not-allowed select-none">
                        <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                    </div>
                </div>

                {{-- Caption --}}
                <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                    <label for="caption" class="pt-2 text-sm font-medium text-gray-700">
                        Caption
                        <span class="block text-xs text-gray-400 font-normal mt-0.5">Deskripsi singkat</span>
                    </label>
                    <textarea id="caption" name="caption" rows="3"
                        class="w-full rounded-2xl border border-gray-400 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 resize-none"
                        placeholder="Tulis deskripsi singkat kegiatan...">{{ old('caption') }}</textarea>
                </div>

                {{-- Content (Quill editor) --}}
                <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                    <label class="pt-2 text-sm font-medium text-gray-700">
                        Konten Artikel
                        <span class="block text-xs text-gray-400 font-normal mt-0.5">Detail lengkap kegiatan</span>
                    </label>
                    <div>
                        <div id="content-editor" class="rounded-2xl border border-gray-400 bg-white" style="min-height: 220px;"></div>
                        <input type="hidden" name="content" id="content-input">
                        <p class="mt-1.5 text-xs text-gray-400">Gunakan toolbar untuk memformat teks, tambah gambar, atau buat daftar.</p>
                    </div>
                </div>

                {{-- Poster --}}
                <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                    <label class="pt-3 text-sm font-medium text-gray-700">Poster/Gambar Pendukung</label>
                    <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                        <input id="publikasi-poster" type="file" name="poster" class="hidden" accept="image/*">
                        <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                            </svg>
                            <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload gambar disini</span>
                        </div>
                    </label>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end pt-4">
                    <button id="publikasi-submit" type="submit"
                        class="inline-flex min-w-[140px] items-center justify-center rounded-full bg-red-700 px-6 py-2 text-sm font-medium text-white transition-all duration-300 hover:bg-red-800 hover:-translate-y-1 hover:shadow-lg hover:shadow-red-200 focus:ring-2 focus:ring-red-200">
                        Kirim
                        <svg class="ml-1 h-4 w-4 -rotate-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
(function () {
    // Quill editor init
    const quill = new Quill('#content-editor', {
        theme: 'snow',
        placeholder: 'Tulis konten artikel kegiatan di sini...',
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ color: [] }, { background: [] }],
                [{ list: 'ordered' }, { list: 'bullet' }],
                [{ align: [] }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Sync quill content to hidden input before submit
    const form = document.getElementById('publikasi-form');
    const contentInput = document.getElementById('content-input');
    const submitBtn = document.getElementById('publikasi-submit');
    const posterInput = document.getElementById('publikasi-poster');

    // Restore old content if validation failed
    const oldContent = @json(old('content'));
    if (oldContent) {
        quill.root.innerHTML = oldContent;
    }

    if (posterInput) {
        posterInput.addEventListener('change', function() {
            const file = this.files[0];
            const container = this.nextElementSibling;
            const textSpan = container.querySelector('span');
            const svgIcon = container.querySelector('svg');
            if (file) {
                textSpan.textContent = 'Terpilih: ' + file.name;
                container.style.borderColor = '#c1121f';
                container.style.backgroundColor = '#fef2f2';
                if (svgIcon) svgIcon.classList.add('text-red-500');
                textSpan.classList.add('text-red-600', 'font-bold');
            }
        });
    }

    if (form) {
        form.addEventListener('submit', function (e) {
            contentInput.value = quill.root.innerHTML;
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Mengirim...`;
            }
        });
    }
})();
</script>
@endpush
