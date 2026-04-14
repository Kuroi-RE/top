@extends('layouts.app')

@section('title', 'Input Proposal Kegiatan')

@section('content')

<div class="min-h-screen bg-gray-100 flex justify-center px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Input Proposal Kegiatan</h1>
    </div>

    @php
        $fields = [
            ['label' => 'Nama Unit/Tim/Komunitas', 'name' => 'nama_unit', 'type' => 'text'],
            ['label' => 'Nama Mahasiswa', 'name' => 'nama_mahasiswa', 'type' => 'text'],
            ['label' => 'Nomor Whatsapp', 'name' => 'nomor_whatsapp', 'type' => 'text'],
            ['label' => 'Nama Kegiatan', 'name' => 'nama_kegiatan', 'type' => 'text'],
            ['label' => 'Penyelenggara Event', 'name' => 'penyelenggara_event', 'type' => 'text'],
            ['label' => 'Tanggal Pelaksanaan', 'name' => 'tanggal_pelaksanaan', 'type' => 'date'],
            ['label' => 'Total Ajuan Anggaran', 'name' => 'total_anggaran', 'type' => 'text'],
            ['label' => 'Nama Bank', 'name' => 'nama_bank', 'type' => 'text'],
            ['label' => 'Atas Nama Rekening', 'name' => 'atas_nama_rekening', 'type' => 'text'],
        ];
    @endphp

    <form method="POST" enctype="multipart/form-data" class="mt-4 space-y-6 sm:mt-6">
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
                    class="w-full h-11 md:h-12 rounded-full border border-gray-400 bg-white px-5 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200">
            </div>
        @endforeach

        <!-- Upload Proposal -->
        <div class="grid grid-cols-1 md:grid-cols-[120px_1fr] items-start gap-3 md:gap-6">
            <label class="pt-3 text-sm font-medium text-gray-700">Proposal</label>

            <label class="block w-full cursor-pointer rounded-xl border border-gray-400 bg-white p-2 hover:bg-gray-50 transition group">
                <input type="file" name="proposal" class="hidden" accept="application/pdf">

                <div class="flex flex-col items-center justify-center bg-[#fcfcfc] min-h-[84px] p-2.5 gap-1 text-center box-border transition group-hover:border-red-500" style="border: 1px dashed #9ca3af; border-radius: 12px;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 transition group-hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V8.25m0 0l-3 3m3-3l3 3M3 16.5v.75A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-.75" />
                    </svg>
                    <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Proposal disini</span>
                    <p class="text-xs text-gray-500">Hanya menerima ekstensi .pdf</p>
                </div>
            </label>
        </div>

        <!-- Button -->
        <div class="flex justify-end pt-4">
            <button type="submit"
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
