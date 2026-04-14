@extends('layouts.app')

@section('title', 'Input Prestasi - Evidence')

@section('content')
<div class="laporan-prestasi-page min-h-screen bg-gray-100 flex justify-center items-start px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold leading-tight text-gray-800 sm:text-3xl">Input Prestasi - Evidence</h1>
        </div>

        <form method="POST" action="#" class="mt-4 sm:mt-8" enctype="multipart/form-data">
            @csrf

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
                            <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidance disini</span>
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
                            <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidance disini</span>
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
                            <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidance disini</span>
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
                            <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidance disini</span>
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
                            <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload Evidance disini</span>
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
                            <span class="text-sm font-medium text-gray-600 transition group-hover:text-red-700">Upload LPJ Kegiatan disini</span>
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
                <button type="submit" class="inline-flex min-w-[140px] items-center justify-center rounded-full bg-red-700 px-6 py-2 text-sm font-medium text-white transition-all duration-300 hover:bg-red-800 hover:-translate-y-1 hover:shadow-lg hover:shadow-red-200 focus:ring-2 focus:ring-red-200">
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
