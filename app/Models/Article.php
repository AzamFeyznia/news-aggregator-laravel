<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'articles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'content',
        'url',
        'source',
        'category',
        'published_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'datetime',  // To treat published_at as a Carbon instance
    ];

    /**
     * Scope a query to search articles based on a term.
     *
     * @param  Builder  $query
     * @param  string  $term
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        $term = '%' . $term . '%';
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', $term)
                ->orWhere('description', 'like', $term)
                ->orWhere('content', 'like', $term);
        });
    }

}
