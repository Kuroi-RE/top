<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreLpjRequest;
use App\Http\Requests\VerifyLpjRequest;
use App\Http\Requests\ReviseLpjRequest;
use App\Http\Resources\LpjKegiatanResource;
use App\Models\LpjKegiatan;
use App\Models\LpjPrestasiMahasiswa;
use App\Models\ProposalKegiatan;
use App\Models\ProposalPrestasiMahasiswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * @group LPJ (Laporan Pertanggungjawaban)
 * Endpoints untuk upload dan verifikasi LPJ kegiatan
 */
class LpjController
{
    /**
     * Daftar LPJ kegiatan
     * 
     * Admin melihat semua LPJ, Ormawa hanya melihat milik mereka sendiri.
     *
     * @queryParam status_lpj string Filter berdasarkan status (Menunggu, Revisi, Disetujui)
     * @queryParam per_page integer Jumlah data per halaman (default: 15)
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Daftar LPJ kegiatan",
     *   "data": [...]
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $type = $request->input('type', $request->query('type'));

        if ($type === 'mahasiswa' || $user->isMahasiswa()) {
            $query = LpjPrestasiMahasiswa::query();
        } else {
            $query = LpjKegiatan::query();
        }

        // Filter berdasarkan role
        if ($user->isOrmawa() || $user->isMahasiswa()) {
            $query->whereHas('proposal', fn($q) => $q->where('id_user', $user->id_user));
        }

        // Filter berdasarkan status
        if ($request->has('status_lpj')) {
            $query->where('status_lpj', $request->status_lpj);
        }

        $lpjs = $query->with('proposal.user')->paginate($request->per_page ?? 15);

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar LPJ kegiatan',
            'data' => LpjKegiatanResource::collection($lpjs->items()),
            'pagination' => [
                'total' => $lpjs->total(),
                'per_page' => $lpjs->perPage(),
                'current_page' => $lpjs->currentPage(),
                'total_pages' => $lpjs->lastPage(),
            ],
        ], 200);
    }

    /**
     * Upload LPJ kegiatan
     * 
     * Mengupload LPJ untuk proposal yang sudah disetujui.
     * LPJ hanya bisa diupload jika proposal berstatus Disetujui.
     *
     * @bodyParam id_proposal integer required ID proposal yang sudah disetujui
     * @bodyParam file_lpj file required File LPJ (PDF, maksimal 5MB)
     * @bodyParam tanggal_upload date required Tanggal upload LPJ
     *
     * @response 201 {
     *   "status": "success",
     *   "message": "LPJ kegiatan berhasil diupload",
     *   "data": {...}
     * }
     * @response 422 {
     *   "status": "error",
     *   "message": "Proposal belum disetujui"
     * }
     */
    public function store(StoreLpjRequest $request): JsonResponse
    {
        $type = $request->input('type', $request->query('type'));
        $user = $request->user();

        if ($type === 'mahasiswa' || $user->isMahasiswa()) {
            $proposalModel = ProposalPrestasiMahasiswa::class;
            $lpjModel = LpjPrestasiMahasiswa::class;
        } else {
            $proposalModel = ProposalKegiatan::class;
            $lpjModel = LpjKegiatan::class;
        }

        $proposal = $proposalModel::find($request->id_proposal);

        if (!$proposal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Proposal tidak ditemukan',
            ], 404);
        }

        if (!in_array($proposal->status, ['Approved', 'Revisi LPJ'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'LPJ hanya bisa diupload untuk proposal yang sudah disetujui atau revisi LPJ',
                'errors' => ['proposal' => 'Status proposal harus Approved atau Revisi LPJ'],
            ], 422);
        }

        $filePath = $request->file('file_lpj')->store('lpj', 'public');

        $lpj = $lpjModel::updateOrCreate(
            ['id_proposal' => $request->id_proposal],
            [
                'file_lpj' => $filePath,
                'status_lpj' => 'Menunggu',
                'catatan_admin' => null,
                'tanggal_upload' => $request->tanggal_upload ?? now()->toDateString(),
            ]
        );

        $proposal->update(['status' => 'Cek LPJ']);

        return response()->json([
            'status' => 'success',
            'message' => 'LPJ kegiatan berhasil diupload',
            'data' => new LpjKegiatanResource($lpj->load('proposal.user')),
        ], 201);
    }

    /**
     * Helper to resolve LPJ by ID and type
     */
    private function resolveLpj(Request $request, $id)
    {
        $type = $request->input('type', $request->query('type'));

        if ($type === 'mahasiswa') {
            return LpjPrestasiMahasiswa::findOrFail($id);
        }

        if ($type === 'ormawa') {
            return LpjKegiatan::findOrFail($id);
        }

        $user = $request->user();
        if ($user && $user->isMahasiswa()) {
            return LpjPrestasiMahasiswa::findOrFail($id);
        }

        $lpj = LpjKegiatan::find($id);
        if ($lpj) {
            return $lpj;
        }

        return LpjPrestasiMahasiswa::findOrFail($id);
    }

    /**
     * Detail LPJ
     * 
     * Melihat detail LPJ kegiatan.
     *
     * @urlParam id integer required ID LPJ
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Detail LPJ kegiatan",
     *   "data": {...}
     * }
     */
    public function show(Request $request, $id): JsonResponse
    {
        $lpj = $this->resolveLpj($request, $id);

        if (($request->user()->isOrmawa() || $request->user()->isMahasiswa()) && $request->user()->id_user !== $lpj->proposal->id_user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses ke LPJ ini',
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail LPJ kegiatan',
            'data' => new LpjKegiatanResource($lpj->load('proposal.user')),
        ], 200);
    }

    /**
     * Upload revisi LPJ
     * 
     * Mengupload versi revisi LPJ yang diminta untuk direvisi.
     *
     * @urlParam id integer required ID LPJ
     * @bodyParam file_lpj file required File LPJ revisi (PDF, maksimal 5MB)
     * @bodyParam tanggal_upload date required Tanggal upload revisi
     *
     * @response 201 {
     *   "status": "success",
     *   "message": "Revisi LPJ berhasil diupload",
     *   "data": {...}
     * }
     */
    public function submitRevision(ReviseLpjRequest $request, $id): JsonResponse
    {
        $lpj = $this->resolveLpj($request, $id);

        Storage::disk('public')->delete($lpj->file_lpj);
        $filePath = $request->file('file_lpj')->store('lpj', 'public');

        $lpj->update([
            'file_lpj' => $filePath,
            'status_lpj' => 'Menunggu',  // nilai enum yang valid di database
            'tanggal_upload' => $request->tanggal_upload,
        ]);

        // When a revision is submitted, the proposal status should go back to "Cek LPJ" so admin can review it again.
        $proposal = $lpj->proposal;
        if ($proposal) {
            $proposal->update(['status' => 'Cek LPJ']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Revisi LPJ berhasil diupload',
            'data' => new LpjKegiatanResource($lpj->load('proposal.user')),
        ], 201);
    }

    /**
     * Verifikasi LPJ (Admin only)
     * 
     * Admin melakukan verifikasi LPJ dan memberikan keputusan.
     * Dapat menyetujui atau meminta revisi.
     *
     * @urlParam id integer required ID LPJ
     * @bodyParam status_lpj string required Status (Disetujui, Revisi)
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Verifikasi LPJ berhasil",
     *   "data": {...}
     * }
     */
    public function verify(VerifyLpjRequest $request, $id): JsonResponse
    {
        $lpj = $this->resolveLpj($request, $id);

        $dbStatusMap = [
            'Approved' => 'Disetujui',
            'Revision' => 'Revisi',
            'Selesai' => 'Disetujui',
            'Revisi' => 'Revisi',
            'Disetujui' => 'Disetujui',
        ];
        $dbStatus = $dbStatusMap[$request->status_lpj] ?? $request->status_lpj;

        $lpj->update([
            'status_lpj' => $dbStatus,
            'catatan_admin' => $request->catatan_admin,
        ]);

        // Auto-update status proposal induk
        $proposal = $lpj->proposal;
        if ($proposal) {
            if ($request->status_lpj === 'Approved') {
                $proposal->update(['status' => 'Selesai']);
            } elseif ($request->status_lpj === 'Revision') {
                $proposal->update(['status' => 'Revisi LPJ']);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Verifikasi LPJ berhasil',
            'data' => new LpjKegiatanResource($lpj->load('proposal.user')),
        ], 200);
    }
}
