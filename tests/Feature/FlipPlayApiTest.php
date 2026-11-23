<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlipPlayApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_list_search_update_and_delete_media(): void
    {
        // POST /api/media (Happy Path)
        $response = $this->postJson('/api/media', [
            'title' => 'Black Clover',
            'type' => 'anime',
            'genre' => 'Action, Fantasy',
            'score' => 86.5,
            'episodes' => 170,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'Black Clover');

        // POST /api/media (Validation: Empty & Duplicate)
        $this->postJson('/api/media', ['title' => ''])->assertStatus(422);
        $this->postJson('/api/media', [
            'title' => 'Black Clover',
            'type' => 'anime',
            'genre' => 'Action',
        ])->assertStatus(422);

        // GET /api/media?title=Black
        $this->getJson('/api/media?title=Black')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');

        $mediaId = $response->json('data.id');

        // PATCH /api/media/{id}
        $this->patchJson("/api/media/{$mediaId}", ['score' => 89.0])
            ->assertStatus(200)
            ->assertJsonPath('data.score', 89);

        // DELETE /api/media/{id}
        $this->deleteJson("/api/media/{$mediaId}")
            ->assertStatus(200);
    }

    public function test_user_collection_many_to_many_custom_actions(): void
    {
        $user = User::factory()->create();
        $media = Media::create([
            'title' => 'Frieren',
            'type' => 'anime',
            'genre' => 'Adventure, Fantasy',
            'score' => 93.0,
            'episodes' => 28,
        ]);

        // POST /api/users/{user}/collections
        $this->postJson("/api/users/{$user->id}/collections", [
            'media_id' => $media->id,
        ])->assertStatus(201);

        // Duplicate collection item must fail with 422
        $this->postJson("/api/users/{$user->id}/collections", [
            'media_id' => $media->id,
        ])->assertStatus(422);

        // GET /api/media/{media} with whenLoaded('users')
        $this->getJson("/api/media/{$media->id}")
            ->assertStatus(200)
            ->assertJsonCount(1, 'data.users');

        // DELETE /api/users/{user}/collections/{media}
        $this->deleteJson("/api/users/{$user->id}/collections/{$media->id}")
            ->assertStatus(200);
    }
}
