<?php

namespace App\Http\Controllers\Api;

use App\Models\Deadline;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeadlineController
{
    public function index(): JsonResponse
    {
        $deadline = Deadline::where('is_active', true)->latest()->first();

        return response()->json([
            'status' => 'success',
            'message' => $deadline ? 'Deadline aktif ditemukan' : 'Tidak ada deadline aktif',
            'data' => $deadline ? [
                'id' => $deadline->id,
                'title' => $deadline->title,
                'deadline_at' => $deadline->deadline_at?->toISOString(),
                'is_active' => $deadline->is_active,
                'sisa_hari' => now()->diffInDays($deadline->deadline_at, false),
            ] : null,
        ], 200);
    }

    public function all(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin() && !$request->user()->isSuperAdmin()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $deadlines = Deadline::orderByDesc('created_at')->get();

        return response()->json([
            'status' => 'success',
            'data' => $deadlines,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin() && !$request->user()->isSuperAdmin()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'deadline_at' => 'required|date|after:today',
        ]);

        Deadline::where('is_active', true)->update(['is_active' => false]);

        $deadline = Deadline::create([
            'title' => $request->title,
            'deadline_at' => $request->deadline_at,
            'is_active' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Deadline berhasil dibuat',
            'data' => $deadline,
        ], 201);
    }

    public function destroy(Request $request, Deadline $deadline): JsonResponse
    {
        if (!$request->user()->isAdmin() && !$request->user()->isSuperAdmin()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $deadline->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Deadline berhasil dihapus',
        ], 200);
    }
}
