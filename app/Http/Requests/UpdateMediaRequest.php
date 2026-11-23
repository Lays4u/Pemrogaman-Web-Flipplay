<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $mediaId = $this->route('media')?->id ?? $this->route('media');

        return [
            'title' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('media', 'title')->ignore($mediaId),
            ],
            'type' => ['sometimes', 'required', 'string', 'in:anime,manga'],
            'genre' => ['sometimes', 'required', 'string', 'max:255'],
            'score' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:100'],
            'episodes' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'chapters' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'volumes' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'cover_image' => ['sometimes', 'nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul media tidak boleh dikosongkan.',
            'title.unique' => 'Judul media sudah digunakan oleh media lain.',
            'type.in' => 'Jenis media harus bernilai anime atau manga.',
            'genre.required' => 'Genre media tidak boleh dikosongkan.',
        ];
    }
}
