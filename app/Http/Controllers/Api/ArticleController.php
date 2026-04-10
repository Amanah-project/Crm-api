<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\AcceptArticleActionRequest;
use App\Http\Requests\Article\StoreArticleRequest;
use App\Http\Requests\Article\UpdateArticleRequest;
use App\Http\Resources\ArticleFullResource;
use Amanah\Common\Models\Article;
use App\Actions\Article\ArchiveAndDeleteArticleAction;
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

    public function show(Article $article)
    {
        return new ArticleFullResource($article);
    }

    public function update(Request $request, Article $article)
    {
        switch ($request->input('action')) {
            case 'accept':
                return $this->handleAcceptAction($request, $article);

            default:
                return $this->handleFullUpdate($request, $article);
        }
    }
    
    public function destroy(Article $article, ArchiveAndDeleteArticleAction $action)
    {
        $action->handle($article);

        return response()->noContent();
    }

    private function handleFullUpdate(Request $request, Article $article)
    {
        $data = $request->validate(UpdateArticleRequest::rulesFor($article));

        $article->update($data);

        return new ArticleFullResource($article);
    }

    private function handleAcceptAction(Request $request, Article $article)
    {
        $data = $request->validate(AcceptArticleActionRequest::rulesFor());

        $article->update([
            'accepted_at' => $data['accepted'] ? now() : null,
        ]);

        return new ArticleFullResource($article);
    }
}
