@extends('layouts.kema')

@section('title', 'Prestasi Mahasiswa')

@section('content')
<style>
	.prestasi-shell {
		max-width: 1200px;
		margin: 0 auto;
	}

	.prestasi-filters-top {
		display: flex;
		align-items: flex-end;
		justify-content: space-between;
		gap: 40px;
		margin-bottom: 20px;
	}

	.prestasi-filter-left {
		display: flex;
		flex-direction: column;
		gap: 8px;
	}

	.prestasi-filter-right {
		display: flex;
		align-items: flex-end;
		gap: 24px;
	}

	.prestasi-filter-item {
		display: flex;
		flex-direction: column;
		gap: 8px;
	}

	.prestasi-filter-label {
		font-size: 0.9rem;
		color: #374151;
		font-weight: 500;
	}

	.prestasi-filter-control {
		display: flex;
		align-items: center;
		gap: 8px;
		border-bottom: 1px solid rgba(55, 65, 81, 0.3);
		padding-bottom: 6px;
		min-width: 180px;
		position: relative;
	}

	.prestasi-filter-control select {
		border: none;
		outline: none;
		background: transparent;
		color: #111827;
		font-size: 0.95rem;
		flex: 1;
		appearance: none;
		padding-right: 20px;
		cursor: pointer;
	}

	.prestasi-filter-control::after {
		content: '';
		position: absolute;
		right: 0;
		width: 16px;
		height: 16px;
		background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>') no-repeat center;
		background-size: contain;
		pointer-events: none;
		color: #374151;
	}

	.prestasi-search-wrapper {
		display: flex;
		flex-direction: column;
		gap: 8px;
	}

	.prestasi-search-label {
		font-size: 0.9rem;
		color: #374151;
		font-weight: 500;
	}

	.prestasi-search {
		display: flex;
		align-items: center;
		gap: 8px;
		border-bottom: 1px solid rgba(55, 65, 81, 0.2);
		padding-bottom: 6px;
		min-width: 250px;
	}

	.prestasi-search input {
		width: 100%;
		border: 0;
		outline: none;
		background: transparent;
		font-size: 0.95rem;
		color: #111827;
	}

	.prestasi-filters-bottom {
		display: flex;
		align-items: center;
		gap: 20px;
		margin-bottom: 26px;
	}

	.prestasi-perpage {
		display: inline-flex;
		align-items: center;
		gap: 10px;
		font-size: 0.92rem;
		color: #374151;
	}

	.prestasi-perpage select {
		width: 60px;
		border: 1px solid rgba(55, 65, 81, 0.2);
		border-radius: 6px;
		background: #f3f4f6;
		padding: 4px 8px;
		color: #111827;
		outline: none;
	}

	.prestasi-table {
		width: 100%;
		border-collapse: collapse;
		font-size: 0.9rem;
		color: rgb(31 41 55);
	}

	.prestasi-table th,
	.prestasi-table td {
		padding: 12px 16px;
		border-bottom: 1px solid rgba(17, 24, 39, 0.08);
	}

	.prestasi-table thead th {
		background: rgba(209, 213, 219, 0.85);
		font-weight: 600;
		text-align: left;
	}

	.prestasi-card {
		overflow: hidden;
		background: #fff;
		box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
	}

	.action-group {
		display: inline-flex;
		align-items: center;
		gap: 10px;
	}

	.action-separator {
		width: 1px;
		height: 18px;
		background: rgba(17, 24, 39, 0.28);
	}

	.action-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		background: transparent;
		border: 0;
		padding: 0;
		cursor: pointer;
		text-decoration: none;
	}

	.action-edit {
		color: #0f63c7;
	}

	.action-delete {
		color: #c01824;
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

<div class="prestasi-shell">
	<!-- Top Filter Row: Prestasi (left), Tingkat + Search (right) -->
	<div class="prestasi-filters-top">
		<div class="prestasi-filter-left">
			<div class="prestasi-filter-item">
				<label class="prestasi-filter-label">Prestasi</label>
				<div class="prestasi-filter-control">
					<select>
						<option value="">Semua Prestasi</option>
						<option value="juara1">Juara 1</option>
						<option value="juara2">Juara 2</option>
						<option value="juara3">Juara 3</option>
						<option value="peserta">Peserta</option>
					</select>
				</div>
			</div>
		</div>

		<div class="prestasi-filter-right">
			<div class="prestasi-filter-item">
				<label class="prestasi-filter-label">Tingkat</label>
				<div class="prestasi-filter-control">
					<select>
						<option value="">Semua Tingkat</option>
						<option value="internasional">Internasional</option>
						<option value="nasional">Nasional</option>
						<option value="regional">Regional</option>
						<option value="lokal">Lokal</option>
					</select>
				</div>
			</div>

			<div class="prestasi-search-wrapper">
				<label class="prestasi-search-label">Search</label>
				<div class="prestasi-search">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 text-gray-700">
						<path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M17 10.5A6.5 6.5 0 1 1 4 10.5a6.5 6.5 0 0 1 13 0Z" />
					</svg>
					<input type="text" placeholder="">
				</div>
			</div>
		</div>
	</div>

	<!-- Bottom Filter Row: Record per page (left) -->
	<div class="prestasi-filters-bottom">
		<div class="prestasi-perpage">
			<select>
				<option>5</option>
				<option>10</option>
				<option>20</option>
			</select>
			<span>Record per page</span>
		</div>
	</div>

	<div class="prestasi-card">
		<table class="prestasi-table">
			<thead>
				<tr>
					<th>NIM</th>
					<th>Nama</th>
					<th>Prodi</th>
					<th>Prestasi</th>
					<th>Nama Event</th>
					<th>Penyelenggara</th>
					<th>Tingkat</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($prestasi as $item)
					<tr>
						<td>{{ $item['nim'] }}</td>
						<td>{{ $item['nama'] }}</td>
						<td>{{ $item['prodi'] }}</td>
						<td>{{ $item['prestasi'] }}</td>
						<td>{{ $item['nama_event'] }}</td>
						<td>{{ $item['penyelenggara'] }}</td>
						<td>{{ $item['tingkat'] }}</td>
						<td>
							<div class="action-group">
								<a href="{{ route('admin.detail_prestasi') }}" class="action-btn action-edit" aria-label="Lihat Detail Prestasi">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="h-4 w-4">
										<path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897z" />
									</svg>
								</a>
								<span class="action-separator"></span>
								<button type="button" class="action-btn action-delete" aria-label="Hapus">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="h-4 w-4">
										<path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5h12m-10 0 .5 12h7l.5-12m-9 0L7.5 5.25A1.5 1.5 0 0 1 9 4.5h6a1.5 1.5 0 0 1 1.5.75L17 7.5M10 11.25v6m4-6v6" />
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
@endsection
