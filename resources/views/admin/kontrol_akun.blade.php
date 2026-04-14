@extends('layouts.app')

@section('title', 'Kontrol Akun')

@section('content')
<style>
	.control-panel {
		max-width: 1040px;
		margin: 0 auto;
	}

	.control-field {
		flex: 1 1 0;
		min-width: 0;
		border-bottom: 1px solid rgba(55, 65, 81, 0.2);
		padding-bottom: 12px;
	}

	.control-filters {
		display: flex;
		align-items: flex-start;
		gap: 48px;
		flex-wrap: nowrap;
	}

	.control-label {
		display: block;
		margin-bottom: 10px;
		font-size: 0.95rem;
		font-weight: 500;
		color: rgb(55 65 81);
	}

	.control-select-wrap,
	.control-search-wrap {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.control-select {
		appearance: none;
		width: 100%;
		border: none;
		border-bottom: 1px solid rgba(55, 65, 81, 0.25);
		background: transparent;
		padding: 10px 28px 10px 0;
		color: #111827;
		outline: none;
	}

	.control-table {
		width: 100%;
		border-collapse: collapse;
		font-size: 0.9rem;
		color: rgb(31 41 55);
	}

	.control-table th,
	.control-table td {
		padding: 12px 16px;
		border-bottom: 1px solid rgba(17, 24, 39, 0.08);
	}

	.control-table thead th {
		background: rgba(209, 213, 219, 0.85);
		font-weight: 600;
	}

	.toggle {
		appearance: none;
		width: 40px;
		height: 22px;
		border-radius: 9999px;
		background: #d1d5db;
		position: relative;
		cursor: pointer;
		transition: background-color 150ms ease;
		border: 1px solid rgba(17, 24, 39, 0.15);
	}

	.toggle::after {
		content: '';
		position: absolute;
		top: 2px;
		left: 2px;
		width: 16px;
		height: 16px;
		border-radius: 9999px;
		background: #111827;
		transition: transform 150ms ease, background-color 150ms ease;
	}

	.toggle:checked {
		background: #111827;
	}

	.toggle:checked::after {
		transform: translateX(18px);
		background: #f9fafb;
	}

	.search-input {
		width: 100%;
		border: none;
		border-bottom: 1px solid rgba(55, 65, 81, 0.25);
		background: transparent;
		padding: 10px 0;
		color: #111827;
		outline: none;
	}

	.search-input::placeholder {
		color: #6b7280;
	}
</style>

@php
	$organizationOptions = ['Ormawa Prodi', 'Ormawa Institusi'];
	$controlRows = [
		['feature' => 'Monitoring Anggaran', 'role' => 'DPM/BEM', 'enabled' => true],
		['feature' => 'Publikasi Kegiatan', 'role' => 'Ormawa Prodi', 'enabled' => false],
	];
@endphp

<div class="control-panel flex flex-col gap-8">
	<div class="control-filters">
		<div class="control-field">
			<label class="control-label">Ormawa</label>
			<div class="relative">
				<select class="control-select pr-8 text-gray-700">
					@foreach ($organizationOptions as $option)
						<option value="{{ $option }}">{{ $option }}</option>
					@endforeach
				</select>
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="pointer-events-none absolute right-0 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-700">
					<path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
				</svg>
			</div>
		</div>

		<div class="control-field">
			<label class="control-label">Search</label>
			<div class="control-search-wrap">
				<input type="text" class="search-input" placeholder="">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 text-gray-700">
					<path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M17 10.5A6.5 6.5 0 1 1 4 10.5a6.5 6.5 0 0 1 13 0Z" />
				</svg>
			</div>
		</div>
	</div>

	<div class="overflow-hidden rounded-sm bg-white shadow-sm">
		<table class="control-table">
			<thead>
				<tr>
					<th class="w-1/2">Fitur</th>
					<th class="w-1/4">Role</th>
					<th class="w-1/4 text-center">Aksi</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($controlRows as $row)
					<tr class="bg-white">
						<td>{{ $row['feature'] }}</td>
						<td>{{ $row['role'] }}</td>
						<td class="text-center">
							<label class="inline-flex items-center justify-center">
								<input type="checkbox" class="toggle" {{ $row['enabled'] ? 'checked' : '' }}>
							</label>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection
