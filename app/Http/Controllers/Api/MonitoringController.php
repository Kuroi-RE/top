<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProposalKegiatanResource;
use App\Models\ProposalKegiatan;
use App\Models\LpjKegiatan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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

        if ($request->has('ormawa_name')) {
            $query->whereHas('user', fn($q) => $q->where('ormawa_name', $request->ormawa_name));
        }

        if ($request->has('ormawa_type')) {
            $query->whereHas('user', fn($q) => $q->where('ormawa_type', $request->ormawa_type));
        }

        if ($request->has('by_ormawa')) {
            $query->where('id_user', $request->by_ormawa);
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
        // Status yang sudah disetujui anggaran: Approved, Cek LPJ, Revisi LPJ, Selesai
        $approvedStatuses = ['Approved', 'Cek LPJ', 'Revisi LPJ', 'Selesai'];

        $totalDiajukan = ProposalKegiatan::sum('besar_ajuan');
        $totalDisetujui = ProposalKegiatan::whereIn('status', $approvedStatuses)->sum('anggaran_disetujui');
        $totalDitolak = ProposalKegiatan::where('status', 'Rejected')->count();
        $totalMenunggu = ProposalKegiatan::where('status', 'Pending')->count();
        $totalRevisi = ProposalKegiatan::where('status', 'Revision')->count();

        // Breakdown per triwulan
        $byTriwulan = [];
        foreach (['I', 'II', 'III', 'IV'] as $triwulan) {
            $proposals = ProposalKegiatan::where('ajuan_triwulan', $triwulan)->get();
            $byTriwulan[$triwulan] = [
                'total_diajukan' => $proposals->sum('besar_ajuan'),
                'total_disetujui' => $proposals->whereIn('status', $approvedStatuses)->sum('anggaran_disetujui'),
                'total_persentase_persetujuan' => $proposals->count() > 0 ? 
                    round(($proposals->whereIn('status', $approvedStatuses)->count() / $proposals->count()) * 100, 2) : 0,
                'proposal_count' => $proposals->count(),
                'approved_count' => $proposals->whereIn('status', $approvedStatuses)->count(),
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
                        round((ProposalKegiatan::where('status', 'Approved')->count() / ProposalKegiatan::count()) * 100, 2) : 0,
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
        $approvedStatuses = ['Approved', 'Cek LPJ', 'Revisi LPJ', 'Selesai'];
        $disetujui = ProposalKegiatan::whereIn('status', $approvedStatuses)->count();
        $ditolak = ProposalKegiatan::where('status', 'Rejected')->count();
        $menunggu = ProposalKegiatan::where('status', 'Pending')->count();
        $revisi = ProposalKegiatan::where('status', 'Revision')->count();

        $totalLpj = LpjKegiatan::count();
        $lpjDisetujui = LpjKegiatan::where('status_lpj', 'Approved')->count();
        $lpjMenunggu = LpjKegiatan::where('status_lpj', 'Menunggu')->count();
        $lpjRevisi = LpjKegiatan::where('status_lpj', 'Revision')->count();

        $totalAnggaran = ProposalKegiatan::sum('besar_ajuan');
        $totalDisetujuiAgg = ProposalKegiatan::whereIn('status', $approvedStatuses)->sum('anggaran_disetujui');

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

    /**
     * Statistik prestasi mahasiswa
     * 
     * Mendapatkan statistik lengkap prestasi mahasiswa keseluruhan.
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Statistik prestasi mahasiswa",
     *   "data": {...}
     * }
     */
    public function prestasiStats(Request $request): JsonResponse
    {
        if (!($request->user()->isDpmbem() || $request->user()->isAdmin())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden - Hanya DPMBEM dan Admin yang dapat mengakses',
            ], 403);
        }

        $total = \App\Models\Prestasi::count();
        $valid = \App\Models\Prestasi::where('status_verifikasi', 'Valid')->count();
        $pending = \App\Models\Prestasi::where('status_verifikasi', 'Pending')->count();
        $revisi = \App\Models\Prestasi::where('status_verifikasi', 'Revisi')->count();

        $byTingkat = \App\Models\Prestasi::selectRaw('tingkat, count(*) as count')
            ->groupBy('tingkat')
            ->get()
            ->pluck('count', 'tingkat');

        $byKategori = \App\Models\Prestasi::selectRaw('kategori, count(*) as count')
            ->groupBy('kategori')
            ->get()
            ->pluck('count', 'kategori');

        return response()->json([
            'status' => 'success',
            'message' => 'Statistik prestasi mahasiswa',
            'data' => [
                'total' => $total,
                'valid' => $valid,
                'pending' => $pending,
                'revision_needed' => $revisi,
                'by_tingkat' => $byTingkat,
                'by_kategori' => $byKategori,
            ],
        ], 200);
    }

    /**
     * Export PDF Monitoring Anggaran
     */
    public function exportAnggaranPdf(Request $request)
    {
        if (!($request->user()->isDpmbem() || $request->user()->isAdmin())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden - Hanya DPMBEM dan Admin yang dapat mengakses',
            ], 403);
        }

        $approvedStatuses = ['Approved', 'Cek LPJ', 'Revisi LPJ', 'Selesai'];
        $totalProposal  = ProposalKegiatan::count();
        $totalDiajukan  = ProposalKegiatan::sum('besar_ajuan');
        $totalDisetujui = ProposalKegiatan::whereIn('status', $approvedStatuses)->sum('anggaran_disetujui');
        $totalLpj       = LpjKegiatan::count();
        $lpjDisetujui   = LpjKegiatan::where('status_lpj', 'Disetujui')->count();
        $lpjRevisi      = LpjKegiatan::where('status_lpj', 'Revisi')->count();

        $proposals = ProposalKegiatan::with('user')
            ->select('id_proposal', 'id_user', 'ajuan_triwulan', 'nama_kegiatan', 'besar_ajuan', 'anggaran_disetujui', 'status')
            ->orderByDesc('id_proposal')
            ->limit(8)
            ->get();

        $summary = [
            'totalProposal'  => $totalProposal,
            'totalDiajukan'  => $totalDiajukan,
            'totalDisetujui' => $totalDisetujui,
            'totalLpj'       => $totalLpj,
            'lpjDisetujui'   => $lpjDisetujui,
            'lpjRevisi'      => $lpjRevisi,
        ];

        $pdf = Pdf::loadView("admin.monitoring_anggaran_export_pdf", compact("summary", "proposals"));
        return $pdf->download("monitoring_anggaran_" . now()->format('YmdHis') . ".pdf");
    }

    /**
     * Export PDF Beranda Ormawa
     */
    public function exportBerandaOrmawaPdf(Request $request)
    {
        if (!($request->user()->isDpmbem() || $request->user()->isAdmin())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden - Hanya DPMBEM dan Admin yang dapat mengakses',
            ], 403);
        }

        $query = ProposalKegiatan::with('user')->where('category', 'Ormawa')->orWhereNull('category');
        
        $proposals = $query->get()->filter(function ($p) {
            return ($p->category ?? 'Ormawa') === 'Ormawa';
        });

        if ($request->has('jenis_ormawa') && $request->jenis_ormawa) {
            $proposals = $proposals->filter(function ($p) use ($request) {
                $role = strtolower($p->user->role ?? '');
                return str_contains($role, strtolower($request->jenis_ormawa));
            });
        }

        if ($request->has('nama_ormawa') && $request->nama_ormawa) {
            $proposals = $proposals->filter(function ($p) use ($request) {
                $name = strtolower(($p->user->nama_depan ?? '') . ' ' . ($p->user->nama_belakang ?? '') . ' ' . ($p->user->username ?? ''));
                return str_contains($name, strtolower($request->nama_ormawa));
            });
        }

        $lpjs = LpjKegiatan::whereIn('id_proposal', $proposals->pluck('id_proposal'))->get()->keyBy('id_proposal');

        $statusMap = [
            'Pending' => 'Menunggu',
            'Revision' => 'Revisi',
            'Approved' => 'Disetujui',
            'Rejected' => 'Ditolak',
            'Cek LPJ' => 'Cek LPJ',
            'Revisi LPJ' => 'Revisi LPJ',
            'Selesai' => 'Selesai',
        ];

        $activities = $proposals->map(function ($p) use ($statusMap, $lpjs) {
            $rawStatus = $p->status ?? '';
            $mappedStatus = $statusMap[$rawStatus] ?? $rawStatus;
            $lpj = $lpjs->get($p->id_proposal);

            if ($mappedStatus === 'Disetujui' && $lpj && ($lpj->status_lpj ?? '') === 'Menunggu') {
                $mappedStatus = 'Cek LPJ';
            }

            $formattedDate = $p->waktu_kegiatan 
                ? \Carbon\Carbon::parse($p->waktu_kegiatan)->format('d F Y') 
                : '-';

            return [
                'tw' => $p->ajuan_triwulan ?? '-',
                'ormawa' => $p->user->nama_belakang ?? $p->user->username ?? 'Ormawa',
                'nama_kegiatan' => $p->nama_kegiatan ?? '-',
                'resiko' => $p->risiko_proposal ?? '-',
                'waktu' => $formattedDate,
                'ajuan' => 'Rp ' . number_format((float) ($p->besar_ajuan ?? 0), 0, ',', '.'),
                'anggaran' => 'Rp ' . number_format((float) ($p->anggaran_disetujui ?? 0), 0, ',', '.'),
                'status' => $mappedStatus,
            ];
        })->toArray();

        $jenisOrmawaText = $request->jenis_ormawa ? 'Ormawa ' . ucfirst($request->jenis_ormawa) : 'Semua Jenis Ormawa';
        $namaOrmawaText = $request->nama_ormawa ?? 'Semua Ormawa';

        $statusStyles = [
            'Selesai' => 'done',
            'Pencairan' => 'pending',
            'Acc' => 'info',
            'Revisi' => 'revisi',
            'Ajuan baru' => 'new',
        ];

        $pdf = Pdf::loadView("admin.beranda_ormawa_export_pdf", compact("activities", "statusStyles", "jenisOrmawaText", "namaOrmawaText"));
        return $pdf->download("beranda_ormawa_" . now()->format('YmdHis') . ".pdf");
    }
}
