@extends('layouts.app')

@section('title', 'Template Dokumen Prestasi')

@section('content')
<div class="mx-auto w-full max-w-5xl">
    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
        <h1 class="text-2xl font-bold text-gray-800">Template Dokumen</h1>
        <p class="mt-2 text-sm text-gray-600">
            Halaman ini digunakan untuk melihat dan mengunduh template dokumen kegiatan.
        </p>

        <div class="mt-6 rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center">
            <p class="text-sm font-medium text-gray-700">Belum ada template yang tersedia.</p>
        </div>
    </div>
</div>
@endsection
