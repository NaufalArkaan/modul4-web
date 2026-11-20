<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Izinkan semua request untuk demo publik
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:media,title',
            'type' => 'nullable|string|max:50',
            'genre' => 'required|string|max:255',
            'status' => 'required|in:Ongoing,Finished',
            'episodes' => 'required|integer|min:1',
            'score' => 'required|numeric|between:0,10',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul media wajib diisi.',
            'title.unique' => 'Judul media sudah ada di dalam sistem.',
            'status.in' => 'Status hanya boleh bernilai "Ongoing" atau "Finished".',
            'score.between' => 'Skor harus berada pada rentang 0.0 sampai 10.0.',
        ];
    }
}