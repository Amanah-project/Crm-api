<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResolveResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = $this->resource['data'] ?? [];

        return [
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'author' => $data['author'] ?? null,
            'published_at' => $data['published_at'] ?? null,
            'language' => $data['language'] ?? null,
            'url' => $data['url'] ?? null,
        ];
    }
}