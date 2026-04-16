<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class AcceptArticleActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return self::rulesFor();
    }

    public static function rulesFor(): array
    {
        return [
            'accepted' => ['required', 'boolean'],
        ];
    }
}
