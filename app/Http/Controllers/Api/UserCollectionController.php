<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MediaResource;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserCollectionController extends Controller
{
    /**
     * GET /api/users/{userId}/collection
     * Menampilkan daftar koleksi media milik pengguna
     */
    public function index(int $userId): JsonResponse
    {
        $user = User::with('media')->find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => "Pengguna dengan ID {$userId} tidak ditemukan.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => "Koleksi media milik {$user->name} berhasil diambil.",
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'data' => MediaResource::collection($user->media),
        ], 200);
    }

    /**
     * POST /api/users/{userId}/collection
     * Menambahkan media ke koleksi pengguna (Custom Action Attach)
     */
    public function store(Request $request, int $userId): JsonResponse
    {
        $request->validate([
            'media_id' => 'required|integer|exists:media,id',
        ], [
            'media_id.required' => 'ID media wajib disertakan.',
            'media_id.exists' => 'Media yang dipilih tidak terdaftar di sistem.',
        ]);

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => "Pengguna dengan ID {$userId} tidak ditemukan.",
            ], 404);
        }

        $mediaId = (int) $request->input('media_id');
        $media = Media::find($mediaId);

        // Validasi: Cek apakah media sudah ada dalam koleksi user (mencegah duplikasi)
        if ($user->media()->where('media_id', $mediaId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => "Media '{$media->title}' sudah ada di koleksi pengguna ini.",
            ], 422);
        }

        // Attach relasi ke pivot table
        $user->media()->attach($mediaId);

        return response()->json([
            'success' => true,
            'message' => "Berhasil menambahkan '{$media->title}' ke koleksi {$user->name}.",
            'data' => new MediaResource($media),
        ], 201);
    }

    /**
     * DELETE /api/users/{userId}/collection/{mediaId}
     * Menghapus media dari koleksi pengguna (Custom Action Detach)
     */
    public function destroy(int $userId, int $mediaId): JsonResponse
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => "Pengguna dengan ID {$userId} tidak ditemukan.",
            ], 404);
        }

        // Cek apakah media ada di koleksi user
        if (!$user->media()->where('media_id', $mediaId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => "Media ID {$mediaId} tidak ditemukan dalam koleksi {$user->name}.",
            ], 404);
        }

        // Detach relasi
        $user->media()->detach($mediaId);

        return response()->json([
            'success' => true,
            'message' => "Berhasil menghapus media ID {$mediaId} dari koleksi {$user->name}.",
        ], 200);
    }
}