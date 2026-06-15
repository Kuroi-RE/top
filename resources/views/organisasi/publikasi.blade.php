@extends('layouts.app')

@section('title', 'Publikasi Kegiatan Ormawa')

@section('content')

<div class="min-h-screen bg-gray-100 flex justify-center px-4 py-8">
    <div class="w-full max-w-5xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Publikasi Kegiatan Ormawa</h1>
                <p class="text-sm text-gray-500">Kelola publikasi kegiatan dan status tayang.</p>
            </div>
            @php $quotaFull = $weekCount >= 3; @endphp
            @if(auth()->user()->can('Create Publikasi'))
                @if($quotaFull)
                    <span
                        class="inline-flex items-center justify-center rounded-full bg-gray-300 px-5 py-2 text-sm font-semibold text-gray-500 cursor-not-allowed"
                        title="Kuota minggu ini sudah habis (3/3)"
                    >
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Publikasi Baru
                    </span>
                @else
                    <a
                        href="{{ route('organisasi.publikasi_create') }}"
                        class="inline-flex items-center justify-center rounded-full bg-red-700 px-5 py-2 text-sm font-semibold text-white transition hover:bg-red-800"
                    >
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Publikasi Baru
                    </a>
                @endif
            @endif
        </div>

        @if($errors->has('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first('error') }}
            </div>
        @endif

        <!-- Ringkasan Publikasi -->
        <div class="flex flex-wrap gap-6">
            <div class="flex-1 min-w-[220px] rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <p class="text-xs text-gray-500 uppercase">Tampil di Beranda</p>
                <p class="mt-2 text-2xl font-bold text-gray-800">{{ $publikasiItems->where('status', 'Disetujui')->count() }}</p>
                <p class="mt-1 text-xs text-gray-500">Poster sudah tayang</p>
            </div>
            <div class="flex-1 min-w-[220px] rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <p class="text-xs text-gray-500 uppercase">Pending</p>
                <p class="mt-2 text-2xl font-bold text-gray-800">{{ $publikasiItems->where('status', 'Menunggu')->count() }}</p>
                <p class="mt-1 text-xs text-gray-500">Menunggu verifikasi</p>
            </div>
            <div class="flex-1 min-w-[220px] rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <p class="text-xs text-gray-500 uppercase">Ditolak</p>
                <p class="mt-2 text-2xl font-bold text-gray-800">{{ $publikasiItems->where('status', 'Ditolak')->count() }}</p>
                <p class="mt-1 text-xs text-gray-500">Perlu perbaikan</p>
            </div>
        </div>

        <!-- Kuota Informasi -->
        @php
            $maxQuota = 3;
            $remaining = max(0, $maxQuota - $weekCount);
        @endphp
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-base font-semibold text-gray-800">Kuota Informasi</p>
                    <p class="mt-1 text-sm text-gray-500">Kegiatan Mingguan</p>
                </div>
                <div class="text-base font-semibold text-red-600">
                    <span>{{ $weekCount }}</span>/<span>{{ $maxQuota }}</span>
                </div>
            </div>
            <div class="mt-3">
                <div class="h-2 w-full rounded-full bg-gray-200">
                    <div class="h-2 rounded-full bg-red-600" style="width: {{ ($weekCount / $maxQuota) * 100 }}%"></div>
                </div>
                <p class="mt-3 text-xs text-gray-500">
                    {{ $remaining > 0 ? "Tersisa $remaining slot pengunggahan poster kegiatan minggu ini." : "Kuota minggu ini sudah habis." }}
                </p>
            </div>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-base font-semibold text-gray-800">Daftar Publikasi</h2>
                <a
                    href="{{ route('organisasi.publikasi_export') }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition-colors hover:bg-gray-50 hover:text-gray-700"
                    aria-label="Download publikasi"
                    title="Download"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l4-4m-4 4l-4-4M5 21h14" />
                    </svg>
                </a>
            </div>

            @php
                $publikasiItems = $publikasiItems ?? collect([]);
            @endphp

            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Caption</th>
                            
                            <th class="px-4 py-3 text-center">File</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($publikasiItems as $item)
                            <tr class="text-gray-700 hover:bg-gray-50/50 transition-colors">
                                <td class="px-4 py-4">
                                    <div class="font-medium text-gray-800">{{ $item['judul'] ?? '-' }}</div>
                                    @if(($item->status == 'Revisi' || $item->status == 'Ditolak') && $item->catatan_admin)
                                        <div class="mt-1 text-xs text-red-600 font-semibold flex items-center gap-1">
                                            <span>📌 Catatan: {{ $item->catatan_admin }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-gray-600 max-w-xs truncate">{{ $item['caption'] ?? '-' }}</td>
                                <!-- <td class="px-4 py-4">
                                    @if (!empty($item['link']))
                                        <a href="{{ $item['link'] }}" target="_blank" rel="noopener noreferrer"
                                           class="text-blue-600 hover:underline">
                                            {{ \Illuminate\Support\Str::limit($item['link'], 20) }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td> -->
                                <td class="px-4 py-4 text-center">
                                    <a href="{{ asset('storage/' . $item->poster) }}" target="_blank" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:text-red-600 hover:border-red-100 transition-colors">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </a>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @php
                                        $badgeColor = match($item->status) {
                                            'Disetujui' => 'bg-green-50 text-green-700 border-green-200',
                                            'Ditolak' => 'bg-red-50 text-red-700 border-red-200',
                                            'Revisi' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            default => 'bg-blue-50 text-blue-700 border-blue-200', // Menunggu
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $badgeColor }}">
                                        {{ $item->status ?? 'Menunggu' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @can('Edit Publikasi')
                                        <a href="{{ route('organisasi.publikasi_edit', $item->id_publikasi) }}" class="inline-flex items-center rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700 hover:bg-blue-100 transition-colors">
                                            Edit
                                        </a>
                                        @endcan
                                        @can('Delete Publikasi')
                                        <form action="{{ route('organisasi.publikasi_destroy', $item->id_publikasi) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus publikasi ini?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center rounded-lg bg-red-50 px-2.5 py-1 text-xs font-bold text-red-700 hover:bg-red-100 transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-sm text-gray-500 italic">
                                    Belum ada publikasi.
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

@push('scripts')
<script>
    // No JS quota logic needed as it is handled by PHP now.
</script>
@endpush
