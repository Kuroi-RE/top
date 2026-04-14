@extends('layouts.kema')

@section('title', 'Detail Prestasi')

@section('content')
<style>
	.detail-shell {
		max-width: 1080px;
		margin: 0 auto;
	}

	.detail-card {
		border-radius: 14px;
		background: #ffffff;
		border: 1px solid #e0e0e0;
		padding: 32px 26px 34px;
		box-shadow: 0 12px 24px rgb(15 23 42 / 0.08);
	}

	.detail-title {
		margin: 0;
		color: #111827;
		font-size: 2.2rem;
		line-height: 1.2;
		font-weight: 700;
	}

	.detail-grid {
		margin-top: 26px;
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 34px 68px;
	}

	.detail-column {
		display: flex;
		flex-direction: column;
		gap: 26px;
	}

	.detail-section {
		display: flex;
		flex-direction: column;
		gap: 12px;
	}

	.detail-section-title {
		margin: 0;
		color: #212121;
		font-size: 1.1rem;
		line-height: 1.35;
		font-weight: 700;
	}

	.detail-row {
		display: grid;
		grid-template-columns: minmax(160px, 1fr) auto;
		align-items: start;
		column-gap: 20px;
		row-gap: 6px;
		color: #3f3f46;
		font-size: 0.95rem;
		line-height: 1.4;
	}

	.detail-label {
		margin: 0;
		color: #303030;
		word-break: break-word;
	}

	.detail-value {
		margin: 0;
		color: #575757;
		white-space: nowrap;
		text-align: right;
	}

	.detail-value.empty {
		color: transparent;
		user-select: none;
	}

	@media (max-width: 980px) {
		.detail-grid {
			grid-template-columns: 1fr;
			gap: 28px;
		}

		.detail-card {
			padding: 26px 20px 28px;
		}

		.detail-title {
			font-size: 2rem;
		}
	}

	@media (max-width: 520px) {
		.detail-row {
			grid-template-columns: 1fr;
		}

		.detail-value {
			text-align: left;
			white-space: normal;
		}
	}
</style>

@php
	$leftSections = [
		[
			'title' => 'Biodata',
			'rows' => [
				['label' => 'Email', 'value' => 'Rendah'],
				['label' => 'NIM', 'value' => 'Rendah'],
				['label' => 'Nama', 'value' => 'Rendah'],
				['label' => 'Program Studi', 'value' => 'Rendah'],
				['label' => 'Mewakili Ormawa HIMA/ Institusi?', 'value' => 'Rendah'],
				['label' => '.', 'value' => '.'],
				['label' => '.', 'value' => '.'],
			],
		],
		[
			'title' => 'Capaian Prestasi',
			'rows' => [
				['label' => 'Juara', 'value' => 'Rendah'],
				['label' => 'Kategori', 'value' => 'Rendah'],
				['label' => 'Nim Anggota 1', 'value' => 'Rendah'],
				['label' => 'Nama Anggota 1', 'value' => 'Rendah'],
				['label' => 'Prodi Anggota 1', 'value' => 'Rendah'],
				['label' => 'Nim Anggota 1', 'value' => 'Rendah'],
				['label' => 'Nama Anggota 1', 'value' => 'Rendah'],
				['label' => 'Prodi Anggota 1', 'value' => 'Rendah'],
			],
		],
		[
			'title' => 'Evidence',
			'rows' => [
				['label' => 'Surat Tugas Mahasiswa', 'value' => 'Rendah'],
				['label' => 'Sertifikat Juara', 'value' => 'Rendah'],
				['label' => 'Foto Penyerahan Penghargaan', 'value' => 'Rendah'],
				['label' => 'Bukti Keikutsertaan Peserta', 'value' => 'Rendah'],
				['label' => 'URL Informasi Kompetisi', 'value' => 'Rendah'],
				['label' => 'Foto Formal', 'value' => 'Rendah'],
				['label' => 'Nama Anggota 1', 'value' => 'Rendah'],
				['label' => 'Prodi Anggota 1', 'value' => 'Rendah'],
			],
		],
	];

	$rightSections = [
		[
			'title' => 'Detail Kompetisi',
			'rows' => [
				['label' => 'Nama Kompetisi', 'value' => 'Rendah'],
				['label' => 'Penyelenggara', 'value' => 'Rendah'],
				['label' => 'Pelaksanaan', 'value' => 'Rendah'],
				['label' => 'Waktu Kompetisi', 'value' => 'Rendah'],
				['label' => 'Tanggal Pengumuman', 'value' => 'Rendah'],
				['label' => 'Tingkat Kompetisi', 'value' => 'Rendah'],
				['label' => 'Klaster', 'value' => 'Rendah'],
			],
		],
		[
			'title' => 'Informasi Dosen Pembimbing',
			'rows' => [
				['label' => 'Apakah ada dosen pembimbing?', 'value' => 'Rendah'],
				['label' => 'Nama Dosen Pembimbing', 'value' => 'Rendah'],
				['label' => 'NIDN', 'value' => 'Rendah'],
				['label' => 'NIP', 'value' => 'Rendah'],
				['label' => 'Prodi Dosen', 'value' => 'Rendah'],
				['label' => 'Surat Tugas Pembimbing', 'value' => 'Rendah'],
				['label' => 'Waktu Kompetisi', 'value' => 'Rendah'],
				['label' => '.', 'value' => '.'],
			],
		],
		[
			'title' => 'Informasi Rekening',
			'rows' => [
				['label' => 'Nomor Rekening', 'value' => 'Rendah'],
				['label' => 'Pemilik Bank', 'value' => 'Rendah'],
				['label' => 'Nama Bank', 'value' => 'Rendah'],
				['label' => 'Scan Buku Tabungan', 'value' => 'Rendah'],
				['label' => 'Scan KTP', 'value' => 'Rendah'],
				['label' => 'Scan KTM', 'value' => 'Rendah'],
				['label' => 'Data yang saya unggah adalah benar jika dikemudian hari ditemukan kekeliruan atau data yang di inputkan keliru saya seiap mnerima sanki', 'value' => null],
				['label' => '.', 'value' => '.'],
			],
		],
	];
@endphp

<div class="detail-shell">
	<div class="detail-card">
		<h1 class="detail-title">Form Verifikasi</h1>

		<div class="detail-grid">
			<div class="detail-column">
				@foreach ($leftSections as $section)
					<section class="detail-section">
						<h2 class="detail-section-title">{{ $section['title'] }}</h2>

						@foreach ($section['rows'] as $row)
							<div class="detail-row">
								<p class="detail-label">{{ $row['label'] }}</p>
								<p class="detail-value {{ $row['value'] === null ? 'empty' : '' }}">{{ $row['value'] ?? '-' }}</p>
							</div>
						@endforeach
					</section>
				@endforeach
			</div>

			<div class="detail-column">
				@foreach ($rightSections as $section)
					<section class="detail-section">
						<h2 class="detail-section-title">{{ $section['title'] }}</h2>

						@foreach ($section['rows'] as $row)
							<div class="detail-row">
								<p class="detail-label">{{ $row['label'] }}</p>
								<p class="detail-value {{ $row['value'] === null ? 'empty' : '' }}">{{ $row['value'] ?? '-' }}</p>
							</div>
						@endforeach
					</section>
				@endforeach
			</div>
		</div>
	</div>
</div>
@endsection
