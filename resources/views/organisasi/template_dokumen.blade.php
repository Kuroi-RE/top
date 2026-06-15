@extends('layouts.app')

@section('title', 'Template Dokumen')

@section('content')
<div class="mx-auto w-full max-w-5xl px-4 py-8">
    <div class="w-full rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Template Dokumen</h1>
            <p class="mt-2 text-sm text-gray-600">
                Halaman ini digunakan untuk melihat dan mengunduh template dokumen kegiatan.
            </p>
        </div>

        @if($templates->isEmpty())
            <div class="mt-6 rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 sm:p-8 text-center">
                <p class="text-sm font-medium text-gray-500">Belum ada template yang tersedia.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                @foreach($templates as $item)
                    <div class="p-5 rounded-2xl border border-gray-200 bg-white hover:border-red-200 transition shadow-sm hover:shadow-md flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-600 shrink-0">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-bold text-red-600 uppercase tracking-wider block">{{ $item['jenis_template'] ?? 'Template' }}</span>
                                <h3 class="text-sm font-semibold text-gray-800 truncate">{{ $item['nama_template'] ?? 'Template Dokumen' }}</h3>
                            </div>
                        </div>
                        <a href="{{ route('organisasi.template_download', $item['id_template']) }}" target="_blank" class="px-4 py-2 rounded-full text-xs font-bold bg-red-600 hover:bg-red-700 text-white transition shrink-0 shadow-sm flex items-center gap-1.5">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Unduh
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
