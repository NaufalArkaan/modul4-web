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
        $mediaId = $this->route('medium') ?? $this->route('media');
        $id = is_object($mediaId) ? $mediaId->id : $mediaId;

        return [
            'title' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('media', 'title')->ignore($id),
            ],
            'type' => 'nullable|string|max:50',
            'genre' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:Ongoing,Finished',
            'episodes' => 'sometimes|required|integer|min:1',
            'score' => 'sometimes|required|numeric|between:0,10',
        ];
    }

    public function messages(): array
    {
        return [
            'title.unique' => 'Judul media sudah digunakan oleh data lain.',
            'status.in' => 'Status hanya boleh bernilai "Ongoing" atau "Finished".',
            'score.between' => 'Skor harus bernilai antara 0.0 sampai 10.0.',
        ];
    }
}
