<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Amanah\Common\Models\Article;
use Amanah\Common\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::accepted()
            ->orderByDesc('published_at')
            ->paginate(10);

        return ArticleResource::collection($articles);
    }
}
