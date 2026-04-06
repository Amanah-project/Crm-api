<?php

namespace App\Services\Article;

use Illuminate\Support\Facades\Http;
use Throwable;

class ArticleHtmlFetcher
{
    public function fetch(string $url): array
    {
        try {
            $response = Http::timeout(10)
                ->connectTimeout(5)
                ->withHeaders([
                    'Accept' => 'text/html,application/xhtml+xml',
                ])
                ->withUserAgent('Amanah CRM API Article Resolver/1.0')
                ->get($url);

            if (! $response->successful()) {
                return [
                    'html' => null,
                    'error' => 'upstream_http_error',
                ];
            }

            $contentType = (string) $response->header('Content-Type', '');

            if ($contentType !== '' && ! str_contains(strtolower($contentType), 'text/html')) {
                return [
                    'html' => null,
                    'error' => 'unsupported_content_type',
                ];
            }

            $body = trim($response->body());

            if ($body === '') {
                return [
                    'html' => null,
                    'error' => 'empty_response_body',
                ];
            }

            return [
                'html' => $body,
                'error' => null,
            ];
        } catch (Throwable) {
            return [
                'html' => null,
                'error' => 'request_failed',
            ];
        }
    }
}