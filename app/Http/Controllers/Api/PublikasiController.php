<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StorePublikasiRequest;
use App\Http\Requests\UpdatePublikasiRequest;
use App\Http\Requests\VerifyPublikasiRequest;
use App\Http\Resources\PublikasiKegiatanResource;
use App\Models\PublikasiKegiatan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublikasiController
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = PublikasiKegiatan::with('user');

        if ($user->isOrmawa()) {
            $query->where('id_user', $user->id_user);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $publikasi = $query->latest()->paginate($request->per_page ?? 15);

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar publikasi kegiatan',
            'data' => PublikasiKegiatanResource::collection($publikasi->items()),
            'pagination' => [
                'total' => $publikasi->total(),
                'per_page' => $publikasi->perPage(),
                'current_page' => $publikasi->currentPage(),
                'total_pages' => $publikasi->lastPage(),
            ],
        ], 200);
    }

    public function store(StorePublikasiRequest $request): JsonResponse
    {
        $posterPath = $request->file('poster')->store('posters', 'public');

        $publikasi = PublikasiKegiatan::create([
            'id_user' => $request->user()->id_user,
            'judul' => $request->judul,
            'ormawa' => $request->ormawa,
            'caption' => $request->caption,
            'link' => $request->link,
            'poster' => $posterPath,
            'status' => 'Pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Publikasi kegiatan berhasil dibuat',
            'data' => new PublikasiKegiatanResource($publikasi->load('user')),
        ], 201);
    }

    public function show(Request $request, PublikasiKegiatan $publikasi): JsonResponse
    {
        $user = $request->user();
        if ($user->isOrmawa() && $publikasi->id_user !== $user->id_user) {
            return response()->json(['status' => 'error', 'message' => 'Forbidden'], 403);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail publikasi kegiatan',
            'data' => new PublikasiKegiatanResource($publikasi->load('user')),
        ], 200);
    }

    public function update(UpdatePublikasiRequest $request, PublikasiKegiatan $publikasi): JsonResponse
    {
        $user = $request->user();
        if ($user->isOrmawa() && $publikasi->id_user !== $user->id_user) {
            return response()->json(['status' => 'error', 'message' => 'Forbidden'], 403);
        }

        $data = $request->validated();

        if ($request->hasFile('poster')) {
            Storage::disk('public')->delete($publikasi->poster);
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $publikasi->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Publikasi kegiatan berhasil diperbarui',
            'data' => new PublikasiKegiatanResource($publikasi->load('user')),
        ], 200);
    }

    public function destroy(Request $request, PublikasiKegiatan $publikasi): JsonResponse
    {
        $user = $request->user();
        if ($user->isOrmawa() && $publikasi->id_user !== $user->id_user) {
            return response()->json(['status' => 'error', 'message' => 'Forbidden'], 403);
        }

        Storage::disk('public')->delete($publikasi->poster);
        $publikasi->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Publikasi kegiatan berhasil dihapus',
        ], 200);
    }

    public function verify(VerifyPublikasiRequest $request, PublikasiKegiatan $publikasi): JsonResponse
    {
        $publikasi->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
            'placement' => $request->status === 'Approved' ? ($request->placement ?? 'keduanya') : $publikasi->placement,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Verifikasi publikasi berhasil',
            'data' => new PublikasiKegiatanResource($publikasi->load('user')),
        ], 200);
    }
}
