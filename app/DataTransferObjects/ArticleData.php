<?php

namespace App\DataTransferObjects;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ArticleData
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $description,
        public readonly ?string $content,
        public readonly string $url,
        public readonly string $source,
        public readonly ?string $category,
        public readonly ?Carbon $published_at
    ) {
        $this->validate([
            'title' => $title,
            'url' => $url,
            'source' => $source,
            'published_at' => $published_at,
        ]);
    }

    public static function fromArray(array $data): self
    {
        $data = self::validate($data);

        return new self(
            $data['title'],
            $data['description'] ?? null,
            $data['content'] ?? null,
            $data['url'],
            $data['source'],
            $data['category'] ?? null,
            isset($data['published_at']) ? Carbon::parse($data['published_at']) : null
        );
    }

    private static function validate(array $data): array
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'source' => 'required|string|max:255',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }

}
