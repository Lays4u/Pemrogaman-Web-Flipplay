<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'title',
        'type',
        'genre',
        'score',
        'episodes',
        'chapters',
        'volumes',
        'cover_image',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'float',
            'episodes' => 'integer',
            'chapters' => 'integer',
            'volumes' => 'integer',
        ];
    }

    /**
     * Relasi Many-to-Many: Media dimiliki dalam koleksi banyak pengguna.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'media_user')
            ->withTimestamps();
    }

    /**
     * Representasi detail dari struktur OOP FlipPlay Modul 3.
     */
    public function getDetails(): string
    {
        if ($this->type === 'anime') {
            $episodes = $this->episodes ?? '?';
            return "Anime: {$this->title} | Genre: {$this->genre} | Episode: {$episodes} | Score: {$this->score}%";
        }

        $chapters = $this->chapters ?? '?';
        $volumes = $this->volumes ?? '?';
        return "Manga: {$this->title} | Genre: {$this->genre} | Bab: {$chapters} | Volume: {$volumes} | Score: {$this->score}%";
    }
}
