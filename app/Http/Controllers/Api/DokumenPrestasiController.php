<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreDokumenRequest;
use App\Http\Resources\DokumenPrestasiResource;
use App\Models\Prestasi;
use App\Models\DokumenPrestasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenPrestasiController
{
    /**
     * Tambah dokumen pendukung prestasi
     */
    public function store(StoreDokumenRequest $request, Prestasi $prestasi): JsonResponse
    {
        $filePath = $request->file('file')->store('prestasi', 'public');

        $dokumen = DokumenPrestasi::create([
            'id_prestasi' => $prestasi->id_prestasi,
            'jenis_dokumen' => $request->jenis_dokumen,
            'file' => $filePath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Dokumen berhasil ditambahkan',
            'data' => new DokumenPrestasiResource($dokumen),
        ], 201);
    }

    /**
     * Hapus dokumen pendukung prestasi
     */
    public function destroy(Request $request, Prestasi $prestasi, DokumenPrestasi $dokumen): JsonResponse
    {
        // Validasi kepemilikan prestasi
        if ($request->user()->id_user !== $prestasi->id_user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses ke prestasi ini',
            ], 403);
        }

        // Pastikan dokumen milik prestasi ini
        if ($dokumen->id_prestasi !== $prestasi->id_prestasi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dokumen tidak ditemukan dalam prestasi ini',
            ], 404);
        }

        // Hapus file dari storage
        if ($dokumen->file && Storage::disk('public')->exists($dokumen->file)) {
            Storage::disk('public')->delete($dokumen->file);
        }

        $dokumen->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Dokumen berhasil dihapus',
        ], 200);
    }
}
