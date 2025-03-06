<?php

namespace App\DataTransferObjects;

use Carbon\Carbon;

class ArticleData
{
    public function __construct(
        public string $title,
        public ?string $description,
        public ?string $content,
        public string $url,
        public string $source,
        public ?string $category,
        public ?Carbon $published_at
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'] ?? '',
            $data['description'] ?? null,
            $data['content'] ?? null,
            $data['url'] ?? '',
            $data['source'] ?? '',
            $data['category'] ?? null,
            isset($data['published_at']) ? Carbon::parse($data['published_at']) : null
        );
    }

}
