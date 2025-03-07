<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Repositories\ArticleRepositoryInterface;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(protected ArticleRepositoryInterface $articleRepository)
    {}

    public function index(Request $request)
    {
        $criteria = $request->only('q', 'source', 'category');
        $perPage = $request->input('per_page', 10); // Default to 10 articles per page
        $articles = $this->articleRepository->search($criteria, $perPage);

        return ArticleResource::collection($articles);
    }

    public function show(int $id)
    {
        $article = $this->articleRepository->findById($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return new ArticleResource($article);
    }

}
