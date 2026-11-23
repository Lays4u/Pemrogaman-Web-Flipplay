<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'genre' => $this->genre,
            'score' => $this->score,
            'episodes' => $this->episodes,
            'chapters' => $this->chapters,
            'volumes' => $this->volumes,
            'cover_image' => $this->cover_image,
            'site_url' => $this->site_url,
            'details' => $this->getDetails(),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}