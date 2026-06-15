@extends('layouts.app')

@section('title', 'Beranda Ormawa')

@section('content')

<style>
	@import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0');

	.dashboard-shell {
		width: 100%;
	}

	.dashboard-summary-grid {
		display: grid;
		grid-template-columns: repeat(3, minmax(0, 1fr));
		gap: 24px;
	}

	.dashboard-filter-grid {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 28px;
		align-items: stretch;
	}

	.greeting-wave-icon {
		font-size: 32px;
		transform-origin: 70% 70%;
		animation: greeting-wiggle 2.2s ease-in-out infinite;
	}

	.greeting-title {
		font-size: 1.25rem;
		font-weight: 800;
		color: #7c2d12;
		opacity: 0;
		transform: translateY(8px) scale(0.98);
		animation: greeting-pop 0.55s ease-out 0.08s forwards;
	}

	.greeting-sub {
		margin-top: 4px;
		font-size: 0.875rem;
		font-weight: 500;
		color: #9a3412;
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
		20% { transform: rotate(14deg); }
		40% { transform: rotate(-8deg); }
		60% { transform: rotate(14deg); }
		80% { transform: rotate(-4deg); }
		100% { transform: rotate(0deg); }
	}

	@keyframes content-fade-in {
		from { opacity: 0; transform: translateY(20px); }
		to { opacity: 1; transform: translateY(0); }
	}

	/* Stagger card animations */
	.summary-card:nth-child(1) { animation-delay: 0.1s; }
	.summary-card:nth-child(2) { animation-delay: 0.2s; }
	.summary-card:nth-child(3) { animation-delay: 0.3s; }
	.main-content-card { animation-delay: 0.4s; }

	@media (max-width: 768px) {
		.dashboard-summary-grid,
		.dashboard-filter-grid {
			grid-template-columns: 1fr;
		}
	}
</style>

@php
	$currentUser = auth()->user();
	$displayName = trim(($currentUser?->nama_depan ?? '') . ' ' . ($currentUser?->nama_belakang ?? ''));
	$displayName = $displayName !== '' ? $displayName : ($currentUser?->username ?? 'teman');

	$summaryCards = [
		['title' => 'Proposal Kegiatan', 'count' => $total ?? 0, 'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H8.25m0 12.75h7.5m-7.5 3h4.5M5.625 2.25h5.603c.895 0 1.754.356 2.386.988l4.773 4.773c.632.632.988 1.49.988 2.386v8.853a2.25 2.25 0 01-2.25 2.25H5.625a2.25 2.25 0 01-2.25-2.25V4.5a2.25 2.25 0 012.25-2.25z'],
		['title' => 'LPJ Kegiatan', 'count' => $lpjCount ?? 0, 'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H8.25m0 12.75h7.5m-7.5 3h4.5M5.625 2.25h5.603c.895 0 1.754.356 2.386.988l4.773 4.773c.632.632.988 1.49.988 2.386v8.853a2.25 2.25 0 01-2.25 2.25H5.625a2.25 2.25 0 01-2.25-2.25V4.5a2.25 2.25 0 012.25-2.25z'],
		['title' => 'Publikasi Kegiatan', 'count' => $publikasiCount ?? 0, 'icon' => 'M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253'],
	];

	$statusStyles = [
		'Selesai' => 'bg-emerald-100 text-emerald-700',
		'Disetujui' => 'bg-emerald-100 text-emerald-700',
		'Pencairan' => 'bg-amber-100 text-amber-700',
		'Acc' => 'bg-sky-100 text-sky-700',
		'Revisi' => 'bg-rose-100 text-rose-700',
		'Revisi LPJ' => 'bg-rose-100 text-rose-700',
		'Ajuan baru' => 'bg-slate-100 text-slate-700',
		'Cek LPJ' => 'bg-purple-100 text-purple-700',
	];
@endphp

<div class="dashboard-shell flex flex-col gap-6">
	<div class="page-hero">
		<div class="title">
			<h1>Beranda Ormawa</h1>
			<p>Ringkasan kegiatan dan status terbaru</p>
		</div>
	</div>

	<!-- Welcome Banner with Animations -->
	<div class="greeting-hero">
		<div class="greeting-wave" aria-hidden="true">
			<span class="greeting-wave-icon">👋</span>
		</div>
		<div>
			<div class="greeting-title">Halo, {{ strtolower($displayName) }}! Selamat datang kembali di TOPKEMA</div>
			<div class="greeting-sub">Semoga harimu lancar dan penuh ide untuk kegiatan berikutnya</div>
		</div>
	</div>

	<!-- Summary Cards -->
	<div class="dashboard-summary-grid">
		@foreach ($summaryCards as $card)
			<div class="dashboard-card summary-card min-h-[112px] rounded-2xl bg-white px-5 py-4 shadow-sm">
				<div class="flex items-center justify-between gap-3">
					<div>
						<p class="text-sm font-semibold leading-tight text-slate-500">{{ $card['title'] }}</p>
						<div class="mt-2 flex items-center gap-2">
							<span class="text-2xl font-bold leading-none tracking-tight text-slate-900">{{ $card['count'] }}</span>
						</div>
					</div>
					<div class="rounded-full bg-red-50 p-3 text-red-600">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
							<path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
						</svg>
					</div>
				</div>
				<p class="mt-2 text-xs font-medium text-slate-400">Menunggu verifikasi</p>
			</div>
		@endforeach
	</div>

	<!-- Unified Card for Filters and Table -->
	<div class="dashboard-card main-content-card bg-white rounded-2xl p-6 shadow-sm">
		<form method="GET" action="{{ route('admin.beranda_ormawa') }}" class="dashboard-filter-grid mb-6">
			<div class="flex flex-col gap-2">
				<label class="text-sm font-semibold text-slate-700">Jenis Ormawa</label>
				<div class="relative" style="position: relative;">
					<select name="jenis_ormawa" onchange="this.form.submit()" class="w-full rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-700 shadow-sm outline-none transition-all hover:border-slate-300 focus:border-red-500 cursor-pointer" style="appearance: none; -webkit-appearance: none; padding-top: 0.75rem; padding-bottom: 0.75rem; padding-left: 1.25rem; padding-right: 2.5rem;">
						<option value="">Semua Jenis Ormawa</option>
						<option value="institusi" {{ request('jenis_ormawa') === 'institusi' ? 'selected' : '' }}>Ormawa Institusi</option>
						<option value="prodi" {{ request('jenis_ormawa') === 'prodi' ? 'selected' : '' }}>Ormawa Prodi</option>
					</select>
					<div class="pointer-events-none text-slate-400" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1rem; height: 1rem;">
							<path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
						</svg>
					</div>
				</div>
			</div>
			<div class="flex flex-col gap-2">
				<label class="text-sm font-semibold text-slate-700">Nama Ormawa</label>
				<div class="flex gap-2">
					<input type="text" name="nama_ormawa" value="{{ request('nama_ormawa') }}" placeholder="Cari nama ormawa..." class="flex-1 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-700 shadow-sm outline-none transition-all hover:border-slate-300 focus:border-red-500 placeholder:text-slate-400" style="padding: 0.75rem 1.25rem;">
					<button type="submit" class="rounded-xl bg-red-600 px-4 text-sm font-semibold text-white hover:bg-red-700 transition">Filter</button>
					@if(request('jenis_ormawa') || request('nama_ormawa') || request('status'))
						<a href="{{ route('admin.beranda_ormawa') }}" class="rounded-xl border border-slate-200 px-4 flex items-center text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Reset</a>
					@endif
				</div>
			</div>
		</form>

		<div class="flex items-center justify-between mb-6">
			<div class="flex items-center gap-3">
				<div class="relative">
					<select class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 outline-none transition-all hover:border-slate-300 focus:border-red-500 cursor-pointer shadow-sm" style="appearance: none; -webkit-appearance: none; padding-right: 2.5rem;">
						<option value="5">5</option>
						<option value="10">10</option>
						<option value="20">20</option>
					</select>
					<div class="pointer-events-none text-slate-400" style="position: absolute; right: 0.875rem; top: 50%; transform: translateY(-50%);">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1rem; height: 1rem;">
							<path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
						</svg>
					</div>
				</div>
				<span class="text-sm font-medium text-slate-500">Record per page</span>
			</div>

			<a href="{{ route('admin.beranda_ormawa.export_pdf') }}" target="_blank" rel="noopener noreferrer" class="inline-flex w-fit items-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 hover:shadow-md focus:ring-4 focus:ring-red-500/20">
				<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 -960 960 960" class="h-5 w-5 shrink-0">
					<path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
				</svg>
				Export Data
			</a>
		</div>

		<div class="dashboard-table-wrap overflow-x-auto">
			<table class="min-w-[1200px] w-full border-separate border-spacing-y-3 border-spacing-x-0 text-left text-sm text-slate-700">
				<thead class="bg-slate-100 text-slate-700">
					<tr>
						<th class="px-4 py-3 font-semibold rounded-l-lg text-center">TW</th>
						<th class="px-4 py-3 font-semibold">Ormawa</th>
						<th class="px-4 py-3 font-semibold">Nama Kegiatan</th>
						<th class="px-4 py-3 font-semibold">Resiko</th>
						<th class="px-4 py-3 font-semibold text-center">Waktu Kegiatan</th>
						<th class="px-4 py-3 font-semibold text-right">Besar Ajuan</th>
						<th class="px-4 py-3 font-semibold text-right">Besar Anggaran</th>
						<th class="px-4 py-3 font-semibold text-center">Status</th>
						<th class="px-4 py-3 font-semibold text-center">LPJ Keu</th>
						<th class="px-4 py-3 font-semibold text-center">LPJ Keg</th>
						<th class="px-4 py-3 font-semibold text-center">Publikasi</th>
						<th class="px-4 py-3 font-semibold rounded-r-lg text-center">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($activities as $activity)
						<tr class="group transition-colors hover:bg-slate-50">
							<td class="bg-white px-4 py-4 align-middle border-y border-l border-slate-200 first:rounded-l-xl group-hover:bg-slate-50 text-slate-600 text-center">{{ $activity['tw'] }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 font-medium text-slate-900">{{ $activity['ormawa'] }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-slate-600 leading-tight">
                                <a href="{{ route('admin.detail_kegiatan', $activity['id']) }}" class="transition-colors hover:text-red-600 hover:underline" title="Klik untuk lihat detail">
                                    {{ $activity['nama_kegiatan'] }}
                                </a>
                            </td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-slate-600 text-center">{{ $activity['resiko'] }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-slate-600 whitespace-nowrap text-center">{{ $activity['waktu'] }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-slate-600 text-right">{{ $activity['ajuan'] }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-slate-600 text-right">{{ $activity['anggaran'] }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-center">
								<span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusStyles[$activity['status']] ?? 'bg-slate-100 text-slate-700' }}">
									{{ $activity['status'] }}
								</span>
							</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-center">
								@if($activity['lpj_keu'])
                                    <a href="{{ asset('storage/' . $activity['lpj_keu']) }}" target="_blank" class="text-slate-400 transition hover:text-red-600" title="Download LPJ Keuangan">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 mx-auto">
                                            <path d="M5.25 3A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.784a2.25 2.25 0 0 0-.659-1.591l-4.534-4.535A2.25 2.25 0 0 0 14.216 2.25H5.25zM12 9.75a.75.75 0 0 1 .75.75v3.19l1.22-1.22a.75.75 0 1 1 1.06 1.06l-2.5 2.5a.75.75 0 0 1-1.06 0l-2.5-2.5a.75.75 0 1 1 1.06-1.06l1.22 1.22V10.5a.75.75 0 0 1 .75-.75z" />
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-slate-300 italic">&mdash;</span>
                                @endif
							</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-center">
								@if($activity['lpj_keg'])
                                    <a href="{{ asset('storage/' . $activity['lpj_keg']) }}" target="_blank" class="text-slate-400 transition hover:text-red-600" title="Download LPJ Kegiatan">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 mx-auto">
                                            <path d="M5.25 3A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.784a2.25 2.25 0 0 0-.659-1.591l-4.534-4.535A2.25 2.25 0 0 0 14.216 2.25H5.25zM12 9.75a.75.75 0 0 1 .75.75v3.19l1.22-1.22a.75.75 0 1 1 1.06 1.06l-2.5 2.5a.75.75 0 0 1-1.06 0l-2.5-2.5a.75.75 0 1 1 1.06-1.06l1.22 1.22V10.5a.75.75 0 0 1 .75-.75z" />
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-slate-300 italic">&mdash;</span>
                                @endif
							</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-center">
								<button type="button" class="text-slate-400 transition hover:text-red-600">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 mx-auto">
										<path d="M5.25 3A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.784a2.25 2.25 0 0 0-.659-1.591l-4.534-4.535A2.25 2.25 0 0 0 14.216 2.25H5.25zM12 9.75a.75.75 0 0 1 .75.75v3.19l1.22-1.22a.75.75 0 1 1 1.06 1.06l-2.5 2.5a.75.75 0 0 1-1.06 0l-2.5-2.5a.75.75 0 1 1 1.06-1.06l1.22 1.22V10.5a.75.75 0 0 1 .75-.75z" />
									</svg>
								</button>
							</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-r border-slate-200 last:rounded-r-xl group-hover:bg-slate-50 text-center">
								<div class="flex items-center justify-center gap-3">
									<a href="{{ route('admin.form_verifikasi', ['id' => $activity['id']]) }}?type=ormawa" class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-sky-50 text-sky-700 transition hover:bg-sky-100">
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
											<path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
										</svg>
									</a>
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

@endsection
