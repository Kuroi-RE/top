<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreInformasiRequest;
use App\Http\Resources\InformasiKegiatanResource;
use App\Http\Resources\InformasiKegiatanCollection;
use App\Models\InformasiKegiatan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * @group Informasi Kegiatan
 * Endpoints untuk pengelolaan informasi dan pengumuman kemahasiswaan
 */
class InformasiController
{
    /**
     * Daftar informasi kegiatan
     * 
     * Menampilkan daftar informasi kegiatan dan pengumuman.
     * Semua pengguna dapat mengakses endpoint ini.
     *
     * @queryParam role string Filter berdasarkan role (Ormawa, Kemahasiswaan)
     * @queryParam per_page integer Jumlah data per halaman (default: 15)
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Daftar informasi kegiatan",
     *   "data": [...]
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $query = InformasiKegiatan::query();

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        $informasi = $query->with('user')
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar informasi kegiatan',
            'data' => InformasiKegiatanCollection::make($informasi->items()),
            'pagination' => [
                'total' => $informasi->total(),
                'per_page' => $informasi->perPage(),
                'current_page' => $informasi->currentPage(),
                'total_pages' => $informasi->lastPage(),
            ],
        ], 200);
    }

    /**
     * Upload informasi kegiatan
     * 
     * Membuat atau mengunggah informasi kegiatan dan pengumuman.
     * Ormawa dan Admin dapat menggunakan endpoint ini.
     *
     * @bodyParam judul string required Judul informasi
     * @bodyParam role string required Untuk siapa info ini (Ormawa, Kemahasiswaan)
     * @bodyParam caption string required Deskripsi/isi informasi
     * @bodyParam file file optional File pendukung (PDF, DOC, JPG, PNG - maksimal 5MB)
     *
     * @response 201 {
     *   "status": "success",
     *   "message": "Informasi kegiatan berhasil dibuat",
     *   "data": {...}
     * }
     */
    public function store(StoreInformasiRequest $request): JsonResponse
    {
        $data = [
            'id_user' => $request->user()->id_user,
            'judul' => $request->judul,
            'role' => $request->role,
            'caption' => $request->caption,
        ];

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('informasi', 'public');
        }

        $informasi = InformasiKegiatan::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Informasi kegiatan berhasil dibuat',
            'data' => new InformasiKegiatanResource($informasi->load('user')),
        ], 201);
    }

    /**
     * Detail informasi kegiatan
     * 
     * Melihat detail lengkap informasi kegiatan.
     *
     * @urlParam id integer required ID Informasi
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Detail informasi kegiatan",
     *   "data": {...}
     * }
     */
    public function show(InformasiKegiatan $informasi): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Detail informasi kegiatan',
            'data' => new InformasiKegiatanResource($informasi->load('user')),
        ], 200);
    }

    /**
     * Update informasi kegiatan
     * 
     * Mengedit informasi kegiatan. Hanya pembuat yang dapat mengedit.
     *
     * @urlParam id integer required ID Informasi
     * @bodyParam judul string optional Judul informasi baru
     * @bodyParam caption string optional Isi informasi baru
     * @bodyParam file file optional File baru
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Informasi kegiatan berhasil diperbarui",
     *   "data": {...}
     * }
     */
    public function update(Request $request, InformasiKegiatan $informasi): JsonResponse
    {
        if ($request->user()->id_user !== $informasi->id_user && !$request->user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses untuk mengedit informasi ini',
            ], 403);
        }

        $data = $request->only(['judul', 'caption']);

        if ($request->hasFile('file')) {
            if ($informasi->file) {
                Storage::disk('public')->delete($informasi->file);
            }
            $data['file'] = $request->file('file')->store('informasi', 'public');
        }

        $informasi->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Informasi kegiatan berhasil diperbarui',
            'data' => new InformasiKegiatanResource($informasi->load('user')),
        ], 200);
    }

    /**
     * Hapus informasi kegiatan
     * 
     * Menghapus informasi kegiatan. Hanya pembuat atau Admin yang dapat menghapus.
     *
     * @urlParam id integer required ID Informasi
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Informasi kegiatan berhasil dihapus"
     * }
     */
    public function destroy(Request $request, InformasiKegiatan $informasi): JsonResponse
    {
        if ($request->user()->id_user !== $informasi->id_user && !$request->user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses untuk menghapus informasi ini',
            ], 403);
        }

        if ($informasi->file) {
            Storage::disk('public')->delete($informasi->file);
        }

        $informasi->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Informasi kegiatan berhasil dihapus',
        ], 200);
    }
}
