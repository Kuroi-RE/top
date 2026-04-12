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
        $data = [
            'id_prestasi' => $prestasi->id_prestasi,
            'nama_dosen' => $request->nama_dosen,
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
