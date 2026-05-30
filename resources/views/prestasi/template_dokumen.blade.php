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

        <div id="loading-templates" class="flex flex-col items-center justify-center py-12">
            <svg class="animate-spin h-10 w-10 text-red-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm font-medium text-gray-500">Memuat template dokumen...</span>
        </div>

        <div id="empty-templates" class="hidden mt-6 rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 sm:p-8 text-center">
            <p class="text-sm font-medium text-gray-500">Belum ada template yang tersedia.</p>
        </div>

        <div id="template-grid" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
            <!-- populated dynamically -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    if (!window.axios) return;

    const loadingEl = document.getElementById('loading-templates');
    const emptyEl = document.getElementById('empty-templates');
    const gridEl = document.getElementById('template-grid');

    window.axios.get('template')
        .then(function(res) {
            loadingEl.style.display = 'none';
            const list = res.data?.data || [];

            if (list.length === 0) {
                emptyEl.classList.remove('hidden');
                return;
            }

            gridEl.classList.remove('hidden');
            gridEl.innerHTML = '';

            list.forEach(function(item) {
                const card = document.createElement('div');
                card.className = 'p-5 rounded-2xl border border-gray-200 bg-white hover:border-red-200 transition shadow-sm hover:shadow-md flex items-center justify-between gap-4';

                card.innerHTML = `
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-600 shrink-0">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] font-bold text-red-600 uppercase tracking-wider block">${item.jenis_template || 'Template'}</span>
                            <h3 class="text-sm font-semibold text-gray-800 truncate">${item.nama_template || 'Template Dokumen'}</h3>
                        </div>
                    </div>
                    <a href="/api/v1/template/${item.id_template}/download" target="_blank" class="px-4 py-2 rounded-full text-xs font-bold bg-red-600 hover:bg-red-700 text-white transition shrink-0 shadow-sm flex items-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Unduh
                    </a>
                `;
                gridEl.appendChild(card);
            });
        })
        .catch(function(err) {
            console.error('Failed to load templates:', err);
            loadingEl.style.display = 'none';
            emptyEl.classList.remove('hidden');
            emptyEl.querySelector('p').textContent = 'Gagal memuat template dokumen dari server.';
            emptyEl.querySelector('p').className = 'text-sm font-medium text-red-600';
        });
});
</script>
@endpush
