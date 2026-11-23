<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $jatmiko = User::create([
            'name' => 'Jatmiko Eka Pratama',
            'email' => 'jatmiko@flipplay.test',
            'password' => Hash::make('password123'),
        ]);

        $rudi = User::create([
            'name' => 'Rudi Mustakim',
            'email' => 'rudi@flipplay.test',
            'password' => Hash::make('password123'),
        ]);

        $snk = Media::create([
            'title' => 'Shingeki no Kyojin',
            'type' => 'anime',
            'genre' => 'Action, Dark Fantasy',
            'score' => 90.5,
            'episodes' => 89,
            'cover_image' => 'https://s4.anilist.co/file/anilistcdn/media/anime/cover/large/bx16498-73IhOXpJZiMF.jpg',
        ]);

        $soloLeveling = Media::create([
            'title' => 'Solo Leveling',
            'type' => 'anime',
            'genre' => 'Action, Fantasy',
            'score' => 88.0,
            'episodes' => 12,
            'cover_image' => 'https://s4.anilist.co/file/anilistcdn/media/anime/cover/large/bx151807-it355ZgzquUd.png',
        ]);

        $onePiece = Media::create([
            'title' => 'One Piece',
            'type' => 'manga',
            'genre' => 'Action, Adventure',
            'score' => 91.2,
            'chapters' => 1110,
            'volumes' => 108,
            'cover_image' => 'https://s4.anilist.co/file/anilistcdn/media/manga/cover/large/bx30013-ulXvn0lzWvFT.jpg',
        ]);

        $jjk = Media::create([
            'title' => 'Jujutsu Kaisen',
            'type' => 'manga',
            'genre' => 'Action, Supernatural',
            'score' => 87.5,
            'chapters' => 271,
            'volumes' => 30,
            'cover_image' => 'https://s4.anilist.co/file/anilistcdn/media/manga/cover/large/bx101517-H3TdM3g5ZUe9.jpg',
        ]);

        // Relasi awal sesuai simulasi Modul 3
        $jatmiko->collections()->attach([$snk->id, $onePiece->id]);
        $rudi->collections()->attach([$soloLeveling->id]);
    }
}
