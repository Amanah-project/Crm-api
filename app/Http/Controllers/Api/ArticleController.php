<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Validation\Article\StoreArticleRequest;
use App\Http\Resources\ArticleFullResource;
use Amanah\Common\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $order = in_array($request->query('order', 'desc'), ['asc', 'desc'], true) 
            ? $request->query('order', 'desc') 
            : 'desc';
        
        $articles = Article::query()
            ->withStatus($request->query('status', 'all'))
            ->orderBy('published_at', $order)
            ->paginate(10)
            ->withQueryString();

        return ArticleFullResource::collection($articles);
    }

    public function store(StoreArticleRequest $request)
    {
        $data = $request->validated();

        if (!empty($data['accepted'])) {
            $data['accepted_at'] = now();
        }

        unset($data['accepted']);

        $article = Article::create($data);

        return new ArticleFullResource($article);
    }
}
