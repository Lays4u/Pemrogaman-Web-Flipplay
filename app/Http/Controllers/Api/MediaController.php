<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateMediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MediaController extends Controller
{
    /**
     * GET /api/media
     * Menampilkan daftar seluruh media + query filter berdasarkan judul (?title=...)
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Media::query();

        $titleKeyword = $request->query('title') ?? $request->query('search');
        if (!empty($titleKeyword)) {
            $query->where('title', 'like', '%' . $titleKeyword . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->query('type'));
        }

        if ($request->filled('genre')) {
            $query->where('genre', 'like', '%' . $request->query('genre') . '%');
        }

        $mediaList = $query->orderBy('id')->get();

        return MediaResource::collection($mediaList)->additional([
            'status' => 'success',
            'message' => 'Daftar media berhasil diambil.',
        ]);
    }

    /**
     * POST /api/media
     * Menambahkan media baru ke dalam sistem.
     */
    public function store(StoreMediaRequest $request): JsonResponse
    {
        $media = Media::create($request->validated());

        return (new MediaResource($media))
            ->additional([
                'status' => 'success',
                'message' => 'Media baru berhasil ditambahkan.',
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * GET /api/media/{media}
     * Menampilkan detail satu media beserta pengguna yang memasukkannya ke koleksi.
     */
    public function show(Media $media): MediaResource
    {
        $media->load('users');

        return (new MediaResource($media))->additional([
            'status' => 'success',
            'message' => 'Detail media berhasil diambil.',
        ]);
    }

    /**
     * PUT/PATCH /api/media/{media}
     * Memperbarui data media.
     */
    public function update(UpdateMediaRequest $request, Media $media): MediaResource
    {
        $media->update($request->validated());

        return (new MediaResource($media))->additional([
            'status' => 'success',
            'message' => 'Data media berhasil diperbarui.',
        ]);
    }

    /**
     * DELETE /api/media/{media}
     * Menghapus media dari sistem.
     */
    public function destroy(Media $media): JsonResponse
    {
        $media->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Media berhasil dihapus dari sistem.',
        ], 200);
    }
}
