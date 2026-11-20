<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateMediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * GET /api/media
     * Menampilkan daftar seluruh media (mendukung filter pencarian ?title=...)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Media::query();

        // Query filter pencarian berdasarkan judul
        if ($request->has('title') && !empty($request->query('title'))) {
            $query->where('title', 'like', '%' . $request->query('title') . '%');
        }

        // Opsional: eager load users jika parameter include_users=true
        if ($request->boolean('include_users')) {
            $query->with('users');
        }

        $mediaList = $query->orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar media berhasil diambil.',
            'data' => MediaResource::collection($mediaList),
        ], 200);
    }

    /**
     * POST /api/media
     * Menambahkan media baru (Status Code: 201 Created)
     */
    public function store(StoreMediaRequest $request): JsonResponse
    {
        $media = Media::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Media berhasil ditambahkan ke library.',
            'data' => new MediaResource($media),
        ], 201);
    }

    /**
     * GET /api/media/{id}
     * Menampilkan detail media beserta user yang mengoleksinya (Eager Loading)
     */
    public function show(int $id): JsonResponse
    {
        $media = Media::with('users')->find($id);

        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => "Media dengan ID {$id} tidak ditemukan.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail media berhasil ditemukan.',
            'data' => new MediaResource($media),
        ], 200);
    }

    /**
     * PUT/PATCH /api/media/{id}
     * Mengupdate data media
     */
    public function update(UpdateMediaRequest $request, int $id): JsonResponse
    {
        $media = Media::find($id);

        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => "Media dengan ID {$id} tidak ditemukan.",
            ], 404);
        }

        $media->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Data media berhasil diperbarui.',
            'data' => new MediaResource($media),
        ], 200);
    }

    /**
     * DELETE /api/media/{id}
     * Menghapus media dari katalog
     */
    public function destroy(int $id): JsonResponse
    {
        $media = Media::find($id);

        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => "Media dengan ID {$id} tidak ditemukan.",
            ], 404);
        }

        $media->delete();

        return response()->json([
            'success' => true,
            'message' => "Media '{$media->title}' berhasil dihapus dari library.",
        ], 200);
    }
}