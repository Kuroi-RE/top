@extends('layouts.app')

@section('title', 'Laporan Kegiatan (LPJ)')

@section('content')
<div class="min-h-screen bg-gray-50/50 px-4 py-8">
    <div class="mx-auto max-w-7xl">
        
        <!-- Header Section -->
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Kegiatan (LPJ)</h1>
                <p class="mt-1 text-sm text-gray-500">Kelola dan pantau status laporan pertanggungjawaban kegiatan Anda.</p>
            </div>
        </div>

        <!-- Table Card -->
        <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">TW</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Kegiatan</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status LPJ</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($proposals as $item)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-xs font-bold text-gray-600">
                                        {{ $item['tw'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-900">{{ $item['nama_kegiatan'] }}</span>
                                        @if($item['lpj_status'] == 'Revisi' && $item['lpj_notes'])
                                            <p class="mt-1 text-xs font-medium text-red-600 italic flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3 w-3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                                </svg>
                                                Rev: {{ \Illuminate\Support\Str::limit($item['lpj_notes'], 60) }}
                                            </p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium 
                                        {{ $item['lpj_status'] == 'Disetujui' ? 'bg-green-100 text-green-800' : ($item['lpj_status'] == 'Revisi' ? 'bg-red-100 text-red-800' : ($item['lpj_status'] == 'Menunggu' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600')) }}">
                                        {{ $item['lpj_status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-3">
                                        @if($item['lpj_file'])
                                            <a href="{{ asset('storage/' . $item['lpj_file']) }}" target="_blank" 
                                               class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors" title="Lihat File">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                Lihat
                                            </a>
                                        @endif

                                        @if($item['lpj_status'] == 'Belum Upload' || $item['lpj_status'] == 'Revisi')
                                            <a href="{{ route('organisasi.lpj', $item['id']) }}" 
                                               class="inline-flex items-center gap-1.5 rounded-lg {{ $item['lpj_status'] == 'Revisi' ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700' }} px-3 py-1.5 text-xs font-bold text-white transition-all shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75v-2.25m-18 0A2.25 2.25 0 005.25 14.25h13.5A2.25 2.25 0 0021 16.5m-18 0V6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v9.75m-18 0l3-3m0 0l3 3m-3-3v11.25" />
                                                </svg>
                                                {{ $item['lpj_status'] == 'Revisi' ? 'Revisi' : 'Upload' }}
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="h-12 w-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500">Belum ada kegiatan yang membutuhkan LPJ.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
