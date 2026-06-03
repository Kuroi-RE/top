<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class CetakPrestasiController
{
    /**
     * Cetak Transkrip Prestasi Mahasiswa (PDF)
     */
    public function cetakTranskrip(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $prestasis = Prestasi::where('id_user', $user->id_user)
            ->orderBy('created_at', 'desc')
            ->get();

        $nim = trim((string) ($user->nim ?? ''));
        $cardUrl = $nim !== ''
            ? $request->getSchemeAndHttpHost() . route('prestasi.kartu_prestasi', ['nim' => $nim], false)
            : null;
        $qrCodeUrl = $cardUrl !== null
            ? 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&margin=8&data=' . urlencode($cardUrl)
            : null;

        $pdf = Pdf::loadView('pdf.transkrip', compact('user', 'prestasis', 'nim', 'cardUrl', 'qrCodeUrl'));
        
        return $pdf->stream('transkrip_prestasi_' . $nim . '.pdf');
    }

    /**
     * Cetak Kartu Prestasi Mahasiswa (PDF)
     */
    public function cetakKartu(Request $request, string $nim)
    {
        $user = User::where('nim', $nim)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data mahasiswa tidak ditemukan.'
            ], 404);
        }

        $prestasis = Prestasi::with('dokumen')
            ->where('id_user', $user->id_user)
            ->orderBy('created_at', 'desc')
            ->get();

        $namaLengkap = trim($user->nama_depan . ' ' . ($user->nama_belakang ?? ''));
        $totalPrestasi = $prestasis->count();
        $totalDokumen = $prestasis->sum(fn ($prestasi) => $prestasi->dokumen->count());
        $tanggalLulus = now()->format('d-m-Y');
        $jenjangPendidikan = 'S1';

        $pdf = Pdf::loadView('pdf.kartu', compact(
            'user',
            'namaLengkap',
            'prestasis',
            'totalPrestasi',
            'totalDokumen',
            'tanggalLulus',
            'jenjangPendidikan'
        ));

        return $pdf->stream('kartu_prestasi_' . $nim . '.pdf');
    }

    /**
     * Export PDF Daftar Prestasi (Mahasiswa atau Ormawa)
     */
    public function exportPrestasiPdf(Request $request)
    {
        if (!($request->user()->isDpmbem() || $request->user()->isAdmin())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden - Hanya DPMBEM dan Admin yang dapat mengakses',
            ], 403);
        }

        $isOrmawa = $request->get('mewakili_ormawa') === 'ya';
        $query = Prestasi::with('user')->where('mewakili_ormawa', $isOrmawa ? 'ya' : 'tidak');

        if ($request->has('tingkat') && $request->tingkat) {
            $query->where('tingkat', $request->tingkat);
        }

        if ($request->has('search') && $request->search) {
            $q = $request->search;
            $query->where(function($sub) use ($q) {
                $sub->where('nama_kompetisi', 'like', '%' . $q . '%')
                    ->orWhere('penyelenggara', 'like', '%' . $q . '%')
                    ->orWhere('capaian', 'like', '%' . $q . '%')
                    ->orWhereHas('user', function($u) use ($q) {
                        $u->where('nama_depan', 'like', '%' . $q . '%')
                          ->orWhere('nama_belakang', 'like', '%' . $q . '%')
                          ->orWhere('username', 'like', '%' . $q . '%')
                          ->orWhere('nim', 'like', '%' . $q . '%');
                    });
            });
        }

        if ($isOrmawa) {
            $activities = $query->latest()->get()->map(function ($item) {
                return [
                    'tw' => $item->klaster ? substr($item->klaster, 0, 1) : '1',
                    'ormawa' => trim(($item->user->nama_depan ?? '') . ' ' . ($item->user->nama_belakang ?? '')),
                    'nama_kegiatan' => $item->nama_kompetisi,
                    'resiko' => $item->tingkat,
                    'waktu' => $item->waktu_kompetisi ? \Carbon\Carbon::parse($item->waktu_kompetisi)->format('d F Y') : '-',
                    'ajuan' => $item->capaian,
                    'anggaran' => $item->penyelenggara,
                    'status' => $item->status_verifikasi == 'Valid' ? 'Selesai' : ($item->status_verifikasi == 'Menunggu' || $item->status_verifikasi == 'Pending' ? 'Ajuan baru' : ($item->status_verifikasi == 'Revisi' ? 'Revisi' : 'Acc')),
                ];
            })->toArray();

            $statusStyles = [
                'Selesai' => 'done',
                'Pencairan' => 'pending',
                'Acc' => 'info',
                'Revisi' => 'revisi',
                'Ajuan baru' => 'new',
            ];

            $pdf = Pdf::loadView("admin.prestasi_ormawa_export_pdf", compact("activities", "statusStyles"));
            return $pdf->download("prestasi_ormawa_" . now()->format('YmdHis') . ".pdf");
        } else {
            $prestasi = $query->latest()->get()->map(function ($item) {
                return [
                    'nim' => $item->user->nim ?? $item->user->username ?? '-',
                    'nama' => trim(($item->user->nama_depan ?? '') . ' ' . ($item->user->nama_belakang ?? '')),
                    'prodi' => $item->user->prodi ?? '-',
                    'prestasi' => $item->capaian,
                    'nama_event' => $item->nama_kompetisi,
                    'penyelenggara' => $item->penyelenggara,
                    'tingkat' => $item->tingkat,
                ];
            });

            $pdf = Pdf::loadView("admin.prestasi_mahasiswa_export_pdf", compact("prestasi"));
            return $pdf->download("prestasi_mahasiswa_" . now()->format('YmdHis') . ".pdf");
        }
    }
}
