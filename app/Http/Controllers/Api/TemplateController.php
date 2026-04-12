<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreTemplateRequest;
use App\Http\Requests\UpdateTemplateRequest;
use App\Http\Resources\TemplateDokumenResource;
use App\Http\Resources\TemplateDokumenCollection;
use App\Models\TemplateDokumen;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * @group Template Dokumen
 * Endpoints untuk pengelolaan template dokumen
 */
class TemplateController
{
    /**
     * Daftar semua template
     * 
     * Menampilkan daftar template dokumen yang tersedia.
     * Semua pengguna dapat mengakses endpoint ini.
     *
     * @queryParam jenis_template string Filter berdasarkan jenis template
     * @queryParam per_page integer Jumlah data per halaman (default: 15)
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Daftar template dokumen",
     *   "data": [...]
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $query = TemplateDokumen::query();

        if ($request->has('jenis_template')) {
            $query->where('jenis_template', $request->jenis_template);
        }

        $templates = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar template dokumen',
            'data' => TemplateDokumenCollection::make($templates->items()),
            'pagination' => [
                'total' => $templates->total(),
                'per_page' => $templates->perPage(),
                'current_page' => $templates->currentPage(),
                'total_pages' => $templates->lastPage(),
            ],
        ], 200);
    }

    /**
     * Tambah template dokumen (Admin only)
     * 
     * Membuat template dokumen baru untuk digunakan oleh user lain.
     *
     * @bodyParam nama_template string required Nama template (unik)
     * @bodyParam jenis_template string required Jenis/kategori template
     * @bodyParam file file required File template (PDF, maksimal 5MB)
     *
     * @response 201 {
     *   "status": "success",
     *   "message": "Template dokumen berhasil dibuat",
     *   "data": {...}
     * }
     * @response 403 {
     *   "status": "error",
     *   "message": "Unauthorized"
     * }
     */
    public function store(StoreTemplateRequest $request): JsonResponse
    {
        $filePath = $request->file('file')->store('templates', 'public');

        $template = TemplateDokumen::create([
            'nama_template' => $request->nama_template,
            'jenis_template' => $request->jenis_template,
            'file' => $filePath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Template dokumen berhasil dibuat',
            'data' => new TemplateDokumenResource($template),
        ], 201);
    }

    /**
     * Detail template dokumen
     * 
     * Melihat detail template dokumen.
     *
     * @urlParam id integer required ID Template
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Detail template dokumen",
     *   "data": {...}
     * }
     */
    public function show(TemplateDokumen $template): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Detail template dokumen',
            'data' => new TemplateDokumenResource($template),
        ], 200);
    }

    /**
     * Update template dokumen (Admin only)
     * 
     * Mengedit data template dokumen.
     *
     * @urlParam id integer required ID Template
     * @bodyParam nama_template string optional Nama template baru
     * @bodyParam jenis_template string optional Jenis template baru
     * @bodyParam file file optional File template baru (PDF, maksimal 5MB)
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Template dokumen berhasil diperbarui",
     *   "data": {...}
     * }
     */
    public function update(UpdateTemplateRequest $request, TemplateDokumen $template): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($template->file);
            $data['file'] = $request->file('file')->store('templates', 'public');
        }

        $template->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Template dokumen berhasil diperbarui',
            'data' => new TemplateDokumenResource($template),
        ], 200);
    }

    /**
     * Hapus template dokumen (Admin only)
     * 
     * Menghapus template dokumen dari sistem.
     *
     * @urlParam id integer required ID Template
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Template dokumen berhasil dihapus"
     * }
     */
    public function destroy(TemplateDokumen $template): JsonResponse
    {
        Storage::disk('public')->delete($template->file);
        $template->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Template dokumen berhasil dihapus',
        ], 200);
    }

    /**
     * Download template dokumen
     * 
     * Mengunduh file template dokumen.
     *
     * @urlParam id integer required ID Template
     *
     * @response 200 Download file template
     * @response 404 {
     *   "status": "error",
     *   "message": "File tidak ditemukan"
     * }
     */
    public function download(TemplateDokumen $template)
    {
        if (!Storage::disk('public')->exists($template->file)) {
            return response()->json([
                'status' => 'error',
                'message' => 'File tidak ditemukan',
            ], 404);
        }

        return Storage::disk('public')->download($template->file, $template->nama_template . '.pdf');
    }
}
