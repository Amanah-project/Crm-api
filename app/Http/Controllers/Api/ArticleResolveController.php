<?php

namespace App\Http\Controllers\Api;

use App\Actions\Article\ResolveArticleAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResolveResource;
use App\Http\Requests\Article\ResolveArticleRequest;

class ArticleResolveController extends Controller
{
    public function store(ResolveArticleRequest $request, ResolveArticleAction $action)
    {
        $result = $action->handle($request->validated('url'));

        return (new ArticleResolveResource($result))->additional([
                'meta' => $result['meta'] ?? [],
            ])
            ->response()
            ->setStatusCode($result['status'] ?? 200);
    }
}