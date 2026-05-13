@extends('layouts.app')

@section('title', 'Input Publikasi Kegiatan Ormawa')

@section('content')

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
            <!-- Header -->
            <div class="mb-5">
                <h1 class="text-2xl font-semibold text-gray-800">Input Publikasi Kegiatan Ormawa</h1>
            </div>

            @php
                $fields = [
                    ['label' => 'Judul', 'name' => 'judul', 'type' => 'text'],
                    ['label' => 'Ormawa', 'name' => 'ormawa', 'type' => 'text'],
                    ['label' => 'Caption', 'name' => 'caption', 'type' => 'text'],
                    ['label' => 'Link', 'name' => 'link', 'type' => 'text'],
                ];
            @endphp

            <form id="publikasi-form" method="POST" enctype="multipart/form-data" class="mt-4 space-y-6 sm:mt-6">
                @csrf

                <!-- Input Fields -->
                @foreach ($fields as $field)
                    <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-center gap-3 md:gap-6">
                        <label for="{{ $field['name'] }}" class="text-sm font-medium text-gray-700">
                            {{ $field['label'] }}
                        </label>

                        <input
                            id="{{ $field['name'] }}"
                            type="{{ $field['type'] }}"
                            name="{{ $field['name'] }}"
                            class="w-full h-10 md:h-11 rounded-full border border-gray-400 bg-white px-4 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                    </div>
                @endforeach

                <!-- Upload Poster/Gambar -->
                <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
                    <label class="pt-3 text-sm font-medium text-gray-700">Poster/Gambar Pendukung</label>

                    <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                        <input id="publikasi-poster" type="file" name="poster" class="hidden" accept="image/*">

                        <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                            </svg>
                            <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload gambar disini</span>
                        </div>
                    </label>
                </div>

                <!-- Button -->
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
<script>
    (function () {
        const form = document.getElementById('publikasi-form');
        const submitBtn = document.getElementById('publikasi-submit');
        const posterInput = document.getElementById('publikasi-poster');

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
                } else {
                    textSpan.textContent = 'Upload gambar disini';
                    container.style.borderColor = '#9ca3af';
                    container.style.backgroundColor = '#fcfcfc';
                    if (svgIcon) svgIcon.classList.remove('text-red-500');
                    textSpan.classList.remove('text-red-600', 'font-bold');
                }
            });
        }

        if (form) {
            form.addEventListener('submit', function (e) {
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Mengirim...
                    `;
                }
            });
        }
    })();
</script>
@endpush
