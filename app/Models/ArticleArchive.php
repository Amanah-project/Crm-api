<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Amanah\Common\Models\Article as SourceArticle;

class ArticleArchive extends Model
{
    protected $table = 'article_archives';

    protected $fillable = [
        'original_article_id',
        'title',
        'url',
        'load_text',
        'language',
        'type',
        'published_at',
        'author',
        'accepted_at',
        'accepted_by',
        'original_created_at',
        'original_updated_at',
        'archived_at',
    ];

    protected $casts = [
        'original_article_id' => 'integer',
        'accepted_by' => 'integer',
        'published_at' => 'datetime',
        'accepted_at' => 'datetime',
        'original_created_at' => 'datetime',
        'original_updated_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    public static function fromArticle(SourceArticle $article): self
    {
        $data = [
            'original_article_id' => $article->id,
            'title' => $article->title,
            'url' => $article->url,
            'load_text' => $article->load_text,
            'language' => $article->language,
            'type' => $article->type,
            'published_at' => $article->published_at,
            'author' => $article->author,
            'accepted_at' => $article->accepted_at,
            'accepted_by' => $article->accepted_by,
            'original_created_at' => $article->created_at,
            'original_updated_at' => $article->updated_at,
            'archived_at' => now(),
        ];

        return new self($data);
    }
}
