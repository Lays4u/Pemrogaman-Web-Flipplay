<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'unique:media,title'],
            'type' => ['required', 'string', 'in:anime,manga'],
            'genre' => ['required', 'string', 'max:255'],
            'score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'episodes' => ['nullable', 'integer', 'min:1'],
            'chapters' => ['nullable', 'integer', 'min:1'],
            'volumes' => ['nullable', 'integer', 'min:1'],
            'cover_image' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul media wajib diisi.',
            'title.unique' => 'Judul media sudah terdaftar dan tidak boleh duplikat.',
            'type.required' => 'Jenis media (anime atau manga) wajib diisi.',
            'type.in' => 'Jenis media harus bernilai anime atau manga.',
            'genre.required' => 'Genre media wajib diisi.',
            'score.numeric' => 'Skor harus berupa angka.',
            'score.min' => 'Skor minimal adalah 0.',
            'score.max' => 'Skor maksimal adalah 100.',
        ];
    }
}
