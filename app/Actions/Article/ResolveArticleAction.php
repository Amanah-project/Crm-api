<?php

namespace App\Actions\Article;

use App\Services\Article\ArticleHtmlFetcher;
use App\Services\Article\ArticleMetadataParser;

class ResolveArticleAction
{
    public function __construct(
        private ArticleHtmlFetcher $htmlFetcher,
        private ArticleMetadataParser $metadataParser,
    ) {
    }

    public function handle(string $url): array
    {
        $fetchResult = $this->htmlFetcher->fetch($url);
        $html = $fetchResult['html'];

        if (empty($html)) {
            return [
                'status' => 502,
                'data' => [
                    'title' => null,
                    'description' => null,
                    'author' => null,
                    'published_at' => null,
                    'language' => null,
                    'url' => $url,
                ],
                'meta' => [
                    'resolved' => false,
                    'warning' => 'Source page could not be fetched.',
                    'error' => $fetchResult['error'],
                ],
            ];
        }

        $metadata = $this->metadataParser->parse($html, $url);
        $missingFields = $this->missingFields($metadata);

        return [
            'status' => 200,
            'data' => $metadata,
            'meta' => [
                'resolved' => true,
                'warning' => $missingFields !== []
                    ? 'Some metadata could not be resolved.'
                    : null,
                'missing_fields' => $missingFields,
                'error' => null,
            ],
        ];
    }

    private function missingFields(array $metadata): array
    {
        $fields = ['title', 'description', 'author', 'published_at', 'language'];

        return array_values(array_filter(
            $fields,
            static fn (string $field): bool => empty($metadata[$field]),
        ));
    }
}