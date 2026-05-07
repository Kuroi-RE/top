@extends('layouts.app')

@section('title', 'Beranda Mahasiswa')

@section('content')

@php
    $user = auth()->user() ?? (session('dummy_user') ? (object)session('dummy_user') : null);
@endphp

@if(!$user)
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="text-center">
            <p class="text-gray-600 mb-4">Anda harus login terlebih dahulu.</p>
            <a href="{{ route('login') }}" class="inline-block bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">Kembali ke Login</a>
        </div>
    </div>
@else

<style>
    @import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0');

    .greeting-hero {
        position: relative;
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 16px;
        border-radius: 14px;
        border: 1px solid #fca5a5;
        background: linear-gradient(135deg, #fff1f2 0%, #ffffff 65%);
        box-shadow: 0 10px 24px rgba(225, 29, 72, 0.08);
        overflow: hidden;
    }

    .greeting-wave {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #e11d48;
        animation: greeting-pop 0.6s ease-out both;
        flex-shrink: 0;
    }

    .greeting-wave-icon {
        font-family: 'Material Symbols Rounded';
        font-size: 34px;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-shadow: 0 6px 12px rgba(225, 29, 72, 0.14);
        font-variation-settings: 'FILL' 0, 'wght' 600, 'GRAD' 0, 'opsz' 48;
        transform-origin: 50% 80%;
        animation: greeting-wiggle 2.2s ease-in-out infinite;
    }

    .greeting-title {
        font-size: 0.98rem;
        font-weight: 700;
        color: #9f1239;
        opacity: 0;
        transform: translateY(8px) scale(0.98);
        animation: greeting-pop 0.55s ease-out 0.08s forwards;
    }

    .greeting-sub {
        margin-top: 2px;
        font-size: 0.82rem;
        color: #be123c;
        opacity: 0;
        transform: translateY(8px) scale(0.98);
        animation: greeting-pop 0.55s ease-out 0.2s forwards;
    }

    @keyframes greeting-pop {
        from { opacity: 0; transform: translateY(10px) scale(0.96); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes greeting-wiggle {
        0% { transform: rotate(0deg); }
        20% { transform: rotate(8deg); }
        40% { transform: rotate(-6deg); }
        60% { transform: rotate(6deg); }
        80% { transform: rotate(-4deg); }
        100% { transform: rotate(0deg); }
    }
</style>

@php
    // Handle both Laravel auth and session dummy_user
    $user = auth()->user();
    $sessionUser = session('dummy_user');
    
    if (!$user && $sessionUser) {
        $user = \App\Models\User::where('username', $sessionUser['username'])->first();
        if (!$user) {
            $user = (object)$sessionUser;
        }
    }
    
    if ($user) {
        $displayName = $user->nama_depan ?? $user->username ?? 'teman';
        $userId = $user->id_user ?? $user->id ?? null;
        $prodi = $user->prodi ?? 'Tidak ada';
        
        // Get prestasi data - if we have id_user, fetch from DB; otherwise empty
        if ($userId) {
            $prestasis = \App\Models\Prestasi::where('id_user', $userId)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $prestasis = collect([]);
        }
    } else {
        $displayName = 'teman';
        $prodi = 'Tidak ada';
        $prestasis = collect([]);
    }
    
    // Calculate summary statistics
    $totalPrestasi = $prestasis->count();
    $internasional = $prestasis->where('tingkat', 'Internasional')->count();
    $nasional = $prestasis->where('tingkat', 'Nasional')->count();
    $regional = $prestasis->where('tingkat', 'Regional')->count();
    
    $summaryCards = [
        ['title' => 'Total Prestasi', 'count' => $totalPrestasi, 'hint' => 'Prestasi Anda', 'color' => 'text-red-600', 'bg' => 'bg-red-50'],
        ['title' => 'Internasional', 'count' => $internasional, 'hint' => $totalPrestasi > 0 ? round(($internasional / $totalPrestasi) * 100, 1) . '% dari total' : '0% dari total', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
        ['title' => 'Nasional', 'count' => $nasional, 'hint' => $totalPrestasi > 0 ? round(($nasional / $totalPrestasi) * 100, 1) . '% dari total' : '0% dari total', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50'],
        ['title' => 'Regional', 'count' => $regional, 'hint' => $totalPrestasi > 0 ? round(($regional / $totalPrestasi) * 100, 1) . '% dari total' : '0% dari total', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
    ];

    $tingkatStyles = [
        'Internasional' => 'bg-blue-100 text-blue-700',
        'Nasional' => 'bg-amber-100 text-amber-700',
        'Regional' => 'bg-emerald-100 text-emerald-700',
    ];
@endphp

<div class="mb-6">
    <div class="greeting-hero mb-4">
        <div class="greeting-wave" aria-hidden="true">
            <span class="greeting-wave-icon">waving_hand</span>
        </div>
        <div>
            <div class="greeting-title">Halo, {{ $displayName }}! Selamat datang di Beranda Mahasiswa</div>
            <div class="greeting-sub">Pantau prestasi, pencapaian, dan progres dokumen mahasiswa dari satu tempat</div>
        </div>
    </div>
</div>

<div class="dashboard-shell flex flex-col gap-8">
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
        @foreach ($summaryCards as $card)
            <div class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100 flex items-start gap-4">
                <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl {{ $card['bg'] }}">
                    <svg class="h-5 w-5 {{ $card['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400">{{ $card['title'] }}</p>
                    <p class="mt-0.5 text-2xl font-extrabold text-gray-800">{{ $card['count'] }}</p>
                    <p class="mt-1 text-[11px] text-gray-500 font-medium">{{ $card['hint'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-gray-100">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Daftar Prestasi Mahasiswa</h2>
            <a href="{{ route('prestasi.transkrip_prestasi') }}" class="inline-flex w-fit items-center gap-2 rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 -960 960 960" class="h-5 w-5 shrink-0">
                    <path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                </svg>
                Unduh Transkrip
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-[900px] w-full border-separate border-spacing-y-3 border-spacing-x-0 text-left text-sm text-slate-700">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 font-semibold">No</th>
                        <th class="px-4 py-3 font-semibold">Nama Kegiatan</th>
                        <th class="px-4 py-3 font-semibold">Tingkat</th>
                        <th class="px-4 py-3 font-semibold">Prestasi Dicapai</th>
                        <th class="px-4 py-3 font-semibold">Nama Kompetisi</th>
                        <th class="px-4 py-3 font-semibold">Penyelenggara</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($prestasis as $index => $prestasi)
                        <tr>
                            <td class="bg-white px-4 py-4 align-middle first:rounded-l-xl">{{ $index + 1 }}</td>
                            <td class="bg-white px-4 py-4 align-middle leading-5 text-slate-700">{{ $prestasi->nama_kompetisi }}</td>
                            <td class="bg-white px-4 py-4 align-middle">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $tingkatStyles[$prestasi->tingkat] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $prestasi->tingkat }}
                                </span>
                            </td>
                            <td class="bg-white px-4 py-4 align-middle">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ $prestasi->capaian ?? '-' }}
                                </span>
                            </td>
                            <td class="bg-white px-4 py-4 align-middle text-slate-700">{{ $prestasi->nama_kompetisi }}</td>
                            <td class="bg-white px-4 py-4 align-middle last:rounded-r-xl text-slate-700">{{ $prestasi->penyelenggara ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="bg-white px-4 py-8 text-center text-slate-500 first:rounded-l-xl last:rounded-r-xl">
                                Belum ada prestasi. <a href="{{ route('prestasi.create') }}" class="text-red-600 hover:underline font-medium">Tambah prestasi sekarang</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endif
@endsection
