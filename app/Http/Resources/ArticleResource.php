<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="ArticleResource",
 *     description="Article resource data model",
 *     @OA\Xml(
 *         name="ArticleResource"
 *     ),
 *     @OA\Property(property="id", type="integer", description="Article ID"),
 *     @OA\Property(property="title", type="string", description="Article title"),
 *     @OA\Property(property="description", type="string", description="Article description"),
 *     @OA\Property(property="content", type="string", description="Article content"),
 *     @OA\Property(property="url", type="string", description="Article URL"),
 *     @OA\Property(property="source", type="string", description="Article source"),
 *     @OA\Property(property="category", type="string", description="Article category"),
 *     @OA\Property(property="published_at", type="string", format="date-time", description="Article publication date and time"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Article creation date and time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Article last update date and time")
 * )
 */
class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'url' => $this->url,
            'source' => $this->source,
            'category' => $this->category,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

}
