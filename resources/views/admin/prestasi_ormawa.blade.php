@extends('layouts.app')

@section('title', 'Beranda Ormawa')

@section('content')

<style>
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

	.dashboard-card {
		box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
		border: 0;
	}

	.dashboard-filter-card {
		min-height: 86px;
		display: flex;
		flex-direction: column;
		justify-content: center;
	}

	.dashboard-filter-surface {
		background: transparent;
		border: 0;
		box-shadow: none;
		padding: 0;
	}

	.dashboard-table-wrap {
		scrollbar-width: thin;
	}

	.dashboard-table-wrap::-webkit-scrollbar {
		height: 10px;
	}

	.dashboard-table-wrap::-webkit-scrollbar-thumb {
		background: rgb(203 213 225);
		border-radius: 9999px;
	}

	@media (max-width: 768px) {
		.dashboard-summary-grid,
		.dashboard-filter-grid {
			grid-template-columns: 1fr;
		}
	}
</style>

@php
	$summaryCards = [
		['title' => 'Proposal Kegiatan', 'count' => 0, 'color' => 'text-red-600'],
		['title' => 'LPJ Kegiatan', 'count' => 0, 'color' => 'text-red-600'],
		['title' => 'Lapor Prestasi', 'count' => 0, 'color' => 'text-red-600'],
	];

	$activities = [
		[
			'tw' => '1',
			'ormawa' => 'Manggala',
			'nama_kegiatan' => 'Buka Bersama Manggala',
			'resiko' => 'Sedang',
			'waktu' => '17 Maret 2026',
			'ajuan' => 'Rp 200.000',
			'anggaran' => 'Rp 200.000',
			'status' => 'Selesai',
		],
		[
			'tw' => '1',
			'ormawa' => 'Manggala',
			'nama_kegiatan' => 'Buka Bersama Manggala',
			'resiko' => 'Tinggi',
			'waktu' => '17 Maret 2026',
			'ajuan' => 'Rp 200.000',
			'anggaran' => 'Rp 200.000',
			'status' => 'Pencairan',
		],
		[
			'tw' => '1',
			'ormawa' => 'Manggala',
			'nama_kegiatan' => 'Buka Bersama Manggala',
			'resiko' => 'Sedang',
			'waktu' => '17 Maret 2026',
			'ajuan' => 'Rp 200.000',
			'anggaran' => 'Rp 200.000',
			'status' => 'Acc',
		],
		[
			'tw' => '1',
			'ormawa' => 'Manggala',
			'nama_kegiatan' => 'Buka Bersama Manggala',
			'resiko' => 'Rendah',
			'waktu' => '17 Maret 2026',
			'ajuan' => 'Rp 200.000',
			'anggaran' => 'Rp 200.000',
			'status' => 'Revisi',
		],
		[
			'tw' => '1',
			'ormawa' => 'Manggala',
			'nama_kegiatan' => 'Buka Bersama Manggala',
			'resiko' => 'Sedang',
			'waktu' => '17 Maret 2026',
			'ajuan' => 'Rp 200.000',
			'anggaran' => 'Rp 200.000',
			'status' => 'Ajuan baru',
		],
	];

	$statusStyles = [
		'Selesai' => 'bg-emerald-100 text-emerald-700',
		'Pencairan' => 'bg-amber-100 text-amber-700',
		'Acc' => 'bg-sky-100 text-sky-700',
		'Revisi' => 'bg-rose-100 text-rose-700',
		'Ajuan baru' => 'bg-slate-100 text-slate-700',
	];
@endphp

	<div class="dashboard-shell flex flex-col gap-8">
	<div class="dashboard-summary-grid">
		@foreach ($summaryCards as $card)
			<div class="dashboard-card min-h-[112px] rounded-2xl bg-white px-5 py-4">
				<div class="flex items-center justify-between gap-3">
					<div>
						<p class="text-sm font-medium leading-tight text-slate-500">{{ $card['title'] }}</p>
						<div class="mt-2 flex items-center gap-2">
							<span class="text-2xl font-semibold leading-none tracking-tight text-slate-900">{{ $card['count'] }}</span>
						</div>
					</div>
					<div class="rounded-full bg-red-50 p-2.5 {{ $card['color'] }}">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
							<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H8.25m0 12.75h7.5m-7.5 3h4.5M5.625 2.25h5.603c.895 0 1.754.356 2.386.988l4.773 4.773c.632.632.988 1.49.988 2.386v8.853a2.25 2.25 0 01-2.25 2.25H5.625a2.25 2.25 0 01-2.25-2.25V4.5a2.25 2.25 0 012.25-2.25z" />
						</svg>
					</div>
				</div>
				<p class="mt-2 text-xs text-slate-500">Menunggu verifikasi</p>
			</div>
		@endforeach
	</div>

    </br>

	<div class="dashboard-filter-grid">
		<div class="dashboard-filter-card dashboard-filter-surface">
			<label class="mb-3 block text-base font-medium leading-none text-slate-700">Jenis Ormawa</label>
			<button type="button" class="flex w-full items-center justify-between border-b border-slate-300 pb-4 pt-1 text-left text-slate-900">
				<span>Ormawa Institusi</span>
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 text-slate-700">
					<path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
				</svg>
			</button>
		</div>

		<div class="dashboard-filter-card dashboard-filter-surface">
			<label class="mb-3 block text-base font-medium leading-none text-slate-700">Nama Ormawa</label>
			<button type="button" class="flex w-full items-center justify-between border-b border-slate-300 pb-4 pt-1 text-left text-slate-900">
				<span>Manggala</span>
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 text-slate-700">
					<path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
				</svg>
			</button>
		</div>
	</div>

    </br>

	<div class="dashboard-card overflow-hidden rounded-2xl bg-white">
		<div class="dashboard-table-wrap overflow-x-auto">
			<table class="min-w-[1350px] w-full border-separate border-spacing-y-3 border-spacing-x-0 text-left text-sm text-slate-700">
				<thead class="bg-slate-100 text-slate-700">
					<tr>
						<th class="px-4 py-3 font-semibold">TW</th>
						<th class="px-4 py-3 font-semibold">Ormawa</th>
						<th class="px-4 py-3 font-semibold">Nama Kegiatan</th>
						<th class="px-4 py-3 font-semibold">Resiko</th>
						<th class="px-4 py-3 font-semibold">Waktu Kegiatan</th>
						<th class="px-4 py-3 font-semibold">Besar Ajuan</th>
						<th class="px-4 py-3 font-semibold">Besar Anggaran</th>
						<th class="px-4 py-3 font-semibold">Status</th>
						<th class="px-4 py-3 font-semibold">LPJ Keuangan</th>
						<th class="px-4 py-3 font-semibold">LPJ Kegiatan</th>
						<th class="px-4 py-3 font-semibold">Publikasi</th>
						<th class="px-4 py-3 font-semibold">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($activities as $activity)
						<tr>
							<td class="bg-white px-4 py-4 align-middle first:rounded-l-xl">{{ $activity['tw'] }}</td>
							<td class="bg-white px-4 py-4 align-middle">{{ $activity['ormawa'] }}</td>
							<td class="bg-white px-4 py-4 align-middle leading-5">{{ $activity['nama_kegiatan'] }}</td>
							<td class="bg-white px-4 py-4 align-middle">{{ $activity['resiko'] }}</td>
							<td class="bg-white px-4 py-4 align-middle whitespace-nowrap">{{ $activity['waktu'] }}</td>
							<td class="bg-white px-4 py-4 align-middle whitespace-nowrap">{{ $activity['ajuan'] }}</td>
							<td class="bg-white px-4 py-4 align-middle whitespace-nowrap">{{ $activity['anggaran'] }}</td>
							<td class="bg-white px-4 py-4 align-middle">
								<span class="inline-flex min-w-[110px] items-center justify-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusStyles[$activity['status']] ?? 'bg-slate-100 text-slate-700' }}">
									{{ $activity['status'] }}
								</span>
							</td>
							<td class="bg-white px-4 py-4 align-middle">
								<button type="button" class="inline-flex items-center justify-center text-slate-900 transition hover:text-red-600" aria-label="Lihat LPJ Keuangan">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
										<path d="M5.25 3A2.25 2.25 0 003 5.25v13.5A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V8.784a2.25 2.25 0 00-.659-1.591l-4.534-4.535A2.25 2.25 0 0014.216 2.25H5.25zM12 9.75a.75.75 0 01.75.75v3.19l1.22-1.22a.75.75 0 111.06 1.06l-2.5 2.5a.75.75 0 01-1.06 0l-2.5-2.5a.75.75 0 111.06-1.06l1.22 1.22V10.5a.75.75 0 01.75-.75z" />
									</svg>
								</button>
							</td>
							<td class="bg-white px-4 py-4 align-middle">
								<button type="button" class="inline-flex items-center justify-center text-slate-900 transition hover:text-red-600" aria-label="Lihat LPJ Kegiatan">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
										<path d="M5.25 3A2.25 2.25 0 003 5.25v13.5A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V8.784a2.25 2.25 0 00-.659-1.591l-4.534-4.535A2.25 2.25 0 0014.216 2.25H5.25zM12 9.75a.75.75 0 01.75.75v3.19l1.22-1.22a.75.75 0 111.06 1.06l-2.5 2.5a.75.75 0 01-1.06 0l-2.5-2.5a.75.75 0 111.06-1.06l1.22 1.22V10.5a.75.75 0 01.75-.75z" />
									</svg>
								</button>
							</td>
							<td class="bg-white px-4 py-4 align-middle">
								<button type="button" class="inline-flex items-center justify-center text-slate-900 transition hover:text-red-600" aria-label="Lihat Publikasi">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
										<path d="M5.25 3A2.25 2.25 0 003 5.25v13.5A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V8.784a2.25 2.25 0 00-.659-1.591l-4.534-4.535A2.25 2.25 0 0014.216 2.25H5.25zM12 9.75a.75.75 0 01.75.75v3.19l1.22-1.22a.75.75 0 111.06 1.06l-2.5 2.5a.75.75 0 01-1.06 0l-2.5-2.5a.75.75 0 111.06-1.06l1.22 1.22V10.5a.75.75 0 01.75-.75z" />
									</svg>
								</button>
							</td>
							<td class="bg-white px-4 py-4 align-middle last:rounded-r-xl">
								<div class="flex items-center gap-3">
									<a href="{{ route('admin.form_verifikasi') }}" class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-sky-50 text-sky-700 transition hover:bg-sky-100" aria-label="Edit">
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
											<path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
										</svg>
									</a>
									<button type="button" onclick="if (confirm('Hapus data ini?')) { this.closest('tr').remove(); }" class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-rose-50 text-rose-700 transition hover:bg-rose-100" aria-label="Hapus">
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
											<path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.22a51.964 51.964 0 00-3.32 0c-1.18.056-2.09 1.04-2.09 2.22v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
										</svg>
									</button>
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
