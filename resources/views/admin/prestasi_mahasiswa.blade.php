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
	$prestasi = [
		[
			'nim' => '23110401',
			'nama' => 'Melani',
			'prodi' => 'Rekayasa Perangkat Lunak',
			'prestasi' => 'Juara 3',
			'nama_event' => 'Sevent',
			'penyelenggara' => 'HMSE',
			'tingkat' => 'Nasional'
		],
		[
			'nim' => '23110401',
			'nama' => 'Melani',
			'prodi' => 'Rekayasa Perangkat Lunak',
			'prestasi' => 'Juara 3',
			'nama_event' => 'Sevent',
			'penyelenggara' => 'HMSE',
			'tingkat' => 'Nasional'
		],
	];
@endphp

<div class="dashboard-shell flex flex-col gap-6">
	<div class="page-hero">
		<div class="title">
			<h1>Prestasi Mahasiswa</h1>
			<p>Kelola dan pantau data prestasi mahasiswa</p>
		</div>
	</div>

	<div class="dashboard-card bg-white rounded-2xl p-6 shadow-sm">
		<div class="dashboard-filter-grid mb-6">
			<!-- Left side: Prestasi dropdown -->
			<div class="flex flex-col gap-2">
				<label class="text-sm font-semibold text-slate-700">Prestasi</label>
				<div class="relative" style="position: relative;">
					<select class="w-full rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-700 shadow-sm outline-none transition-all hover:border-slate-300 focus:border-red-500 cursor-pointer" style="appearance: none; -webkit-appearance: none; padding-top: 0.75rem; padding-bottom: 0.75rem; padding-left: 1.25rem; padding-right: 2.5rem;">
						<option value="" disabled selected>Semua Prestasi</option>
						<option value="juara1">Juara 1</option>
						<option value="juara2">Juara 2</option>
						<option value="juara3">Juara 3</option>
					</select>
					<div class="pointer-events-none text-slate-400" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1rem; height: 1rem;">
							<path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
						</svg>
					</div>
				</div>
			</div>

			<!-- Right side: Tingkat dropdown and Search input -->
			<div class="flex gap-4">
				<div class="flex flex-col gap-2 flex-1">
					<label class="text-sm font-semibold text-slate-700">Tingkat</label>
					<div class="relative" style="position: relative;">
						<select class="w-full rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-700 shadow-sm outline-none transition-all hover:border-slate-300 focus:border-red-500 cursor-pointer" style="appearance: none; -webkit-appearance: none; padding-top: 0.75rem; padding-bottom: 0.75rem; padding-left: 1.25rem; padding-right: 2.5rem;">
							<option value="" disabled selected>Semua Tingkat</option>
							<option value="internasional">Internasional</option>
							<option value="nasional">Nasional</option>
							<option value="regional">Regional</option>
						</select>
						<div class="pointer-events-none text-slate-400" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);">
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1rem; height: 1rem;">
								<path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
							</svg>
						</div>
					</div>
				</div>
				
				<div class="flex flex-col gap-2 flex-1">
					<label class="text-sm font-semibold text-slate-700">Pencarian</label>
					<div class="relative" style="position: relative;">
						<input type="text" placeholder="Cari nama, nim..." class="w-full rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-700 shadow-sm outline-none transition-all hover:border-slate-300 focus:border-red-500 placeholder:text-slate-400" style="padding-top: 0.75rem; padding-bottom: 0.75rem; padding-left: 1.25rem; padding-right: 2.5rem;">
						<div class="pointer-events-none text-slate-400" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);">
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
								<path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M17 10.5A6.5 6.5 0 1 1 4 10.5a6.5 6.5 0 0 1 13 0Z" />
							</svg>
						</div>
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

			<a href="{{ route('admin.prestasi_mahasiswa.export_pdf') }}" target="_blank" rel="noopener noreferrer" class="inline-flex w-fit items-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 hover:shadow-md focus:ring-4 focus:ring-red-500/20">
				<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 -960 960 960" class="h-5 w-5 shrink-0">
					<path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
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
						<th class="px-4 py-3 font-semibold">Penyelenggara</th>
						<th class="px-4 py-3 font-semibold">Tingkat</th>
						<th class="px-4 py-3 font-semibold rounded-r-lg text-center">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($prestasi as $item)
						<tr class="group transition-colors hover:bg-slate-50">
							<td class="bg-white px-4 py-4 align-middle border-y border-l border-slate-200 first:rounded-l-xl group-hover:bg-slate-50 text-slate-600">{{ $item['nim'] }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 font-medium text-slate-900">{{ $item['nama'] }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-slate-600">{{ $item['prodi'] }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50">
								<span class="inline-flex items-center rounded-md bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20">{{ $item['prestasi'] }}</span>
							</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 leading-5 text-slate-600">{{ $item['nama_event'] }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50 text-slate-600">{{ $item['penyelenggara'] }}</td>
							<td class="bg-white px-4 py-4 align-middle border-y border-slate-200 group-hover:bg-slate-50">
								<span class="inline-flex items-center rounded-md bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700 ring-1 ring-inset ring-sky-600/20">{{ $item['tingkat'] }}</span>
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
