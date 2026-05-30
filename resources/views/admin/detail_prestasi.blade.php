@extends('layouts.app')

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
    $id = request()->route('id');
    $prestasi = \App\Models\Prestasi::with(['user', 'dokumen', 'anggota', 'dosen'])->find($id);
    
    if (!$prestasi) {
        // Fallback or redirect if not found
        $prestasi = new \App\Models\Prestasi();
    }

    $user = $prestasi->user;
    
	$leftSections = [
		[
			'title' => 'Biodata',
			'rows' => [
				['label' => 'Email', 'value' => $user->email ?? '-'],
				['label' => 'NIM', 'value' => $user->username ?? '-'],
				['label' => 'Nama', 'value' => ($user->nama_depan ?? '') . ' ' . ($user->nama_belakang ?? '')],
				['label' => 'Program Studi', 'value' => $user->prodi ?? '-'],
			],
		],
		[
			'title' => 'Capaian Prestasi',
			'rows' => [
				['label' => 'Juara', 'value' => $prestasi->capaian ?? '-'],
				['label' => 'Kategori', 'value' => $prestasi->kategori ?? '-'],
			],
		],
	];

    // Add members if any
    if ($prestasi->anggota && $prestasi->anggota->count() > 0) {
        $memberRows = [];
        foreach($prestasi->anggota as $idx => $m) {
            $memberRows[] = ['label' => "NIM Anggota ".($idx+1), 'value' => $m->nim];
            $memberRows[] = ['label' => "Nama Anggota ".($idx+1), 'value' => $m->nama];
        }
        $leftSections[] = [
            'title' => 'Anggota Tim',
            'rows' => $memberRows
        ];
    }

	$rightSections = [
		[
			'title' => 'Detail Kompetisi',
			'rows' => [
				['label' => 'Nama Kompetisi', 'value' => $prestasi->nama_kompetisi ?? '-'],
				['label' => 'Penyelenggara', 'value' => $prestasi->penyelenggara ?? '-'],
				['label' => 'Tingkat Kompetisi', 'value' => $prestasi->tingkat ?? '-'],
                ['label' => 'Status Verifikasi', 'value' => $prestasi->status_verifikasi ?? 'Menunggu'],
			],
		],
	];

    // Add mentors if any
    if ($prestasi->dosen && $prestasi->dosen->count() > 0) {
        $mentorRows = [];
        foreach($prestasi->dosen as $idx => $d) {
            $mentorRows[] = ['label' => "Nama Dosen", 'value' => $d->nama_dosen];
            $mentorRows[] = ['label' => "NIDN/NIP", 'value' => $d->nidn_nip];
        }
        $rightSections[] = [
            'title' => 'Dosen Pendamping',
            'rows' => $mentorRows
        ];
    }
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

        @if($prestasi->dokumen && $prestasi->dokumen->count() > 0)
        <div class="mt-10 border-t border-gray-100 pt-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Evidence / Dokumen Pendukung</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($prestasi->dokumen as $doc)
                @php
                    $ext = strtolower(pathinfo($doc->file, PATHINFO_EXTENSION));
                    $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                @endphp
                <a href="{{ asset('storage/' . $doc->file) }}" target="_blank"
                   class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 hover:bg-gray-50 transition group">
                    <div class="h-10 w-10 flex-shrink-0 flex items-center justify-center rounded-lg {{ $isImage ? 'bg-blue-50 text-blue-600' : 'bg-red-50 text-red-600' }}">
                        @if($isImage)
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        @else
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        @endif
                    </div>
                    <div class="overflow-hidden min-w-0">
                        <p class="text-sm font-semibold text-gray-700 truncate group-hover:text-red-600 transition">{{ $doc->jenis_dokumen }}</p>
                        <p class="text-[11px] text-gray-400 uppercase">{{ $ext }} &nbsp;·&nbsp; Klik untuk membuka</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Verification Actions --}}
        <div class="mt-12 flex items-center justify-end gap-4 border-t border-gray-100 pt-8">
            <a href="{{ route('admin.prestasi_mahasiswa') }}" class="px-6 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700">Kembali</a>
            
            @if($prestasi->status_verifikasi == 'Menunggu')
            <button type="button" onclick="openRevisionModal()" class="px-6 py-2.5 rounded-full border border-red-200 bg-red-50 text-red-600 text-sm font-bold hover:bg-red-100 transition shadow-sm">
                Minta Revisi
            </button>
            <form action="{{ route('admin.prestasi_mahasiswa.verify', $prestasi->id_prestasi) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="Valid">
                <button type="submit" class="px-8 py-2.5 rounded-full text-white text-sm font-bold hover:bg-green-700 transition shadow-lg" style="background-color: #16a34a; color: #ffffff; border: none; cursor: pointer;">
                    Setujui & Verifikasi
                </button>
            </form>
            @else
            <div class="px-6 py-2.5 rounded-full text-sm font-bold border
                {{ $prestasi->status_verifikasi == 'Valid' ? 'bg-green-50 text-green-700 border-green-200' : ($prestasi->status_verifikasi == 'Revisi' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-red-50 text-red-700 border-red-200') }}">
                Status Verifikasi: {{ $prestasi->status_verifikasi }}
            </div>
            @endif
        </div>
	</div>
</div>

{{-- Revision Modal --}}
<div id="revisionModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
        <h3 class="text-lg font-bold text-gray-800 mb-2">Catatan Revisi</h3>
        <p class="text-sm text-gray-500 mb-4">Berikan alasan mengapa data ini perlu direvisi oleh mahasiswa.</p>
        
        <form action="{{ route('admin.prestasi_mahasiswa.verify', $prestasi->id_prestasi) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="Revisi">
            <textarea name="catatan" rows="4" class="w-full rounded-xl border border-gray-300 p-4 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100" placeholder="Contoh: Sertifikat juara tidak terbaca, mohon upload ulang..."></textarea>
            
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeRevisionModal()" class="px-4 py-2 text-sm font-medium text-gray-500">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-full bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition">
                    Kirim Revisi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRevisionModal() {
        document.getElementById('revisionModal').classList.remove('hidden');
        document.getElementById('revisionModal').classList.add('flex');
    }
    function closeRevisionModal() {
        document.getElementById('revisionModal').classList.add('hidden');
        document.getElementById('revisionModal').classList.remove('flex');
    }
</script>
@endsection
