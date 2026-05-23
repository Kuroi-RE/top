@extends('layouts.app')

@section('title', 'Template Dokumen Prestasi')

@section('content')
<div class="mx-auto w-full max-w-5xl px-4 py-8">
    <div class="w-full rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Template Dokumen</h1>
            <p class="mt-2 text-sm text-gray-600">
                Halaman ini digunakan untuk melihat dan mengunduh template dokumen kegiatan.
            </p>
        </div>

        <div class="mt-6 rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 sm:p-8 text-center">
            <p class="text-sm font-medium text-gray-500">Belum ada template yang tersedia.</p>
        </div>
    </div>
</div>
@endsection
