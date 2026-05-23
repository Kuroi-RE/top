@extends('layouts.app')

@section('title', 'Prestasi Ormawa')

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
		box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
		border: 0;
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
		.dashboard-summary-grid,
		.dashboard-filter-grid {
			grid-template-columns: 1fr;
		}
	}
</style>

@php
	$summaryCards = [
		['title' => 'Proposal Ajuan', 'count' => 0, 'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H8.25m0 12.75h7.5m-7.5 3h4.5M5.625 2.25h5.603c.895 0 1.754.356 2.386.988l4.773 4.773c.632.632.988 1.49.988 2.386v8.853a2.25 2.25 0 01-2.25 2.25H5.625a2.25 2.25 0 01-2.25-2.25V4.5a2.25 2.25 0 012.25-2.25z'],
		['title' => 'LPJ Kegiatan', 'count' => 0, 'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H8.25m0 12.75h7.5m-7.5 3h4.5M5.625 2.25h5.603c.895 0 1.754.356 2.386.988l4.773 4.773c.632.632.988 1.49.988 2.386v8.853a2.25 2.25 0 01-2.25 2.25H5.625a2.25 2.25 0 01-2.25-2.25V4.5a2.25 2.25 0 012.25-2.25z'],
		['title' => 'Lapor Prestasi', 'count' => 0, 'icon' => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z'],
	];

	$activities = [
		[
			'tahun' => '1',
			'nim' => '2311104001',
			'nama' => 'Tri Mylani',
			'prodi' => 'S1 Rekayasa Perangkat Lunak',
			'prestasi' => '17 Maret 2026',
			'nama_event' => 'Lomba Essay',
			'penyelenggara' => 'Telkom University',
			'tingkat' => 'Nasional',
			'klaster' => 'I',
		],
		[
			'tahun' => '1',
			'nim' => '2311104003',
			'nama' => 'Martryatus Sofia',
			'prodi' => 'S1 Rekayasa Perangkat Lunak',
			'prestasi' => '17 Maret 2026',
			'nama_event' => 'Lomba Hackathon',
			'penyelenggara' => 'Telkom University',
			'tingkat' => 'Internasional',
			'klaster' => 'II',
		],
		[
			'tahun' => '1',
			'nim' => '2311103005',
			'nama' => 'Viona Aziz Syahputri',
			'prodi' => 'S1 Rekayasa Perangkat Lunak',
			'prestasi' => '17 Maret 2026',
			'nama_event' => 'Lomba UI/UX',
			'penyelenggara' => 'Telkom University',
			'tingkat' => 'Regional',
			'klaster' => 'III',
		],
		[
			'tahun' => '1',
			'nim' => '2311104006',
			'nama' => 'Kelvin Ferdinan',
			'prodi' => 'S1 Rekayasa Perangkat Lunak',
			'prestasi' => '17 Maret 2026',
			'nama_event' => 'Cerpen',
			'penyelenggara' => 'Telkom University',
			'tingkat' => 'Nasional',
			'klaster' => 'III',
		],
		[
			'tahun' => '1',
			'nim' => '2311107007',
			'nama' => 'Satria Ramadhan',
			'prodi' => 'S1 Rekayasa Perangkat Lunak',
			'prestasi' => '17 Maret 2026',
			'nama_event' => 'LKTI',
			'penyelenggara' => 'Telkom University',
			'tingkat' => 'Regional',
			'klaster' => 'I',
		],
	];
@endphp

<div class="dashboard-shell flex flex-col gap-6">
	<div class="page-hero">
		<div class="title">
			<h1>Prestasi Ormawa</h1>
			<p>Kelola dan pantau data prestasi mahasiswa berdasarkan ormawa</p>
		</div>
	</div>

	<!-- Summary Cards -->
	<div class="dashboard-summary-grid">
		@foreach ($summaryCards as $card)
			<div class="dashboard-card min-h-[112px] rounded-2xl bg-white px-5 py-4 shadow-sm">
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
	<div class="dashboard-card bg-white rounded-2xl p-6 shadow-sm">
		<div class="dashboard-filter-grid mb-6">
			<!-- Left side: Jenis Ormawa -->
			<div class="flex flex-col gap-2">
				<label class="text-sm font-semibold text-slate-700">Jenis Ormawa</label>
				<div class="relative" style="position: relative;">
					<select class="w-full rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-700 shadow-sm outline-none transition-all hover:border-slate-300 focus:border-red-500 cursor-pointer" style="appearance: none; -webkit-appearance: none; padding-top: 0.75rem; padding-bottom: 0.75rem; padding-left: 1.25rem; padding-right: 2.5rem;">
						<option value="" disabled selected>Ormawa Institusi</option>
						<option value="institusi">Ormawa Institusi</option>
						<option value="fakultas">Ormawa Fakultas</option>
						<option value="prodi">Ormawa Prodi</option>
					</select>
					<div class="pointer-events-none text-slate-400" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1rem; height: 1rem;">
							<path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
						</svg>
					</div>
				</div>
			</div>

			<!-- Right side: Nama Ormawa -->
			<div class="flex flex-col gap-2">
				<label class="text-sm font-semibold text-slate-700">Nama Ormawa</label>
				<div class="relative" style="position: relative;">
					<select class="w-full rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-700 shadow-sm outline-none transition-all hover:border-slate-300 focus:border-red-500 cursor-pointer" style="appearance: none; -webkit-appearance: none; padding-top: 0.75rem; padding-bottom: 0.75rem; padding-left: 1.25rem; padding-right: 2.5rem;">
						<option value="" disabled selected>Manggala</option>
						<option value="manggala">Manggala</option>
						<option value="bem">BEM</option>
						<option value="dpm">DPM</option>
					</select>
					<div class="pointer-events-none text-slate-400" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1rem; height: 1rem;">
							<path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
						</svg>
					</div>
				</div>
			</div>
		</div>

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

			<a href="{{ route('admin.prestasi_ormawa.export_pdf') }}" target="_blank" rel="noopener noreferrer" class="inline-flex w-fit items-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 hover:shadow-md focus:ring-4 focus:ring-red-500/20">
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
						<th class="px-4 py-3 font-semibold rounded-l-lg">Tahun</th>
						<th class="px-4 py-3 font-semibold">NIM</th>
						<th class="px-4 py-3 font-semibold">Nama</th>
						<th class="px-4 py-3 font-semibold">Prodi</th>
						<th class="px-4 py-3 font-semibold">Prestasi Dicapai</th>
						<th class="px-4 py-3 font-semibold">Nama Event</th>
						<th class="px-4 py-3 font-semibold">Penyelenggara</th>
						<th class="px-4 py-3 font-semibold">Tingkat</th>
						<th class="px-4 py-3 font-semibold">Klaster</th>
						<th class="px-4 py-3 font-semibold rounded-r-lg text-center">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($activities as $item)
						<tr class="group transition-colors hover:bg-slate-50">
							<td class="bg-white px-4 py-4 align-middle border-y border-l border-slate-200 first:rounded-l-xl group-hover:bg-slate-50 text-slate-600 text-center">{{ $item['tahun'] }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-slate-600">{{ $item['nim'] }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 font-medium text-slate-900">{{ $item['nama'] }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-slate-600">{{ $item['prodi'] }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-slate-600">{{ $item['prestasi'] }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-slate-600">{{ $item['nama_event'] }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-slate-600">{{ $item['penyelenggara'] }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50">
								<span class="inline-flex items-center rounded-md bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700 ring-1 ring-inset ring-sky-600/20">{{ $item['tingkat'] }}</span>
							</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-center">
								<span class="inline-flex items-center rounded-md bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20">{{ $item['klaster'] }}</span>
							</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-r border-slate-200 last:rounded-r-xl group-hover:bg-slate-50">
								<div class="flex items-center justify-center gap-3">
									<a href="{{ route('admin.detail_prestasi') }}" class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-sky-50 text-sky-700 transition hover:bg-sky-100" aria-label="Edit">
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
