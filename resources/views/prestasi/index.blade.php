@extends('layouts.app')

@section('title', 'Prestasi Mahasiswa')
@section('page-title', 'Prestasi Mahasiswa')
@section('page-subtitle', 'Rekap data prestasi mahasiswa aktif')

@php
    $currentUser = auth()->user();
    $totalPrestasi = \App\Models\Prestasi::where('id_user', $currentUser->id_user)->count();
    $internasional = \App\Models\Prestasi::where('id_user', $currentUser->id_user)->where('tingkat', 'Internasional')->count();
    $nasional = \App\Models\Prestasi::where('id_user', $currentUser->id_user)->where('tingkat', 'Nasional')->count();
    $regional = \App\Models\Prestasi::where('id_user', $currentUser->id_user)->where('tingkat', 'Regional')->count();

    $myProposals = \App\Models\ProposalKegiatan::where('id_user', $currentUser->id_user)->latest()->get();
    $myPrestasi = \App\Models\Prestasi::where('id_user', $currentUser->id_user)->latest()->get();
@endphp

@section('content')
<div class="p-4 sm:p-6 space-y-6">

    {{-- ══════════════════════════════════════════════════════════════
         STAT CARDS
    ══════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

        {{-- Total Prestasi --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100 flex items-start gap-4">
            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-red-100">
                <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0
                             014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42
                             3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806
                             1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42
                             3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0
                             01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438
                             3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400">Total Prestasi</p>
                <p class="mt-0.5 text-2xl font-extrabold text-gray-800">{{ $totalPrestasi }}</p>
                <p class="mt-1 text-[11px] text-green-500 font-medium">Data Terverifikasi</p>
            </div>
        </div>

        {{-- Tingkat Internasional --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100 flex items-start gap-4">
            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-blue-100">
                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8
                             3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2
                             0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0
                             11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400">Internasional</p>
                <p class="mt-0.5 text-2xl font-extrabold text-gray-800">{{ $internasional }}</p>
                <p class="mt-1 text-[11px] text-blue-500 font-medium">Tingkat Prestasi</p>
            </div>
        </div>

        {{-- Tingkat Nasional --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100 flex items-start gap-4">
            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-yellow-100">
                <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0
                             00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0
                             00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1
                             1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1
                             1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1
                             0 00.951-.69l1.519-4.674z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400">Nasional</p>
                <p class="mt-0.5 text-2xl font-extrabold text-gray-800">{{ $nasional }}</p>
                <p class="mt-1 text-[11px] text-yellow-500 font-medium">Tingkat Prestasi</p>
            </div>
        </div>

        {{-- Tingkat Regional --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100 flex items-start gap-4">
            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-green-100">
                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8
                             8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400">Regional</p>
                <p class="mt-0.5 text-2xl font-extrabold text-gray-800">{{ $regional }}</p>
                <p class="mt-1 text-[11px] text-green-500 font-medium">Tingkat Prestasi</p>
            </div>
        </div>

    </div>
    {{-- END STAT CARDS --}}


    {{-- ══════════════════════════════════════════════════════════════
         TABLE CARD
    ══════════════════════════════════════════════════════════════════ --}}
    <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">

        {{-- Table Header / Toolbar --}}
        <div class="flex flex-col gap-3 border-b border-gray-100 px-5 py-4
                    sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-base font-bold text-gray-800">Daftar Prestasi Mahasiswa</h2>
                <p class="mt-0.5 text-xs text-gray-400">Menampilkan seluruh data prestasi yang telah diinputkan</p>
            </div>
            <div class="flex items-center gap-2">
                {{-- Search --}}
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        type="text"
                        placeholder="Cari prestasi..."
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2 pl-9 pr-4
                               text-sm text-gray-700 placeholder-gray-400 outline-none
                               focus:border-red-400 focus:bg-white focus:ring-2 focus:ring-red-100
                               transition-all sm:w-52"
                    />
                </div>
                {{-- Tambah Button --}}
                <a
                    href="{{ url('/prestasi/create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-red-700 px-4 py-2
                           text-sm font-semibold text-white shadow-sm
                           hover:bg-red-800 active:bg-red-900 transition-colors flex-shrink-0"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah
                </a>
            </div>
        </div>

        {{-- Responsive Table Wrapper --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase
                                   tracking-wider text-gray-500">No</th>
                        <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase
                                   tracking-wider text-gray-500">Nama Mahasiswa</th>
                        <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase
                                   tracking-wider text-gray-500">NIM</th>
                        <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase
                                   tracking-wider text-gray-500">Nama Prestasi</th>
                        <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase
                                   tracking-wider text-gray-500">Tingkat</th>
                        <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase
                                      @forelse ($myPrestasi as $index => $item)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-5 py-3.5 text-gray-400 font-medium">{{ $index + 1 }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center
                                            rounded-full bg-red-100 text-xs font-bold text-red-700">
                                    {{ strtoupper(substr($currentUser->nama_depan, 0, 1) . substr($currentUser->nama_belakang, 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-800">{{ $currentUser->nama_depan }} {{ $currentUser->nama_belakang }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-gray-500 font-mono text-xs">{{ $currentUser->username }}</td>
                        <td class="px-5 py-3.5 text-gray-700 max-w-xs truncate">
                            {{ $item->nama_kompetisi }}
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5
                                         py-0.5 text-xs font-semibold text-blue-700">
                                {{ $item->tingkat }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1 rounded-full bg-yellow-100
                                         px-2.5 py-0.5 text-xs font-semibold text-yellow-700">
                                🏆 {{ $item->capaian }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-500">{{ $item->created_at->format('Y') }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1.5">
                                <a href="#" class="rounded-lg p-1.5 text-blue-500 hover:bg-blue-50 transition-colors" title="Detail">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-10 text-center text-gray-400 italic">
                            Belum ada data prestasi terverifikasi.
                        </td>
                    </tr>
                    @endforelse
 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0
                                                 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-500">Belum ada data prestasi</p>
                                    <p class="mt-0.5 text-xs text-gray-400">Klik tombol "Tambah" untuk menambahkan data baru</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endif
                    --}}

                </tbody>
            </table>
        </div>

        {{-- Table Footer / Pagination --}}
        <div class="flex flex-col items-center justify-between gap-3 border-t border-gray-100
                    px-5 py-3.5 sm:flex-row">
            <p class="text-xs text-gray-400">
                Menampilkan <span class="font-semibold text-gray-600">1&ndash;3</span>
                dari <span class="font-semibold text-gray-600">128</span> data
            </p>
            {{-- Pagination links (replace with {{ $prestasis->links() }} when using real data) --}}
            <div class="flex items-center gap-1">
                <button class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium
                               text-gray-400 hover:bg-gray-50 transition-colors disabled:opacity-40"
                        disabled>
                    &laquo; Sebelumnya
                </button>
                <button class="rounded-lg bg-red-700 px-3 py-1.5 text-xs font-semibold
                               text-white shadow-sm">
                    1
                </button>
                <button class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium
                               text-gray-600 hover:bg-gray-50 transition-colors">
                    2
                </button>
                <button class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium
                               text-gray-600 hover:bg-gray-50 transition-colors">
                    3
                </button>
                <button class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium
                               text-gray-600 hover:bg-gray-50 transition-colors">
                    Berikutnya &raquo;
                </button>
            </div>
        </div>

    </div>
    {{-- END TABLE CARD --}}

    {{-- ══════════════════════════════════════════════════════════════
         STATUS PENGAJUAN PROPOSAL (Kegiatan)
    ══════════════════════════════════════════════════════════════════ --}}
    <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="text-base font-bold text-gray-800">Status Pengajuan Proposal Kegiatan</h2>
            <p class="mt-0.5 text-xs text-gray-400">Pantau status verifikasi proposal kegiatan yang kamu ajukan</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="px-5 py-3 text-xs font-semibold uppercase text-gray-500">No</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase text-gray-500">Nama Kegiatan</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase text-gray-500">Pelaksanaan</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase text-gray-500">Ajuan Dana</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase text-gray-500 text-center">Status</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase text-gray-500 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($myProposals as $index => $prop)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-5 py-3.5 text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-5 py-3.5 font-medium text-gray-800">{{ $prop->nama_kegiatan }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ \Carbon\Carbon::parse($prop->waktu_kegiatan)->format('d M Y') }}</td>
                        <td class="px-5 py-3.5 text-gray-600">Rp {{ number_format($prop->besar_ajuan, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold 
                                {{ $prop->status == 'Disetujui' ? 'bg-green-100 text-green-700' : ($prop->status == 'Menunggu' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700') }}">
                                {{ $prop->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($prop->file)
                            <a href="{{ asset('storage/' . $prop->file) }}" target="_blank" class="text-blue-500 hover:underline text-xs">Lihat File</a>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-gray-400 italic">
                            Belum ada pengajuan proposal kegiatan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
