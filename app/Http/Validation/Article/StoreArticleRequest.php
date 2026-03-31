<?php

namespace App\Http\Validation\Article;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255', 'unique:articles,url'],
            'load_text' => ['nullable', 'string'],
            'language' => ['nullable', 'string', 'max:5'],
            'type' => ['nullable', 'in:news,analysis,opinion,wiki'],
            'published_at' => ['nullable', 'date'],
            'author' => ['nullable', 'string', 'max:255'],
            'accepted' => ['sometimes', 'boolean'],
        ];
    }
}