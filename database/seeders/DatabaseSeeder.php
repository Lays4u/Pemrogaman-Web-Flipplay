<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $jatmiko = User::create([
            'name' => 'Jatmiko Eka Pratama',
            'email' => 'jatmiko@flipplay.test',
            'password' => Hash::make('password123'),
        ]);

        $snk = Media::create([
            'title' => 'Shingeki no Kyojin',
            'type' => 'anime',
            'genre' => 'Action, Dark Fantasy',
            'score' => 90.5,
            'episodes' => 89,
            'cover_image' => 'https://s4.anilist.co/file/anilistcdn/media/anime/cover/large/bx16498-73IhOXpJZiMF.jpg',
            'site_url' => 'https://anilist.co/anime/16498',
        ]);

        Media::create([
            'title' => 'Solo Leveling',
            'type' => 'anime',
            'genre' => 'Action, Fantasy',
            'score' => 88.0,
            'episodes' => 12,
            'cover_image' => 'https://s4.anilist.co/file/anilistcdn/media/anime/cover/large/bx151807-it355ZgzquUd.png',
            'site_url' => 'https://anilist.co/anime/151807',
        ]);

        $onePiece = Media::create([
            'title' => 'One Piece',
            'type' => 'manga',
            'genre' => 'Action, Adventure',
            'score' => 91.2,
            'chapters' => 1110,
            'volumes' => 108,
            'cover_image' => 'https://s4.anilist.co/file/anilistcdn/media/manga/cover/large/bx30013-ulXvn0lzWvFT.jpg',
            'site_url' => 'https://anilist.co/manga/30013',
        ]);

        Media::create([
            'title' => 'Jujutsu Kaisen',
            'type' => 'manga',
            'genre' => 'Action, Supernatural',
            'score' => 87.5,
            'chapters' => 271,
            'volumes' => 30,
            'cover_image' => 'https://s4.anilist.co/file/anilistcdn/media/manga/cover/large/bx101517-H3TdM3g5ZUe9.jpg',
            'site_url' => 'https://anilist.co/manga/101517',
        ]);

        $jatmiko->collections()->attach([$snk->id, $onePiece->id]);

        $fetchedAnime = $this->seedFromAniList('ANIME', 15);
        $fetchedManga = $this->seedFromAniList('MANGA', 15);

        if (! $fetchedAnime || ! $fetchedManga) {
            $this->seedFallbackMedia();
        }
    }

    private function seedFromAniList(string $mediaType, int $limit): bool
    {
        $query = '
            query ($type: MediaType!, $perPage: Int!) {
                Page(page: 1, perPage: $perPage) {
                    media(type: $type, sort: POPULARITY_DESC) {
                        title { userPreferred }
                        genres
                        episodes
                        chapters
                        volumes
                        averageScore
                        coverImage { large }
                        siteUrl
                    }
                }
            }
        ';

        try {
            $response = Http::withoutVerifying()
                ->timeout(12)
                ->post('https://graphql.anilist.co', [
                    'query' => $query,
                    'variables' => [
                        'type' => $mediaType,
                        'perPage' => $limit,
                    ],
                ]);

            if (! $response->successful()) {
                return false;
            }

            $items = $response->json('data.Page.media', []);
            if (empty($items)) {
                return false;
            }

            foreach ($items as $item) {
                $rawTitle = $item['title']['userPreferred'] ?? null;
                if (! $rawTitle) {
                    continue;
                }

                $title = Str::limit(trim($rawTitle), 250, '');
                if (Media::whereRaw('LOWER(title) = ?', [strtolower($title)])->exists()) {
                    continue;
                }

                $genres = ! empty($item['genres'])
                    ? implode(', ', array_slice($item['genres'], 0, 3))
                    : 'Action, Adventure';

                Media::create([
                    'title' => $title,
                    'type' => strtolower($mediaType),
                    'genre' => $genres,
                    'score' => $item['averageScore'] ?? 82.0,
                    'episodes' => $mediaType === 'ANIME' ? ($item['episodes'] ?? null) : null,
                    'chapters' => $mediaType === 'MANGA' ? ($item['chapters'] ?? null) : null,
                    'volumes' => $mediaType === 'MANGA' ? ($item['volumes'] ?? null) : null,
                    'cover_image' => $item['coverImage']['large'] ?? null,
                    'site_url' => $item['siteUrl'] ?? null,
                ]);
            }

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function seedFallbackMedia(): void
    {
        $extras = [
            ['title' => 'Black Clover', 'type' => 'anime', 'genre' => 'Action, Fantasy', 'score' => 84.0, 'episodes' => 170, 'site_url' => 'https://anilist.co/anime/97940'],
            ['title' => 'Kimetsu no Yaiba', 'type' => 'anime', 'genre' => 'Action, Supernatural', 'score' => 86.0, 'episodes' => 26, 'site_url' => 'https://anilist.co/anime/101922'],
            ['title' => 'Sousou no Frieren', 'type' => 'anime', 'genre' => 'Adventure, Drama, Fantasy', 'score' => 93.0, 'episodes' => 28, 'site_url' => 'https://anilist.co/anime/154587'],
            ['title' => 'Naruto Shippuden', 'type' => 'anime', 'genre' => 'Action, Adventure', 'score' => 83.5, 'episodes' => 500, 'site_url' => 'https://anilist.co/anime/1735'],
            ['title' => 'Bleach: Sennen Kessen-hen', 'type' => 'anime', 'genre' => 'Action, Supernatural', 'score' => 89.5, 'episodes' => 26, 'site_url' => 'https://anilist.co/anime/116674'],
            ['title' => 'Tensei shitara Slime Datta Ken', 'type' => 'anime', 'genre' => 'Action, Adventure, Fantasy', 'score' => 82.5, 'episodes' => 48, 'site_url' => 'https://anilist.co/anime/101280'],
            ['title' => 'Chainsaw Man', 'type' => 'manga', 'genre' => 'Action, Horror, Supernatural', 'score' => 87.0, 'chapters' => 180, 'volumes' => 18, 'site_url' => 'https://anilist.co/manga/105778'],
            ['title' => 'Berserk', 'type' => 'manga', 'genre' => 'Action, Dark Fantasy, Drama', 'score' => 94.0, 'chapters' => 376, 'volumes' => 42, 'site_url' => 'https://anilist.co/manga/30002'],
            ['title' => 'Vinland Saga', 'type' => 'manga', 'genre' => 'Action, Adventure, Drama', 'score' => 90.5, 'chapters' => 214, 'volumes' => 28, 'site_url' => 'https://anilist.co/manga/30642'],
            ['title' => 'Tokyo Ghoul', 'type' => 'manga', 'genre' => 'Action, Mystery, Supernatural', 'score' => 85.0, 'chapters' => 144, 'volumes' => 14, 'site_url' => 'https://anilist.co/manga/63327'],
        ];

        foreach ($extras as $item) {
            if (! Media::whereRaw('LOWER(title) = ?', [strtolower($item['title'])])->exists()) {
                Media::create($item);
            }
        }
    }
}