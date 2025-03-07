<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Repositories\ArticleRepositoryInterface;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="News Aggregator API",
 *      description="API for accessing news articles",
 *      @OA\Contact(
 *          email="azam.feyznia@gmail.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 */
class ArticleController extends Controller
{
    public function __construct(protected ArticleRepositoryInterface $articleRepository)
    {}

    /**
     * Display a listing of the articles.
     * @OA\Get(
     *     path="/api/v1/articles",
     *     summary="Returns a list of articles",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Search query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *            type="array",
     *            @OA\Items(ref="#/components/schemas/ArticleResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Display error that the url doesn't exists"
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Display error when user is not authenticated"
     *     )
     * )
     */
    public function index(IndexArticleRequest $request)
    {
        $criteria = $request->only('q', 'source', 'category');
        $perPage = $request->input('per_page', 10); // Default to 10 articles per page
        $articles = $this->articleRepository->search($criteria, $perPage);

        return ArticleResource::collection($articles);
    }

    /**
     * Get article by id
     * @OA\Get(
     *     path="/api/v1/articles/{id}",
     *     summary="Returns article",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Article ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *            type="array",
     *            @OA\Items(ref="#/components/schemas/ArticleResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Display error that the url doesn't exists"
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Display error when user is not authenticated"
     *     )
     * )
     */
    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

}
