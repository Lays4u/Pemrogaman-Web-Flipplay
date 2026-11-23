<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCollectionRequest;
use App\Http\Resources\UserResource;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserCollectionController extends Controller
{
    /**
     * GET /api/users
     * Menampilkan daftar pengguna terdaftar.
     */
    public function indexUsers(): AnonymousResourceCollection
    {
        $users = User::orderBy('id')->get();

        return UserResource::collection($users)->additional([
            'status' => 'success',
            'message' => 'Daftar pengguna berhasil diambil.',
        ]);
    }

    /**
     * GET /api/users/{user}/collections
     * Menampilkan koleksi media yang dimiliki seorang pengguna.
     */
    public function index(User $user): UserResource
    {
        $user->load('collections');

        return (new UserResource($user))->additional([
            'status' => 'success',
            'message' => "Koleksi media milik {$user->name} berhasil diambil.",
        ]);
    }

    /**
     * POST /api/users/{user}/collections
     * Custom Action: Menambahkan media ke dalam koleksi pribadi pengguna.
     */
    public function store(AddCollectionRequest $request, User $user): JsonResponse
    {
        $mediaId = $request->validated()['media_id'];

        if ($user->collections()->where('media_id', $mediaId)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Media sudah ada di dalam koleksi pengguna.',
                'errors' => [
                    'media_id' => ['Media yang sama tidak boleh ditambahkan dua kali ke koleksi pengguna yang sama.'],
                ],
            ], 422);
        }

        $user->collections()->attach($mediaId);
        $user->load('collections');

        return (new UserResource($user))
            ->additional([
                'status' => 'success',
                'message' => 'Media berhasil ditambahkan ke koleksi pengguna.',
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * DELETE /api/users/{user}/collections/{media}
     * Custom Action: Menghapus media dari koleksi pribadi pengguna.
     */
    public function destroy(User $user, Media $media): JsonResponse|UserResource
    {
        if (! $user->collections()->where('media_id', $media->id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Media tidak ditemukan di dalam koleksi pengguna ini.',
            ], 404);
        }

        $user->collections()->detach($media->id);
        $user->load('collections');

        return (new UserResource($user))->additional([
            'status' => 'success',
            'message' => 'Media berhasil dihapus dari koleksi pengguna.',
        ]);
    }
}
