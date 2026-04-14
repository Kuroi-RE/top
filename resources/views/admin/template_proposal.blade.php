@extends('layouts.app')

@section('title', 'Template Dokumen')

@section('content')
<style>
	.proposal-shell {
		max-width: 1040px;
		margin: 0 auto;
	}

	.proposal-toolbar {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 20px;
		margin-bottom: 26px;
		flex-wrap: wrap;
	}

	.proposal-search {
		width: 150px;
		display: flex;
		align-items: center;
		gap: 8px;
		border-bottom: 1px solid rgba(55, 65, 81, 0.2);
		padding-bottom: 8px;
	}

	.proposal-search input {
		width: 100%;
		border: 0;
		outline: none;
		background: transparent;
		font-size: 0.95rem;
		color: #111827;
	}

	.proposal-perpage {
		display: inline-flex;
		align-items: center;
		gap: 10px;
		margin-top: 10px;
		font-size: 0.92rem;
		color: #374151;
	}

	.proposal-perpage select {
		width: 54px;
		border: 1px solid rgba(55, 65, 81, 0.2);
		border-radius: 6px;
		background: #f3f4f6;
		padding: 4px 8px;
		color: #111827;
		outline: none;
	}

	.proposal-add-btn {
		display: inline-flex;
		align-items: center;
		gap: 10px;
		border: 0;
		border-radius: 9999px;
		background: #c0161f;
		color: #fff;
		padding: 14px 24px;
		font-size: 1rem;
		font-weight: 600;
		box-shadow: 0 10px 20px rgba(192, 22, 31, 0.24);
		transition: 0.2s ease;
	}

	.proposal-add-btn:hover {
		background: #a6121a;
	}

	.proposal-table {
		width: 100%;
		border-collapse: collapse;
		font-size: 0.9rem;
		color: rgb(31 41 55);
	}

	.proposal-table th,
	.proposal-table td {
		padding: 12px 16px;
		border-bottom: 1px solid rgba(17, 24, 39, 0.08);
	}

	.proposal-table thead th {
		background: rgba(209, 213, 219, 0.85);
		font-weight: 600;
	}

	.proposal-card {
		overflow: hidden;
		background: #fff;
		box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
	}

	.doc-icon {
		width: 18px;
		height: 18px;
		color: #111827;
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
		background: transparent;
		border: 0;
		padding: 0;
		cursor: pointer;
	}

	.action-edit {
		color: #0f63c7;
	}

	.action-delete {
		color: #c01824;
	}
</style>

@php
	$templates = [
		['name' => 'Buka bersama Manggala'],
		['name' => 'Buka bersama Manggala'],
	];
@endphp

<div class="proposal-shell">
	<div class="proposal-toolbar">
		<div>
			<div class="proposal-search">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 text-gray-700">
					<path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M17 10.5A6.5 6.5 0 1 1 4 10.5a6.5 6.5 0 0 1 13 0Z" />
				</svg>
				<input type="text" placeholder="Search...">
			</div>

			<div class="proposal-perpage">
				<select>
					<option>5</option>
					<option>10</option>
					<option>20</option>
				</select>
				<span>Record per page</span>
			</div>
		</div>

		<a href="{{ route('admin.input_template_dokumen') }}" class="proposal-add-btn">
			<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
				<path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5" />
			</svg>
			<span>Tambah Data</span>
		</a>
	</div>

	<div class="proposal-card">
		<table class="proposal-table">
			<thead>
				<tr>
					<th class="text-left">Nama Kegiatan</th>
					<th class="text-center">Dokumen</th>
					<th class="text-center">Aksi</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($templates as $template)
					<tr>
						<td>{{ $template['name'] }}</td>
						<td class="text-center">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="doc-icon inline-block">
								<path d="M5.25 3A2.25 2.25 0 0 0 3 5.25v13.5A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V8.784a2.25 2.25 0 0 0-.659-1.591l-4.534-4.535A2.25 2.25 0 0 0 14.216 2.25H5.25Zm7.5 6.75a.75.75 0 0 1 .75.75v3.19l1.22-1.22a.75.75 0 1 1 1.06 1.06l-2.5 2.5a.75.75 0 0 1-1.06 0l-2.5-2.5a.75.75 0 1 1 1.06-1.06l1.22 1.22V10.5a.75.75 0 0 1 .75-.75Z" />
							</svg>
						</td>
						<td class="text-center">
							<div class="action-group">
								<button type="button" class="action-btn action-edit" aria-label="Edit">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="h-4 w-4">
										<path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897z" />
									</svg>
								</button>
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
