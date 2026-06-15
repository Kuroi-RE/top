@extends('layouts.app')

@section('title', 'Detail Kegiatan')

@section('content')

<div class="min-h-screen bg-gray-100 flex justify-center px-4 py-8">
    <div class="w-full max-w-5xl rounded-2xl bg-white p-6 shadow-lg sm:p-8 lg:p-10">

        <!-- Header -->
        <div class="mb-8 flex items-center justify-between border-b pb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Detail Kegiatan</h1>
                <p class="text-gray-500 mt-1">Informasi lengkap pengajuan proposal dan laporan</p>
            </div>
            <a href="{{ route('organisasi.index') }}" class="flex items-center gap-2 text-gray-500 hover:text-gray-700 transition font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="space-y-8">
            <!-- Informasi Utama -->
            <section>
                <h2 class="text-lg font-bold text-red-700 mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-red-600 rounded-full"></span>
                    Informasi Kegiatan
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 bg-gray-50 p-6 rounded-2xl border border-gray-100">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Kegiatan</p>
                        <p class="mt-1 text-gray-800 font-medium">{{ $proposal->nama_kegiatan }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Ajuan Triwulan</p>
                        <p class="mt-1 text-gray-800 font-medium">Triwulan {{ $proposal->ajuan_triwulan }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Waktu Pelaksanaan</p>
                        <p class="mt-1 text-gray-800 font-medium">{{ \Carbon\Carbon::parse($proposal->waktu_kegiatan)->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tingkat Risiko</p>
                        <p class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $proposal->risiko_proposal == 'Tinggi' ? 'bg-red-100 text-red-800' : ($proposal->risiko_proposal == 'Sedang' ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800') }}">
                                {{ $proposal->risiko_proposal }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Besar Ajuan Dana</p>
                        <p class="mt-1 text-gray-800 font-medium text-lg">Rp {{ number_format($proposal->besar_ajuan, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Anggaran Disetujui</p>
                        <p class="mt-1 text-red-700 font-bold text-lg">Rp {{ number_format($proposal->anggaran_disetujui ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </section>

            <!-- Berkas & Dokumen -->
            <section>
                <h2 class="text-lg font-bold text-red-700 mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-red-600 rounded-full"></span>
                    Berkas & Dokumen
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Proposal -->
                    <div class="border rounded-2xl p-5 flex flex-col items-center text-center gap-3 hover:border-red-200 transition bg-white shadow-sm">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H8.25m0 12.75h7.5m-7.5 3h4.5M5.625 2.25h5.603c.895 0 1.754.356 2.386.988l4.773 4.773c.632.632.988 1.49.988 2.386v8.853a2.25 2.25 0 01-2.25 2.25H5.625a2.25 2.25 0 01-2.25-2.25V4.5a2.25 2.25 0 012.25-2.25z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-700">Proposal Kegiatan</h3>
                        <a href="{{ asset('storage/' . $proposal->file) }}" target="_blank" class="mt-auto px-4 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-full hover:bg-blue-700 transition">
                            Lihat Proposal
                        </a>
                    </div>

                    <!-- LPJ Keuangan -->
                    <div class="border rounded-2xl p-5 flex flex-col items-center text-center gap-3 hover:border-red-200 transition bg-white shadow-sm">
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-700">LPJ Keuangan</h3>
                        @if($proposal->file_lpj_keuangan)
                            <a href="{{ asset('storage/' . $proposal->file_lpj_keuangan) }}" target="_blank" class="mt-auto px-4 py-1.5 bg-green-600 text-white text-xs font-semibold rounded-full hover:bg-green-700 transition">
                                Lihat LPJ Keuangan
                            </a>
                        @else
                            <p class="mt-auto text-xs text-gray-400 font-medium italic">Belum tersedia (&mdash;)</p>
                        @endif
                    </div>

                    <!-- LPJ Kegiatan -->
                    <div class="border rounded-2xl p-5 flex flex-col items-center text-center gap-3 hover:border-red-200 transition bg-white shadow-sm">
                        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 0113.5 5.625v1.5a3.375 3.375 0 003.375 3.375h1.5a3.375 3.375 0 013.375 3.375M9 15l2.25 2.25L15 12" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-700">LPJ Kegiatan</h3>
                        @php $lpjKeg = $proposal->lpj->first(); @endphp
                        @if($lpjKeg)
                            <a href="{{ asset('storage/' . $lpjKeg->file_lpj) }}" target="_blank" class="mt-auto px-4 py-1.5 bg-purple-600 text-white text-xs font-semibold rounded-full hover:bg-purple-700 transition">
                                Lihat LPJ Kegiatan
                            </a>
                        @else
                            <p class="mt-auto text-xs text-gray-400 font-medium italic">Belum tersedia (&mdash;)</p>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
