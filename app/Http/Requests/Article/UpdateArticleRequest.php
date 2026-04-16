<?php

namespace App\Http\Requests\Article;

use Amanah\Common\Models\Article;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $article = $this->route('article');

        return self::rulesFor($article instanceof Article ? $article : null);
    }

    public static function rulesFor(?Article $article = null): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'url' => ['sometimes', 'string', 'max:255',
                Rule::unique('articles', 'url')->ignore($article?->id),
            ],
            'load_text' => ['sometimes', 'nullable', 'string'],
            'language' => ['sometimes', 'nullable', 'string', 'max:5'],
            'type' => ['sometimes', 'nullable', 'in:news,analysis,opinion,wiki'],
            'published_at' => ['sometimes', 'nullable', 'date'],
            'author' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
