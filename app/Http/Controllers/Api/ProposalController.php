<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreProposalRequest;
use App\Http\Requests\UpdateProposalRequest;
use App\Http\Requests\VerifyProposalRequest;
use App\Http\Requests\ReviseProposalRequest;
use App\Http\Resources\ProposalKegiatanResource;
use App\Http\Resources\ProposalKegiatanCollection;
use App\Models\ProposalKegiatan;
use App\Models\RevisiProposal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * @group Proposal Kegiatan
 * Endpoints untuk pengajuan, verifikasi, dan revisi proposal kegiatan Ormawa
 */
class ProposalController
{
    /**
     * Daftar proposal kegiatan
     * 
     * Menampilkan daftar proposal. Admin melihat semua proposal,
     * Ormawa hanya melihat proposal milik mereka sendiri.
     * DPMBEM dapat melihat semua untuk monitoring.
     *
     * @queryParam status string Filter berdasarkan status (Menunggu, Revisi, Disetujui, Ditolak)
     * @queryParam triwulan string Filter berdasarkan triwulan (I, II, III, IV)
     * @queryParam per_page integer Jumlah data per halaman (default: 15)
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Daftar proposal kegiatan",
     *   "data": [...]
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = ProposalKegiatan::query();

        // Filter berdasarkan role
        if ($user->isOrmawa()) {
            $query->where('id_user', $user->id_user);
        }

        // Filter berdasarkan status
        if ($request->has('status')) {
            $request->validate([
                'status' => 'in:Pending,Revision,Approved,Rejected',
            ]);
            $query->where('status', $request->status);
        }

        // Filter berdasarkan triwulan
        if ($request->has('triwulan')) {
            $query->where('ajuan_triwulan', $request->triwulan);
        }

        $proposals = $query->with('user')->paginate($request->per_page ?? 15);

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar proposal kegiatan',
            'data' => ProposalKegiatanCollection::make($proposals->items()),
            'pagination' => [
                'total' => $proposals->total(),
                'per_page' => $proposals->perPage(),
                'current_page' => $proposals->currentPage(),
                'total_pages' => $proposals->lastPage(),
            ],
        ], 200);
    }

    /**
     * Ajukan proposal kegiatan baru
     * 
     * Membuat proposal kegiatan baru dengan upload file PDF.
     * Hanya Ormawa yang dapat mengajukan proposal.
     *
     * @bodyParam ajuan_triwulan string required Pilihan triwulan (I, II, III, IV)
     * @bodyParam risiko_proposal string required Tingkat risiko (Rendah, Sedang, Tinggi)
     * @bodyParam no_telepon string required Nomor telepon, max 15 karakter
     * @bodyParam nama_kegiatan string required Nama kegiatan, max 150 karakter
     * @bodyParam waktu_kegiatan date required Tanggal kegiatan
     * @bodyParam tempat_kegiatan string required Tempat kegiatan, max 150 karakter
     * @bodyParam besar_ajuan decimal required Besar ajuan dana (minimum Rp 100.000)
     * @bodyParam nomor_rekening string required Nomor rekening, max 30 karakter
     * @bodyParam nama_rekening string required Nama pemegang rekening, max 100 karakter
     * @bodyParam nama_bank string required Nama bank, max 100 karakter
     * @bodyParam honor_pelatih string required Ada honor pelatih? (Ya/Tidak)
     * @bodyParam file file required File proposal (PDF, maksimal 5MB)
     *
     * @response 201 {
     *   "status": "success",
     *   "message": "Proposal kegiatan berhasil dibuat",
     *   "data": {...}
     * }
     * @response 422 {
     *   "status": "error",
     *   "message": "Validation failed",
     *   "errors": {...}
     * }
     */
    public function store(StoreProposalRequest $request): JsonResponse
    {
        $filePath = $request->file('file')->store('proposals', 'public');

        $proposal = ProposalKegiatan::create([
            'id_user' => $request->user()->id_user,
            'ajuan_triwulan' => $request->ajuan_triwulan,
            'risiko_proposal' => $request->risiko_proposal,
            'no_telepon' => $request->no_telepon,
            'nama_kegiatan' => $request->nama_kegiatan,
            'waktu_kegiatan' => $request->waktu_kegiatan,
            'tempat_kegiatan' => $request->tempat_kegiatan,
            'besar_ajuan' => $request->besar_ajuan,
            'nomor_rekening' => $request->nomor_rekening,
            'nama_rekening' => $request->nama_rekening,
            'nama_bank' => $request->nama_bank,
            'honor_pelatih' => $request->honor_pelatih,
            'file' => $filePath,
            'status' => 'Menunggu',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Proposal kegiatan berhasil dibuat',
            'data' => new ProposalKegiatanResource($proposal->load('user')),
        ], 201);
    }

    /**
     * Lihat detail proposal
     * 
     * Menampilkan detail proposal berdasarkan ID.
     * Ormawa hanya dapat melihat proposal mereka sendiri.
     *
     * @urlParam id integer required ID Proposal
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Detail proposal kegiatan",
     *   "data": {...}
     * }
     * @response 404 {
     *   "status": "error",
     *   "message": "Proposal tidak ditemukan"
     * }
     */
    public function show(Request $request, ProposalKegiatan $proposal): JsonResponse
    {
        if ($request->user()->isOrmawa() && $request->user()->id_user !== $proposal->id_user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses ke proposal ini',
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail proposal kegiatan',
            'data' => new ProposalKegiatanResource($proposal->load('user')),
        ], 200);
    }

    /**
     * Update proposal kegiatan
     * 
     * Mengubah data proposal. Hanya bisa diedit jika status Menunggu atau Revisi.
     * Hanya Ormawa yang membuat proposal yang dapat mengedit.
     *
     * @urlParam id integer required ID Proposal
     * @bodyParam * optional Field-field dapat diupdate sebagian atau seluruhnya
     * @bodyParam file file optional File proposal baru (PDF, maksimal 5MB)
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Proposal kegiatan berhasil diperbarui",
     *   "data": {...}
     * }
     * @response 422 {
     *   "status": "error",
     *   "message": "Proposal tidak dapat diedit"
     * }
     */
    public function update(UpdateProposalRequest $request, ProposalKegiatan $proposal): JsonResponse
    {
        if ($proposal->status !== 'Menunggu' && $proposal->status !== 'Revisi') {
            return response()->json([
                'status' => 'error',
                'message' => 'Proposal tidak dapat diedit pada status saat ini',
                'errors' => ['status' => 'Status proposal harus Menunggu atau Revisi'],
            ], 422);
        }

        $data = $request->validated();

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($proposal->file);
            $data['file'] = $request->file('file')->store('proposals', 'public');
        }

        $proposal->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Proposal kegiatan berhasil diperbarui',
            'data' => new ProposalKegiatanResource($proposal->load('user')),
        ], 200);
    }

    /**
     * Hapus proposal kegiatan
     * 
     * Menghapus proposal. Hanya bisa dihapus jika belum disetujui.
     *
     * @urlParam id integer required ID Proposal
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Proposal kegiatan berhasil dihapus"
     * }
     * @response 422 {
     *   "status": "error",
     *   "message": "Proposal tidak dapat dihapus"
     * }
     */
    public function destroy(Request $request, ProposalKegiatan $proposal): JsonResponse
    {
        if ($proposal->status === 'Disetujui') {
            return response()->json([
                'status' => 'error',
                'message' => 'Proposal yang sudah disetujui tidak dapat dihapus',
            ], 422);
        }

        Storage::disk('public')->delete($proposal->file);
        $proposal->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Proposal kegiatan berhasil dihapus',
        ], 200);
    }

    /**
     * Cek status proposal
     * 
     * Melihat status dan detail verifikasi proposal.
     *
     * @urlParam id integer required ID Proposal
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Status proposal kegiatan",
     *   "data": {
     *     "id_proposal": 1,
     *     "status": "Menunggu",
     *     "catatan_admin": null,
     *     "anggaran_disetujui": null
     *   }
     * }
     */
    public function checkStatus(Request $request, ProposalKegiatan $proposal): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Status proposal kegiatan',
            'data' => [
                'id_proposal' => $proposal->id_proposal,
                'status' => $proposal->status,
                'catatan_admin' => $proposal->catatan_admin,
                'anggaran_disetujui' => $proposal->anggaran_disetujui,
            ],
        ], 200);
    }

    /**
     * Upload revisi proposal
     * 
     * Mengupload versi revisi dari proposal yang ditolak atau diminta revisi.
     *
     * @urlParam id integer required ID Proposal
     * @bodyParam ajuan_triwulan string required Pilihan triwulan (I, II, III, IV)
     * @bodyParam risiko_proposal string required Tingkat risiko (Rendah, Sedang, Tinggi)
     * @bodyParam nama_kegiatan string required Nama kegiatan, max 150 karakter
     * @bodyParam waktu_kegiatan date required Tanggal kegiatan
     * @bodyParam besar_ajuan decimal required Besar ajuan dana
     * @bodyParam catatan_revisi string required Catatan revisi
     * @bodyParam file file required File proposal revisi (PDF, maksimal 5MB)
     *
     * @response 201 {
     *   "status": "success",
     *   "message": "Revisi proposal berhasil diupload",
     *   "data": {...}
     * }
     */
    public function submitRevision(ReviseProposalRequest $request, ProposalKegiatan $proposal): JsonResponse
    {
        $filePath = $request->file('file')->store('revisions', 'public');

        $revision = RevisiProposal::create([
            'id_proposal' => $proposal->id_proposal,
            'ajuan_triwulan' => $request->ajuan_triwulan,
            'risiko_proposal' => $request->risiko_proposal,
            'nama_kegiatan' => $request->nama_kegiatan,
            'waktu_kegiatan' => $request->waktu_kegiatan,
            'besar_ajuan' => $request->besar_ajuan,
            'catatan_revisi' => $request->catatan_revisi,
            'file' => $filePath,
        ]);

        $proposal->update(['status' => 'Menunggu']);

        return response()->json([
            'status' => 'success',
            'message' => 'Revisi proposal berhasil diupload',
            'data' => [
                'revision' => new \App\Http\Resources\RevisiProposalResource($revision),
                'proposal_status' => $proposal->status,
            ],
        ], 201);
    }

    /**
     * Verifikasi proposal (Admin only)
     * 
     * Admin melakukan verifikasi dan keputusan terhadap proposal.
     * Dapat menyetujui, meminta revisi, atau menolak.
     *
     * @urlParam id integer required ID Proposal
     * @bodyParam status string required Status keputusan (Disetujui, Revisi, Ditolak)
     * @bodyParam catatan_admin string optional Catatan untuk pemohon
     * @bodyParam anggaran_disetujui decimal required_if Besar anggaran yang disetujui (wajib jika status Disetujui)
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Verifikasi proposal berhasil",
     *   "data": {...}
     * }
     * @response 403 {
     *   "status": "error",
     *   "message": "Unauthorized"
     * }
     */
    public function verify(VerifyProposalRequest $request, ProposalKegiatan $proposal): JsonResponse
    {
        $proposal->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
            'anggaran_disetujui' => $request->status === 'Disetujui' ? $request->anggaran_disetujui : null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Verifikasi proposal berhasil',
            'data' => new ProposalKegiatanResource($proposal->load('user')),
        ], 200);
    }
}
