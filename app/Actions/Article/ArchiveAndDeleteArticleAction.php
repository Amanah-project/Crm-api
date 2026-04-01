<?php

namespace App\Actions\Article;

use Amanah\Common\Models\Article;
use App\Models\ArticleArchive;
use Illuminate\Support\Facades\DB;

class ArchiveAndDeleteArticleAction
{
    /**
     * Archive given article and hard-delete it inside a DB transaction.
     *
     * @param Article $article
     * @return void
     * @throws \Throwable
     */
    public function handle(Article $article): void
    {
        DB::transaction(function () use ($article) {
            $archive = ArticleArchive::fromArticle($article);
            $archive->save();

            $article->delete();
        });
    }
}
