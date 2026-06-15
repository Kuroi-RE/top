@extends('layouts.app')

@section('title', 'Prestasi Mahasiswa')

@section('content')

<style>
	.dashboard-shell {
		width: 100%;
	}

	.page-hero {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		padding: 6px 0 2px;
	}

	.page-hero .title h1 {
		margin: 0; font-size: 1.25rem; font-weight: 700; color: #0f172a;
	}
	.page-hero .title p { margin: 0; color: #64748b; font-size: 0.95rem; }

	.dashboard-filter-grid {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 28px;
		align-items: stretch;
	}

	.dashboard-card {
		box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
		border: 0;
		border-radius: 12px;
		padding: 12px;
		background: #ffffff;
	}

	.dashboard-filter-card {
		min-height: 86px;
		display: flex;
		flex-direction: column;
		justify-content: flex-start;
	}

	.dashboard-filter-surface {
		background: transparent;
		border: 0;
		box-shadow: none;
		padding: 0;
	}

	.dashboard-table-wrap {
		scrollbar-width: thin;
		overflow: auto;
		max-width: 100%;
	}

	.dashboard-table-wrap::-webkit-scrollbar {
		height: 10px;
	}

	.dashboard-table-wrap::-webkit-scrollbar-thumb {
		background: rgb(203 213 225);
		border-radius: 9999px;
	}

	@media (max-width: 768px) {
		.dashboard-filter-grid {
			grid-template-columns: 1fr;
		}
	}
</style>

@php
    $query = \App\Models\Prestasi::with('user')->where('mewakili_ormawa', 'tidak');
    
    if (request('tingkat')) {
        $query->where('tingkat', request('tingkat'));
    }
    
    if (request('search')) {
        $q = request('search');
        $query->where(function($sub) use ($q) {
            $sub->where('nama_kompetisi', 'like', '%' . $q . '%')
                ->orWhere('penyelenggara', 'like', '%' . $q . '%')
                ->orWhere('capaian', 'like', '%' . $q . '%')
                ->orWhereHas('user', function($u) use ($q) {
                    $u->where('nama_depan', 'like', '%' . $q . '%')
                      ->orWhere('nama_belakang', 'like', '%' . $q . '%')
                      ->orWhere('username', 'like', '%' . $q . '%')
                      ->orWhere('nim', 'like', '%' . $q . '%');
                });
        });
    }

    $prestasi = $query->latest()->paginate(request('per_page', 10))->withQueryString();
    
    $studentProposals = \App\Models\ProposalPrestasiMahasiswa::with('user')
        ->latest()
        ->get();
@endphp

<div class="dashboard-shell flex flex-col gap-6">
	<div class="page-hero">
		<div class="title">
			<h1>Prestasi Mahasiswa</h1>
			<p>Kelola dan pantau data prestasi mahasiswa</p>
		</div>
	</div>

	<div class="dashboard-card bg-white rounded-2xl p-6 shadow-sm">

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admin.prestasi_mahasiswa') }}" id="prestasi-filter-form" class="mb-6">
            <div class="flex flex-wrap gap-4 items-end">
                {{-- Tingkat --}}
                <div class="flex flex-col gap-1.5 flex-1 min-w-[160px]">
                    <label class="text-sm font-semibold text-slate-700">Tingkat</label>
                    <div class="relative">
                        <select name="tingkat" onchange="this.form.submit()"
                            class="w-full rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-700 shadow-sm outline-none transition-all hover:border-slate-300 focus:border-red-500 cursor-pointer"
                            style="appearance:none;-webkit-appearance:none;padding:0.75rem 2.5rem 0.75rem 1.25rem;">
                            <option value="">Semua Tingkat</option>
                            <option value="Internasional" {{ request('tingkat') == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                            <option value="Nasional" {{ request('tingkat') == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                            <option value="Regional" {{ request('tingkat') == 'Regional' ? 'selected' : '' }}>Regional</option>
                        </select>
                        <div class="pointer-events-none text-slate-400" style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1rem;height:1rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Pencarian --}}
                <div class="flex flex-col gap-1.5 flex-1 min-w-[220px]">
                    <label class="text-sm font-semibold text-slate-700">Pencarian</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama, NIM, prestasi..."
                            class="w-full rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-700 shadow-sm outline-none transition-all hover:border-slate-300 focus:border-red-500 placeholder:text-slate-400"
                            style="padding:0.75rem 2.5rem 0.75rem 1.25rem;">
                        <div class="pointer-events-none text-slate-400" style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1.25rem;height:1.25rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M17 10.5A6.5 6.5 0 1 1 4 10.5a6.5 6.5 0 0 1 13 0Z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2 self-end">
                    <button type="submit" class="rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700 transition">Cari</button>
                    @if(request('search') || request('tingkat'))
                        <a href="{{ route('admin.prestasi_mahasiswa') }}" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Reset</a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Toolbar: record-per-page + export --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <select class="rounded-xl border border-slate-200 bg-white text-sm font-bold text-slate-700 outline-none transition-all hover:border-slate-300 focus:border-red-500 cursor-pointer shadow-sm"
                        style="appearance:none;-webkit-appearance:none;padding:0.5rem 2.5rem 0.5rem 1rem;"
                        onchange="window.location.href = '{{ route('admin.prestasi_mahasiswa') }}?per_page=' + this.value + '&search={{ request('search') }}&tingkat={{ request('tingkat') }}'">
                        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                    </select>
                    <div class="pointer-events-none text-slate-400" style="position:absolute;right:0.875rem;top:50%;transform:translateY(-50%);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1rem;height:1rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                </div>
                <span class="text-sm font-medium text-slate-500">Record per page</span>
            </div>

            <a href="{{ route('admin.prestasi_mahasiswa.export_pdf', request()->query()) }}" target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 hover:shadow-md focus:ring-4 focus:ring-red-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 -960 960 960" class="h-5 w-5 shrink-0">
                    <path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z"/>
                </svg>
                Export Data
            </a>
        </div>

		<div class="dashboard-table-wrap">
			<table class="min-w-full w-full border-separate border-spacing-y-3 border-spacing-x-0 text-left text-sm text-slate-700">
				<thead class="bg-slate-100 text-slate-700">
					<tr>
						<th class="px-4 py-3 font-semibold rounded-l-lg">NIM</th>
						<th class="px-4 py-3 font-semibold">Nama</th>
						<th class="px-4 py-3 font-semibold">Prodi</th>
						<th class="px-4 py-3 font-semibold">Prestasi</th>
						<th class="px-4 py-3 font-semibold">Nama Event</th>
						<th class="px-4 py-3 font-semibold text-center">Status</th>
						<th class="px-4 py-3 font-semibold rounded-r-lg text-center">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($prestasi as $item)
						<tr class="group transition-colors hover:bg-slate-50">
							<td class="bg-white px-4 py-4 align-middle border-y border-l border-slate-200 first:rounded-l-xl group-hover:bg-slate-50 text-slate-600">{{ $item->user->username }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 font-medium text-slate-900">{{ $item->user->nama_depan }} {{ $item->user->nama_belakang }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-slate-600">{{ $item->user->prodi ?? '-' }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50">
								<span class="inline-flex items-center rounded-md bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20">{{ $item->capaian }}</span>
							</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 leading-5 text-slate-600">
                                <div class="font-medium text-slate-800">{{ $item->nama_kompetisi }}</div>
                                <div class="text-[11px] text-slate-400">{{ $item->penyelenggara }}</div>
                            </td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-center">
								<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold 
                                    {{ $item->status_verifikasi == 'Valid' ? 'bg-green-100 text-green-700' : ($item->status_verifikasi == 'Menunggu' ? 'bg-blue-100 text-blue-700' : ($item->status_verifikasi == 'Revisi' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700')) }}">
                                    {{ $item->status_verifikasi ?? 'Menunggu' }}
                                </span>
							</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-r border-slate-200 last:rounded-r-xl group-hover:bg-slate-50">
								<div class="flex items-center justify-center gap-3">
									<a href="{{ route('admin.detail_prestasi', $item->id_prestasi) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-sky-50 text-sky-700 transition hover:bg-sky-100" title="Verifikasi">
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
											<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
										</svg>
									</a>
									<form class="form-delete-prestasi" data-id="{{ $item->id_prestasi }}" data-event="{{ $item->nama_kompetisi }}" data-capaian="{{ $item->capaian }}" action="{{ route('admin.prestasi_mahasiswa.delete', $item->id_prestasi) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-delete-prestasi inline-flex h-8 w-8 items-center justify-center rounded-md bg-rose-50 text-rose-700 transition hover:bg-rose-100 disabled:opacity-50 disabled:cursor-not-allowed" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.22a51.964 51.964 0 00-3.32 0c-1.18.056-2.09 1.04-2.09 2.22v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
								</div>
							</td>
						</tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-slate-400 italic bg-white first:rounded-l-xl last:rounded-r-xl border border-slate-200">
                                Belum ada data pengajuan prestasi mahasiswa.
                            </td>
                        </tr>
					@endforelse
				</tbody>
			</table>
		</div>
        @if($prestasi->hasPages())
        <div class="mt-4">
            {{ $prestasi->links() }}
        </div>
        @endif
	</div>
	<div class="dashboard-card bg-white rounded-2xl p-6 shadow-sm mt-8">
        <div class="flex items-center justify-between mb-6">
            <div class="title">
                <h2 class="text-lg font-bold text-slate-800">Daftar Pengajuan Proposal Kegiatan Mahasiswa</h2>
                <p class="text-sm text-slate-500">Pantau pengajuan dana kegiatan lomba mahasiswa secara individu</p>
            </div>
        </div>

        <div class="dashboard-table-wrap">
			<table class="min-w-full w-full border-separate border-spacing-y-3 border-spacing-x-0 text-left text-sm text-slate-700">
				<thead class="bg-slate-100 text-slate-700">
					<tr>
						<th class="px-4 py-3 font-semibold rounded-l-lg">Nama Pengaju</th>
						<th class="px-4 py-3 font-semibold">Nama Kegiatan</th>
						<th class="px-4 py-3 font-semibold">Waktu</th>
						<th class="px-4 py-3 font-semibold text-right">Besar Ajuan</th>
						<th class="px-4 py-3 font-semibold text-center">Status</th>
						<th class="px-4 py-3 font-semibold rounded-r-lg text-center">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($studentProposals as $prop)
						<tr class="group transition-colors hover:bg-slate-50">
							<td class="bg-white px-4 py-4 align-middle border-y border-l border-slate-200 first:rounded-l-xl group-hover:bg-slate-50">
                                <div class="font-medium text-slate-900">{{ $prop->user->nama_depan }} {{ $prop->user->nama_belakang }}</div>
                                <div class="text-[11px] text-slate-400">{{ $prop->user->username }}</div>
                            </td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 font-medium text-slate-800">{{ $prop->nama_kegiatan }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-slate-600">
                                {{ $prop->waktu_kegiatan ? \Carbon\Carbon::parse($prop->waktu_kegiatan)->format('d/m/Y') : '-' }}
                            </td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-slate-600 text-right font-medium">
                                Rp {{ number_format($prop->besar_ajuan, 0, ',', '.') }}
                            </td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-center">
								<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    {{ $prop->status == 'Disetujui' ? 'bg-green-100 text-green-700' : ($prop->status == 'Menunggu' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $prop->status ?? 'Menunggu' }}
                                </span>
							</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-r border-slate-200 last:rounded-r-xl group-hover:bg-slate-50 text-center">
								<a href="{{ route('admin.form_verifikasi', ['id' => $prop->id_proposal]) }}?type=mahasiswa" class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-sky-50 text-sky-700 transition hover:bg-sky-100" title="Verifikasi Proposal">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                    </svg>
                                </a>
							</td>
						</tr>
					@empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-slate-400 italic bg-white first:rounded-l-xl last:rounded-r-xl border border-slate-200">
                                Belum ada pengajuan proposal kegiatan mahasiswa.
                            </td>
                        </tr>
					@endforelse
				</tbody>
			</table>
		</div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const token = localStorage.getItem('topkema_api_token');

        // Handle delete via API
        const deleteForms = document.querySelectorAll('.form-delete-prestasi');
        deleteForms.forEach(function (form) {
            form.addEventListener('submit', function (e) {
                const id = form.dataset.id;
                const eventName = form.dataset.event;
                const capaian = form.dataset.capaian;

                const confirmed = confirm(`Yakin ingin menghapus data prestasi ini?\n\nNama Event: ${eventName}\nCapaian: ${capaian}\n\nAksi ini tidak dapat dibatalkan.`);
                if (!confirmed) {
                    e.preventDefault();
                    return;
                }

                if (!token || !id || !window.axios) {
                    return; // Let standard form submit handle it
                }

                e.preventDefault();

                const btn = form.querySelector('.btn-delete-prestasi');
                if (btn) btn.disabled = true;

                window.axios.delete(`prestasi/${id}`)
                    .then(function () {
                        showAlert('success', 'Data prestasi berhasil dihapus.');
                        setTimeout(function () { window.location.reload(); }, 1000);
                    })
                    .catch(function (err) {
                        const msg = err?.response?.data?.message || 'Gagal menghapus data prestasi.';
                        showAlert('error', msg);
                        if (btn) btn.disabled = false;
                    });
            });
        });
    });

    // ── Simple Alert ─────────────────────────────────────────────────────────
    function showAlert(type, message) {
        const existing = document.getElementById('api-toast');
        if (existing) existing.remove();

        const colors = type === 'success'
            ? { bg: '#f0fdf4', border: '#bbf7d0', text: '#166534' }
            : { bg: '#fef2f2', border: '#fecaca', text: '#991b1b' };

        const toast = document.createElement('div');
        toast.id = 'api-toast';
        toast.style.cssText = `
            position: fixed; top: 24px; left: 50%; transform: translateX(-50%);
            z-index: 9999; background: ${colors.bg}; border: 1px solid ${colors.border};
            color: ${colors.text}; padding: 14px 24px; border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); font-size: 14px; font-weight: 600;
        `;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(function () { if (toast.parentNode) toast.remove(); }, 4000);
    }
</script>
@endpush
@endsection
