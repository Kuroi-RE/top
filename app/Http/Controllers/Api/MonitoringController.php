<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProposalKegiatanResource;
use App\Models\ProposalKegiatan;
use App\Models\LpjKegiatan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Monitoring Anggaran
 * Endpoints untuk monitoring dan transparansi anggaran (DPMBEM + Admin only)
 */
class MonitoringController
{
    /**
     * Daftar seluruh kegiatan Ormawa
     * 
     * Menampilkan data seluruh pengajuan kegiatan dari semua Ormawa.
     * Hanya accessible oleh DPMBEM dan Admin (Kemahasiswaan).
     *
     * @queryParam status string Filter berdasarkan status proposal
     * @queryParam ajuan_triwulan string Filter berdasarkan triwulan
     * @queryParam per_page integer Jumlah data per halaman (default: 20)
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Daftar seluruh kegiatan Ormawa",
     *   "data": [...]
     * }
     * @response 403 {
     *   "status": "error",
     *   "message": "Forbidden - Only DPMBEM and Admin can access this"
     * }
     */
    public function activities(Request $request): JsonResponse
    {
        if (!($request->user()->isDpmbem() || $request->user()->isAdmin())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden - Hanya DPMBEM dan Admin yang dapat mengakses',
            ], 403);
        }

        $query = ProposalKegiatan::with('user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('ajuan_triwulan')) {
            $query->where('ajuan_triwulan', $request->ajuan_triwulan);
        }

        $proposals = $query->paginate($request->per_page ?? 20);

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar seluruh kegiatan Ormawa',
            'data' => ProposalKegiatanResource::collection($proposals->items()),
            'pagination' => [
                'total' => $proposals->total(),
                'per_page' => $proposals->perPage(),
                'current_page' => $proposals->currentPage(),
                'total_pages' => $proposals->lastPage(),
            ],
        ], 200);
    }

    /**
     * Transparansi anggaran
     * 
     * Menampilkan ringkasan transparansi anggaran yang diajukan vs disetujui
     * per triwulan dari semua Ormawa.
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Transparansi anggaran",
     *   "data": {
     *     "summary": {...},
     *     "by_triwulan": {...}
     *   }
     * }
     */
    public function budgetTransparency(Request $request): JsonResponse
    {
        if (!($request->user()->isDpmbem() || $request->user()->isAdmin())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden - Hanya DPMBEM dan Admin yang dapat mengakses',
            ], 403);
        }

        // Summary keseluruhan
        $totalDiajukan = ProposalKegiatan::sum('besar_ajuan');
        $totalDisetujui = ProposalKegiatan::where('status', 'Disetujui')->sum('anggaran_disetujui');
        $totalDitolak = ProposalKegiatan::where('status', 'Ditolak')->count();
        $totalMenunggu = ProposalKegiatan::where('status', 'Menunggu')->count();
        $totalRevisi = ProposalKegiatan::where('status', 'Revisi')->count();

        // Breakdown per triwulan
        $byTriwulan = [];
        foreach (['I', 'II', 'III', 'IV'] as $triwulan) {
            $proposals = ProposalKegiatan::where('ajuan_triwulan', $triwulan)->get();
            $byTriwulan[$triwulan] = [
                'total_diajukan' => $proposals->sum('besar_ajuan'),
                'total_disetujui' => $proposals->where('status', 'Disetujui')->sum('anggaran_disetujui'),
                'total_persentase_persetujuan' => $proposals->count() > 0 ? 
                    round(($proposals->where('status', 'Disetujui')->count() / $proposals->count()) * 100, 2) : 0,
                'proposal_count' => $proposals->count(),
                'approved_count' => $proposals->where('status', 'Disetujui')->count(),
            ];
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Transparansi anggaran',
            'data' => [
                'summary' => [
                    'total_anggaran_diajukan' => $totalDiajukan,
                    'total_anggaran_disetujui' => $totalDisetujui,
                    'persentase_persetujuan' => ProposalKegiatan::count() > 0 ?
                        round((ProposalKegiatan::where('status', 'Disetujui')->count() / ProposalKegiatan::count()) * 100, 2) : 0,
                    'total_proposal_menunggu' => $totalMenunggu,
                    'total_proposal_revisi' => $totalRevisi,
                    'total_proposal_ditolak' => $totalDitolak,
                ],
                'by_triwulan' => $byTriwulan,
            ],
        ], 200);
    }

    /**
     * Daftar LPJ seluruh Ormawa
     * 
     * Menampilkan status LPJ dari semua Ormawa untuk monitoring.
     *
     * @queryParam status_lpj string Filter berdasarkan status LPJ
     * @queryParam per_page integer Jumlah data per halaman (default: 20)
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Daftar LPJ seluruh Ormawa",
     *   "data": [...]
     * }
     */
    public function lpjList(Request $request): JsonResponse
    {
        if (!($request->user()->isDpmbem() || $request->user()->isAdmin())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden - Hanya DPMBEM dan Admin yang dapat mengakses',
            ], 403);
        }

        $query = LpjKegiatan::with('proposal.user');

        if ($request->has('status_lpj')) {
            $query->where('status_lpj', $request->status_lpj);
        }

        $lpjs = $query->paginate($request->per_page ?? 20);

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar LPJ seluruh Ormawa',
            'data' => \App\Http\Resources\LpjKegiatanResource::collection($lpjs->items()),
            'pagination' => [
                'total' => $lpjs->total(),
                'per_page' => $lpjs->perPage(),
                'current_page' => $lpjs->currentPage(),
                'total_pages' => $lpjs->lastPage(),
            ],
        ], 200);
    }

    /**
     * Detail kegiatan tertentu dengan LPJ terkait
     * 
     * Melihat detail lengkap kegiatan termasuk LPJ dan riwayat revisi.
     *
     * @urlParam id integer required ID Proposal
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Detail kegiatan",
     *   "data": {
     *     "proposal": {...},
     *     "lpj": [...],
     *     "revisions": [...]
     *   }
     * }
     */
    public function activityDetail(Request $request, ProposalKegiatan $proposal): JsonResponse
    {
        if (!($request->user()->isDpmbem() || $request->user()->isAdmin())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden - Hanya DPMBEM dan Admin yang dapat mengakses',
            ], 403);
        }

        $proposal->load('user', 'lpj', 'revisions');

        return response()->json([
            'status' => 'success',
            'message' => 'Detail kegiatan',
            'data' => [
                'proposal' => new ProposalKegiatanResource($proposal),
                'lpj' => \App\Http\Resources\LpjKegiatanResource::collection($proposal->lpj),
                'revisions' => \App\Http\Resources\RevisiProposalResource::collection($proposal->revisions),
            ],
        ], 200);
    }

    /**
     * Statistik monitoring anggaran
     * 
     * Mendapatkan statistik lengkap monitoring anggaran keseluruhan.
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Statistik monitoring anggaran",
     *   "data": {...}
     * }
     */
    public function statistics(Request $request): JsonResponse
    {
        if (!($request->user()->isDpmbem() || $request->user()->isAdmin())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden - Hanya DPMBEM dan Admin yang dapat mengakses',
            ], 403);
        }

        $totalProposal = ProposalKegiatan::count();
        $disetujui = ProposalKegiatan::where('status', 'Disetujui')->count();
        $ditolak = ProposalKegiatan::where('status', 'Ditolak')->count();
        $menunggu = ProposalKegiatan::where('status', 'Menunggu')->count();
        $revisi = ProposalKegiatan::where('status', 'Revisi')->count();

        $totalLpj = LpjKegiatan::count();
        $lpjDisetujui = LpjKegiatan::where('status_lpj', 'Disetujui')->count();
        $lpjMenunggu = LpjKegiatan::where('status_lpj', 'Menunggu')->count();
        $lpjRevisi = LpjKegiatan::where('status_lpj', 'Revisi')->count();

        $totalAnggaran = ProposalKegiatan::sum('besar_ajuan');
        $totalDisetujuiAgg = ProposalKegiatan::where('status', 'Disetujui')->sum('anggaran_disetujui');

        return response()->json([
            'status' => 'success',
            'message' => 'Statistik monitoring anggaran',
            'data' => [
                'proposal_statistics' => [
                    'total' => $totalProposal,
                    'approved' => $disetujui,
                    'rejected' => $ditolak,
                    'pending' => $menunggu,
                    'revision_needed' => $revisi,
                    'approval_rate' => $totalProposal > 0 ? 
                        round(($disetujui / $totalProposal) * 100, 2) : 0,
                ],
                'lpj_statistics' => [
                    'total' => $totalLpj,
                    'approved' => $lpjDisetujui,
                    'pending' => $lpjMenunggu,
                    'revision_needed' => $lpjRevisi,
                    'approval_rate' => $totalLpj > 0 ? 
                        round(($lpjDisetujui / $totalLpj) * 100, 2) : 0,
                ],
                'budget_statistics' => [
                    'total_budget_requested' => $totalAnggaran,
                    'total_budget_approved' => $totalDisetujuiAgg,
                    'budget_utilization_rate' => $totalAnggaran > 0 ? 
                        round(($totalDisetujuiAgg / $totalAnggaran) * 100, 2) : 0,
                ],
            ],
        ], 200);
    }
}
