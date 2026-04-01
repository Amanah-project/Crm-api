<?php

namespace App\Services\Article;

use Carbon\CarbonImmutable;
use DOMDocument;
use DOMXPath;
use Throwable;

class ArticleMetadataParser
{
    public function parse(string $html, string $fallbackUrl): array
    {
        $document = new DOMDocument();

        libxml_use_internal_errors(true);
        $loaded = $document->loadHTML($html);
        libxml_clear_errors();
        libxml_use_internal_errors(false);

        if (! $loaded) {
            return $this->emptyMetadata($fallbackUrl);
        }

        $xpath = new DOMXPath($document);

        $title = $this->firstNonEmpty([
            $this->metaByProperty($xpath, 'og:title'),
            $this->metaByName($xpath, 'twitter:title'),
            $this->nodeText($xpath, '//title[1]'),
        ]);

        $description = $this->firstNonEmpty([
            $this->metaByProperty($xpath, 'og:description'),
            $this->metaByName($xpath, 'description'),
            $this->metaByName($xpath, 'twitter:description'),
        ]);

        $author = $this->firstNonEmpty([
            $this->metaByProperty($xpath, 'article:author'),
            $this->metaByName($xpath, 'author'),
            $this->metaByName($xpath, 'parsely-author'),
        ]);

        $publishedAt = $this->normalizePublishedAt($this->firstNonEmpty([
            $this->metaByProperty($xpath, 'article:published_time'),
            $this->metaByName($xpath, 'article:published_time'),
            $this->metaByName($xpath, 'pubdate'),
            $this->metaByName($xpath, 'publishdate'),
            $this->metaByName($xpath, 'date'),
            $this->metaByProperty($xpath, 'og:published_time'),
            $this->timeNodeDateTime($xpath),
        ]));

        $language = $this->firstNonEmpty([
            $this->htmlLang($xpath),
            $this->metaByProperty($xpath, 'og:locale'),
            $this->metaByName($xpath, 'language'),
        ]);

        $url = $this->firstNonEmpty([
            $this->linkHref($xpath, 'canonical'),
            $this->metaByProperty($xpath, 'og:url'),
            $fallbackUrl,
        ]);

        return [
            'title' => $title,
            'description' => $description,
            'author' => $author,
            'published_at' => $publishedAt,
            'language' => $language,
            'url' => $url,
        ];
    }

    private function emptyMetadata(string $fallbackUrl): array
    {
        return [
            'title' => null,
            'description' => null,
            'author' => null,
            'published_at' => null,
            'language' => null,
            'url' => $fallbackUrl,
        ];
    }

    private function metaByProperty(DOMXPath $xpath, string $property): ?string
    {
        return $this->attributeValue(
            $xpath,
            sprintf("//meta[translate(@property, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')='%s']/@content", strtolower($property)),
        );
    }

    private function metaByName(DOMXPath $xpath, string $name): ?string
    {
        return $this->attributeValue(
            $xpath,
            sprintf("//meta[translate(@name, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')='%s']/@content", strtolower($name)),
        );
    }

    private function linkHref(DOMXPath $xpath, string $rel): ?string
    {
        return $this->attributeValue(
            $xpath,
            sprintf("//link[contains(translate(@rel, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), '%s')]/@href", strtolower($rel)),
        );
    }

    private function htmlLang(DOMXPath $xpath): ?string
    {
        return $this->attributeValue($xpath, '//html[1]/@lang');
    }

    private function timeNodeDateTime(DOMXPath $xpath): ?string
    {
        return $this->attributeValue($xpath, '(//time[@datetime][1])/@datetime');
    }

    private function nodeText(DOMXPath $xpath, string $expression): ?string
    {
        $nodes = $xpath->query($expression);

        if ($nodes === false || $nodes->length === 0) {
            return null;
        }

        return $this->normalizeValue($nodes->item(0)?->textContent);
    }

    private function attributeValue(DOMXPath $xpath, string $expression): ?string
    {
        $nodes = $xpath->query($expression);

        if ($nodes === false || $nodes->length === 0) {
            return null;
        }

        return $this->normalizeValue($nodes->item(0)?->nodeValue);
    }

    private function firstNonEmpty(array $values): ?string
    {
        foreach ($values as $value) {
            if (!empty($value)) {
                return $value;
            }
        }

        return null;
    }

    private function normalizeValue(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $normalized = trim(html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        return !empty($normalized) ? preg_replace('/\s+/u', ' ', $normalized) : null;
    }

    private function normalizePublishedAt(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            return CarbonImmutable::parse($value)->toDateTimeString();
        } catch (Throwable) {
            return null;
        }
    }
}