<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Repositories\ArticleRepositoryInterface;

class ArticleController extends Controller
{
    public function __construct(protected ArticleRepositoryInterface $articleRepository)
    {}

    public function index(IndexArticleRequest $request)
    {
        $criteria = $request->only('q', 'source', 'category');
        $perPage = $request->input('per_page', 10); // Default to 10 articles per page
        $articles = $this->articleRepository->search($criteria, $perPage);

        return ArticleResource::collection($articles);
    }

    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

}
