<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transkrip Prestasi Mahasiswa</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .logo-td {
            width: 300px;
            vertical-align: middle;
        }
        .logo-td img {
            height: 40px;
            width: auto;
            margin-right: 10px;
            display: inline-block;
        }
        .title-td {
            text-align: right;
            vertical-align: middle;
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        .document-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            color: #c1121f;
            text-transform: uppercase;
            margin-top: 10px;
            margin-bottom: 2px;
        }
        .document-subtitle {
            text-align: center;
            font-size: 10px;
            font-style: italic;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .student-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .student-info-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        .info-separator {
            width: 10px;
        }
        .transkrip-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .transkrip-table th {
            background-color: #c1121f;
            color: white;
            padding: 8px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            border: 1px solid #c1121f;
            text-align: left;
        }
        .transkrip-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .transkrip-table tr:nth-child(even) td {
            background-color: #f9f9f9;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-internasional {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .badge-nasional {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-regional {
            background-color: #dcfce7;
            color: #15803d;
        }
        .footer-note {
            text-align: center;
            font-size: 9px;
            color: #666;
            margin-bottom: 30px;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .signature-td {
            width: 33%;
            text-align: center;
            vertical-align: top;
        }
        .signature-space {
            height: 70px;
        }
        .signature-name {
            font-weight: bold;
            border-top: 1px solid #333;
            display: inline-block;
            padding-top: 5px;
            min-width: 150px;
        }
        .signature-title {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="logo-td">
                @if(file_exists(public_path('logo_TUP.png')))
                    <img src="{{ public_path('logo_TUP.png') }}" alt="Logo TUP">
                @endif
                @if(file_exists(public_path('logo_direktorat.png')))
                    <img src="{{ public_path('logo_direktorat.png') }}" alt="Logo Direktorat">
                @endif
            </td>
            <td class="title-td">
                Universitas Telkom
            </td>
        </tr>
    </table>

    <div class="document-title">Transkrip Prestasi Mahasiswa</div>
    <div class="document-subtitle">Student Prestations Transcript</div>

    <table class="student-info-table">
        <tr>
            <td class="info-label">Nama/Name</td>
            <td class="info-separator">:</td>
            <td>{{ $user->nama_depan }} {{ $user->nama_belakang ?? '' }}</td>
            
            <td class="info-label" style="padding-left: 20px;">Nomor Induk Mahasiswa/Student's ID</td>
            <td class="info-separator">:</td>
            <td>{{ $user->nim ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Tempat & Tanggal Lahir/Place & Date of Birth</td>
            <td class="info-separator">:</td>
            <td>-</td>
            
            <td class="info-label" style="padding-left: 20px;">Program Studi/Program</td>
            <td class="info-separator">:</td>
            <td>{{ $user->prodi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Tanggal Cetak/Date of Print</td>
            <td class="info-separator">:</td>
            <td>{{ now()->format('d-m-Y') }}</td>
            
            <td class="info-label" style="padding-left: 20px;">Jenjang Pendidikan/Level of Education</td>
            <td class="info-separator">:</td>
            <td>S1 (Strata 1)</td>
        </tr>
    </table>

    <table class="transkrip-table">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 12%;">Tahun</th>
                <th style="width: 40%;">Nama Kegiatan</th>
                <th style="width: 20%;">Tingkat</th>
                <th style="width: 20%;">Prestasi dicapai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prestasis as $index => $prestasi)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $prestasi->created_at->format('Y') }}</td>
                    <td>{{ $prestasi->nama_kompetisi }}</td>
                    <td>
                        <span class="badge badge-{{ strtolower($prestasi->tingkat) }}">
                            {{ $prestasi->tingkat }}
                        </span>
                    </td>
                    <td>{{ $prestasi->capaian ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #999; padding: 20px;">
                        Tidak ada data prestasi
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-note">
        Pencapaian prestasi saat ini: {{ now()->locale('id')->format('j F Y') }}
    </div>

    <table class="signature-table">
        <tr>
            <td class="signature-td">
                <div class="signature-title"><strong>Kepala Urusan</strong></div>
                <div class="signature-title">Kemahasiswaan, Karier dan Alumni</div>
                <div class="signature-space"></div>
                <div class="signature-name">Kadarisman, S.Si</div>
            </td>
            <td class="signature-td">
                @if(!empty($qrCodeUrl))
                    <img src="{{ $qrCodeUrl }}" alt="QR Code" style="width: 80px; height: 80px; margin-top: 10px;">
                @else
                    <div style="border: 1px dashed #ccc; width: 80px; height: 80px; margin: 10px auto; line-height: 80px; font-size: 8px; color: #999; text-align: center;">QR CODE</div>
                @endif
            </td>
            <td class="signature-td">
                <div class="signature-title"><strong>Mahasiswa</strong></div>
                <div class="signature-title">Student</div>
                <div class="signature-space"></div>
                <div class="signature-name">{{ $user->nama_depan }} {{ $user->nama_belakang ?? '' }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
