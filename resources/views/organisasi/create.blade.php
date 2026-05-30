@extends('layouts.app')

@section('title', 'Input Proposal Ajuan Dana Kegiatan')

@section('fullpage')
@endsection

@section('content')

<div class="min-h-screen bg-gray-100 flex justify-center px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Input Proposal Ajuan Dana Kegiatan</h1>
    </div>

    @php
        $fields = [
            ['label' => 'No PIC', 'name' => 'no_pic', 'type' => 'text'],
            ['label' => 'Nama Kegiatan', 'name' => 'nama_kegiatan', 'type' => 'text'],
            ['label' => 'Mulai Kegiatan', 'name' => 'mulai_kegiatan', 'type' => 'date'],
            ['label' => 'Tempat Kegiatan', 'name' => 'tempat_kegiatan', 'type' => 'text'],
            ['label' => 'Besar Ajuan', 'name' => 'besar_ajuan', 'type' => 'number'],
            ['label' => 'Nomor Rekening', 'name' => 'nomor_rekening', 'type' => 'text'],
            ['label' => 'Nama Bank', 'name' => 'nama_bank', 'type' => 'text'],
            ['label' => 'Nama Rekening', 'name' => 'nama_rekening', 'type' => 'text'],
        ];
    @endphp

    <form action="{{ route('organisasi.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-6 sm:mt-6">
        @csrf

        @if ($errors->any())
            <div class="rounded-xl bg-red-50 p-4 border border-red-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat beberapa kesalahan:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Ajuan TW -->
        <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
            <label class="pt-3 text-sm font-medium text-gray-700">Ajuan TW</label>
            <div class="flex flex-wrap items-center gap-5 pt-2">
                @foreach (['I', 'II', 'III', 'IV'] as $tw)
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="ajuan_tw" value="{{ $tw }}" {{ old('ajuan_tw') == $tw ? 'checked' : '' }}
                            class="h-5 w-5 border-gray-400 accent-red-600">
                        <span>{{ $tw }}</span>
                    </label>
                @endforeach
                @error('ajuan_tw') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <input type="hidden" name="category" value="Ormawa">

        <!-- Resiko Proposal -->
        <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
            <label class="pt-3 text-sm font-medium text-gray-700">Resiko Proposal</label>
            <div class="flex flex-wrap items-center gap-5 pt-2">
                @foreach (['Rendah', 'Sedang', 'Tinggi'] as $r)
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="resiko_proposal" value="{{ $r }}" {{ old('resiko_proposal') == $r ? 'checked' : '' }}
                            class="h-5 w-5 border-gray-400 accent-red-600">
                        <span>{{ $r }}</span>
                    </label>
                @endforeach
                @error('resiko_proposal') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

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
                    value="{{ old($field['name']) }}"
                    class="w-full h-10 md:h-11 rounded-full border @error($field['name']) border-red-500 @else border-gray-400 @enderror bg-white px-4 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
                @error($field['name']) <p class="text-xs text-red-600 mt-1 ml-4">{{ $message }}</p> @enderror
            </div>
        @endforeach

        <!-- Honor Pelatih -->
        <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] items-start gap-3 md:gap-6">
            <label class="text-sm font-medium leading-5 text-gray-700">
                Apakah pengajuan dana untuk honor pelatih?
            </label>
            <div class="flex flex-wrap items-center gap-5 pt-1">
                <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="radio" name="honor_pelatih" value="Ya" {{ old('honor_pelatih') == 'Ya' ? 'checked' : '' }}
                        class="h-5 w-5 border-gray-400 accent-red-600">
                    <span>Ya</span>
                </label>
                <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="radio" name="honor_pelatih" value="Tidak" {{ old('honor_pelatih') == 'Tidak' ? 'checked' : '' }}
                        class="h-5 w-5 border-gray-400 accent-red-600">
                    <span>Tidak</span>
                </label>
                @error('honor_pelatih') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Upload Proposal -->
        <div class="grid grid-cols-1 md:grid-cols-[120px_1fr] items-start gap-3 md:gap-6">
            <label class="pt-3 text-sm font-medium text-gray-700">Proposal</label>

            <label class="block w-full cursor-pointer rounded-xl border @error('proposal') border-red-500 @else border-gray-400 @enderror bg-white p-2 hover:bg-gray-50 transition group">
                <input type="file" name="proposal" class="hidden" accept="application/pdf">

                <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed @error('proposal') #ef4444 @else #9ca3af @enderror; border-radius: 12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                        </svg>
                    <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Proposal disini</span>
                    <p class="text-xs text-gray-500">Hanya menerima ekstensi .pdf</p>
                </div>
            </label>
            @error('proposal') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Button -->
        <div class="flex justify-end pt-4">
            <button type="submit" id="btn-submit"
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.querySelector('input[type="file"]');
    const uploadArea = fileInput?.parentElement?.querySelector('.flex-col');
    const filenameDisplay = uploadArea?.querySelector('span');

    if (fileInput && uploadArea && filenameDisplay) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const name = this.files[0].name;
                filenameDisplay.textContent = 'Terpilih: ' + name;
                filenameDisplay.classList.add('text-red-600', 'font-bold');
                uploadArea.style.borderColor = '#c1121f';
                uploadArea.style.background = '#fef2f2';
            }
        });
    }

    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            const btn = document.getElementById('btn-submit');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = 'Mengirim...';
                btn.classList.add('opacity-70', 'cursor-not-allowed');
            }
        });
    }
});
</script>
@endpush