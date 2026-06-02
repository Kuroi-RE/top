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
}
