<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StorePrestasiRequest;
use App\Http\Requests\VerifyPrestasiRequest;
use App\Http\Requests\StoreAnggotaRequest;
use App\Http\Requests\StoreDosenRequest;
use App\Http\Resources\PrestasiResource;
use App\Http\Resources\PrestasiCollection;
use App\Models\Prestasi;
use App\Models\DokumenPrestasi;
use App\Models\AnggotaPrestasi;
use App\Models\DosenPendamping;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePrestasiRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


/**
 * @group Prestasi Mahasiswa
 * Endpoints untuk input, verifikasi, dan monitoring prestasi mahasiswa
 */
class PrestasiController
{
    /**
     * Daftar prestasi
     * 
     * Admin melihat semua prestasi, Mahasiswa hanya melihat milik mereka sendiri.
     *
     * @queryParam status_verifikasi string Filter berdasarkan status verifikasi
     * @queryParam per_page integer Jumlah data per halaman (default: 15)
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Daftar prestasi",
     *   "data": [...]
     * }
     */


    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Prestasi::query();

        if ($user->isMahasiswa()) {
            $query->where('id_user', $user->id_user);
        }

        if ($request->has('status_verifikasi')) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        $prestasi = $query->with('user', 'dokumen', 'anggota', 'dosen')->paginate($request->per_page ?? 15);

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar prestasi',
            'data' => PrestasiCollection::make($prestasi->items()),
            'pagination' => [
                'total' => $prestasi->total(),
                'per_page' => $prestasi->perPage(),
                'current_page' => $prestasi->currentPage(),
                'total_pages' => $prestasi->lastPage(),
            ],
        ], 200);
    }

    /**
     * Input laporan prestasi baru
     * 
     * Mahasiswa melaporkan prestasi mereka dengan upload dokumen pendukung.
     *
     * @bodyParam nama_kompetisi string required Nama kompetisi
     * @bodyParam penyelenggara string required Organisasi penyelenggara
     * @bodyParam tingkat string required Level kompetisi (Regional, Nasional, Internasional)
     * @bodyParam capaian string required Pencapaian/juara (misal: Juara 1, Top 10)
     * @bodyParam kategori string required Tipe prestasi (Individu, Kelompok)
     * @bodyParam dokumen array required Array dokumen pendukung (minimal 1)
     * @bodyParam dokumen.*.jenis_dokumen string required Jenis dokumen (sertifikat, piala, dll)
     * @bodyParam dokumen.*.file file required File dokumen (PDF/JPG/PNG, maksimal 5MB)
     *
     * @response 201 {
     *   "status": "success",
     *   "message": "Prestasi berhasil dibuat",
     *   "data": {...}
     * }
     */
    public function store(StorePrestasiRequest $request): JsonResponse
    {
        $prestasi = Prestasi::create([
            'id_user' => $request->user()->id_user,
            'nama_kompetisi' => $request->nama_kompetisi,
            'penyelenggara' => $request->penyelenggara,
            'tingkat' => $request->tingkat,
            'capaian' => $request->capaian,
            'kategori' => $request->kategori,
            'status_verifikasi' => 'Menunggu',
        ]);

        // Upload dokumen
        foreach ($request->dokumen as $doc) {
            $filePath = $doc['file']->store('prestasi', 'public');
            DokumenPrestasi::create([
                'id_prestasi' => $prestasi->id_prestasi,
                'jenis_dokumen' => $doc['jenis_dokumen'],
                'file' => $filePath,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Prestasi berhasil dibuat',
            'data' => new PrestasiResource($prestasi->load('user', 'dokumen')),
        ], 201);
    }

    /**
     * Detail prestasi
     * 
     * Melihat detail lengkap prestasi termasuk dokumen dan anggota tim.
     *
     * @urlParam id integer required ID Prestasi
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Detail prestasi",
     *   "data": {...}
     * }
     */
    public function show(Request $request, Prestasi $prestasi): JsonResponse
    {
        if ($request->user()->isMahasiswa() && $request->user()->id_user !== $prestasi->id_user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses ke prestasi ini',
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail prestasi',
            'data' => new PrestasiResource($prestasi->load('user', 'dokumen', 'anggota', 'dosen')),
        ], 200);
    }

    /**
     * Cek status verifikasi prestasi
     * 
     * Melihat status verifikasi prestasi dari admin.
     *
     * @urlParam id integer required ID Prestasi
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Status prestasi",
     *   "data": {
     *     "id_prestasi": 1,
     *     "status_verifikasi": "Menunggu"
     *   }
     * }
     */
    public function checkStatus(Request $request, Prestasi $prestasi): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Status prestasi',
            'data' => [
                'id_prestasi' => $prestasi->id_prestasi,
                'status_verifikasi' => $prestasi->status_verifikasi,
            ],
        ], 200);
    }

    /**
     * Verifikasi prestasi (Admin only)
     * 
     * Admin memverifikasi prestasi mahasiswa dan memberikan keputusan.
     *
     * @urlParam id integer required ID Prestasi
     * @bodyParam status_verifikasi string required Status (Valid, Tidak Valid, Revisi)
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Prestasi berhasil diverifikasi",
     *   "data": {...}
     * }
     */
    public function verify(VerifyPrestasiRequest $request, Prestasi $prestasi): JsonResponse
    {
        $prestasi->update(['status_verifikasi' => $request->status_verifikasi]);

        return response()->json([
            'status' => 'success',
            'message' => 'Prestasi berhasil diverifikasi',
            'data' => new PrestasiResource($prestasi->load('user', 'dokumen', 'anggota', 'dosen')),
        ], 200);
    }

    /**
     * Tambah anggota tim prestasi
     * 
     * Menambahkan anggota tim untuk prestasi kelompok.
     *
     * @urlParam id integer required ID Prestasi
     * @bodyParam nama string required Nama anggota
     * @bodyParam nim string required NIM anggota (max 12 karakter)
     * @bodyParam prodi string required Program studi anggota
     *
     * @response 201 {
     *   "status": "success",
     *   "message": "Anggota tim berhasil ditambahkan",
     *   "data": {...}
     * }
     */
    public function addAnggota(StoreAnggotaRequest $request, Prestasi $prestasi): JsonResponse
    {
        if ($prestasi->kategori !== 'Kelompok') {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya prestasi kelompok yang dapat memiliki anggota',
            ], 422);
        }

        $anggota = AnggotaPrestasi::create([
            'id_prestasi' => $prestasi->id_prestasi,
            'nama' => $request->nama,
            'nim' => $request->nim,
            'prodi' => $request->prodi,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Anggota tim berhasil ditambahkan',
            'data' => new \App\Http\Resources\AnggotaPrestasiResource($anggota),
        ], 201);
    }

        /**
     * Update data prestasi
     *
     * Mahasiswa hanya bisa update jika status masih Menunggu atau Revisi.
     */
    public function update(UpdatePrestasiRequest $request, Prestasi $prestasi): JsonResponse
    {
        // Authorization: hanya pemilik yang bisa update
        if ($request->user()->id_user !== $prestasi->id_user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses untuk mengubah prestasi ini',
            ], 403);
        }

        $prestasi->update($request->only([
            'nama_kompetisi',
            'penyelenggara',
            'tingkat',
            'capaian',
            'kategori',
        ]));

        // Refresh data dari database untuk menampilkan nilai terbaru
        $prestasi->refresh();

        return response()->json([
            'status'  => 'success',
            'message' => 'Prestasi berhasil diperbarui',
            'data'    => new PrestasiResource($prestasi->load('user', 'dokumen', 'anggota', 'dosen')),
        ], 200);
    }

    /**
     * Hapus prestasi
     *
     * Mahasiswa hanya bisa hapus jika status masih Menunggu.
     * File dokumen ikut terhapus dari storage.
     */
    public function destroy(Request $request, Prestasi $prestasi): JsonResponse
    {
        // Hanya pemilik yang bisa hapus
        if ($request->user()->id_user !== $prestasi->id_user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses untuk menghapus prestasi ini',
            ], 403);
        }

        // Tidak bisa hapus jika sudah Valid
        if ($prestasi->status_verifikasi === 'Valid') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Prestasi yang sudah diverifikasi tidak dapat dihapus',
            ], 422);
        }

        // Hapus semua file dokumen dari storage
        foreach ($prestasi->dokumen as $dokumen) {
            if (Storage::disk('public')->exists($dokumen->file)) {
                Storage::disk('public')->delete($dokumen->file);
            }
        }

        // Hapus file surat tugas dosen dari storage
        foreach ($prestasi->dosen as $dosen) {
            if ($dosen->surat_tugas && Storage::disk('public')->exists($dosen->surat_tugas)) {
                Storage::disk('public')->delete($dosen->surat_tugas);
            }
        }

        $prestasi->delete(); // Cascade delete anggota, dokumen, dosen via DB

        return response()->json([
            'status'  => 'success',
            'message' => 'Prestasi berhasil dihapus',
        ], 200);
    }

    /**
     * Hapus anggota tim prestasi
     */
    public function deleteAnggota(Request $request, Prestasi $prestasi, AnggotaPrestasi $anggota): JsonResponse
    {
        // Validasi kepemilikan
        if ($request->user()->id_user !== $prestasi->id_user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses ke prestasi ini',
            ], 403);
        }

        // Pastikan anggota milik prestasi ini
        if ($anggota->id_prestasi !== $prestasi->id_prestasi) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anggota tidak ditemukan dalam prestasi ini',
            ], 404);
        }

        $anggota->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Anggota tim berhasil dihapus',
        ], 200);
    }

    /**
     * Hapus dosen pendamping
     */
    public function deleteDosen(Request $request, Prestasi $prestasi, DosenPendamping $dosen): JsonResponse
    {
        // Validasi kepemilikan
        if ($request->user()->id_user !== $prestasi->id_user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses ke prestasi ini',
            ], 403);
        }

        // Pastikan dosen milik prestasi ini
        if ($dosen->id_prestasi !== $prestasi->id_prestasi) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dosen tidak ditemukan dalam prestasi ini',
            ], 404);
        }

        // Hapus file surat tugas jika ada
        if ($dosen->surat_tugas && Storage::disk('public')->exists($dosen->surat_tugas)) {
            Storage::disk('public')->delete($dosen->surat_tugas);
        }

        $dosen->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Dosen pendamping berhasil dihapus',
        ], 200);
    }



    /**
     * Tambah dosen pendamping
     * 
     * Menambahkan data dosen pendamping untuk prestasi.
     *
     * @urlParam id integer required ID Prestasi
     * @bodyParam nama_dosen string required Nama dosen
     * @bodyParam nidn string optional NIDN dosen (max 10 karakter)
     * @bodyParam nip string optional NIP dosen (max 18 karakter)
     * @bodyParam prodi string required Program studi dosen
     * @bodyParam surat_tugas file optional File surat tugas (PDF, maksimal 5MB)
     *
     * @response 201 {
     *   "status": "success",
     *   "message": "Dosen pendamping berhasil ditambahkan",
     *   "data": {...}
     * }
     */
    public function addDosen(StoreDosenRequest $request, Prestasi $prestasi): JsonResponse
    {
        // Validasi kepemilikan
        if ($request->user()->id_user !== $prestasi->id_user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses ke prestasi ini',
            ], 403);
        }

        $data = [
            'id_prestasi' => $prestasi->id_prestasi,
            'nama_dosen' => $request->nama,  // Map 'nama' dari request ke 'nama_dosen' di database
            'nidn' => $request->nidn,
            'nip' => $request->nip,
            'prodi' => $request->prodi,
        ];

        if ($request->hasFile('surat_tugas')) {
            $data['surat_tugas'] = $request->file('surat_tugas')->store('dokumen-dosen', 'public');
        }

        $dosen = DosenPendamping::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Dosen pendamping berhasil ditambahkan',
            'data' => new \App\Http\Resources\DosenPendampingResource($dosen),
        ], 201);
    }
}
