<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EmbedPreviewController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'url' => ['required', 'url'],
        ]);

        $url = $data['url'];

        try {
            $response = Http::timeout(7)->withHeaders([
                'User-Agent' => 'OpenReportMapBot/1.0 (+https://openreportmap.local)',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ])->get($url);
        } catch (\Throwable $exception) {
            throw ValidationException::withMessages([
                'url' => 'Unable to fetch preview for this link. Please check the address and try again.',
            ]);
        }

        if (! $response->successful()) {
            throw ValidationException::withMessages([
                'url' => 'Unable to fetch preview for this link. Please try again later.',
            ]);
        }

        $html = $response->body();
        $metadata = $this->extractMetadata($html, $url);

        if (empty(array_filter($metadata))) {
            throw ValidationException::withMessages([
                'url' => 'No preview information available for this link.',
            ]);
        }

        return $metadata;
    }

    private function extractMetadata(string $html, string $url): array
    {
        $document = new DOMDocument();
        libxml_use_internal_errors(true);
        $document->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXPath($document);

        $title = $this->getMetaContent($xpath, ['og:title', 'twitter:title'], $document->getElementsByTagName('title')->item(0)?->nodeValue ?? '');
        $description = $this->getMetaContent($xpath, ['og:description', 'twitter:description']);
        $siteName = $this->getMetaContent($xpath, ['og:site_name']);
        $image = $this->getMetaContent($xpath, ['og:image', 'twitter:image']);

        if ($image && ! Str::startsWith($image, ['http://', 'https://'])) {
            $image = $this->resolveRelativeUrl($url, $image);
        }

        $oEmbed = $this->discoverOEmbed($xpath, $url);

        return array_filter([
            'url' => $url,
            'title' => $title ? Str::limit(trim($title), 140) : null,
            'description' => $description ? Str::limit(trim($description), 240) : null,
            'site_name' => $siteName ? trim($siteName) : parse_url($url, PHP_URL_HOST),
            'image' => $image,
            'oembed' => $oEmbed,
        ], fn ($value) => $value !== null && $value !== '');
    }

    private function getMetaContent(DOMXPath $xpath, array $keys, ?string $fallback = null): ?string
    {
        foreach ($keys as $key) {
            $query = "//meta[translate(@property, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')='{$key}']";
            $node = $xpath->query($query)->item(0);
            if ($node?->getAttribute('content')) {
                return $node->getAttribute('content');
            }

            $query = "//meta[translate(@name, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')='{$key}']";
            $node = $xpath->query($query)->item(0);
            if ($node?->getAttribute('content')) {
                return $node->getAttribute('content');
            }
        }

        return $fallback;
    }

    private function discoverOEmbed(DOMXPath $xpath, string $url): ?array
    {
        $nodes = $xpath->query("//link[contains(translate(@type, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'json+oembed') or contains(translate(@type, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'xml+oembed')]");

        if (! $nodes || $nodes->count() === 0) {
            return null;
        }

        $href = $nodes->item(0)?->getAttribute('href');

        if (! $href) {
            return null;
        }

        $resolved = $this->resolveRelativeUrl($url, $href);

        try {
            $response = Http::timeout(5)->get($resolved);
        } catch (\Throwable $exception) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $contentType = strtolower($response->header('Content-Type', 'application/json'));

        if (str_contains($contentType, 'json')) {
            $payload = $response->json();

            return is_array($payload) ? $payload : null;
        }

        if (str_contains($contentType, 'xml')) {
            $xml = simplexml_load_string($response->body());

            if (! $xml) {
                return null;
            }

            return json_decode(json_encode($xml), true);
        }

        return null;
    }

    private function resolveRelativeUrl(string $base, string $relative): string
    {
        if (Str::startsWith($relative, ['http://', 'https://'])) {
            return $relative;
        }

        if (Str::startsWith($relative, '//')) {
            $scheme = parse_url($base, PHP_URL_SCHEME) ?: 'https';
            return $scheme.':'.$relative;
        }

        $baseParts = parse_url($base);

        $scheme = $baseParts['scheme'] ?? 'https';
        $host = $baseParts['host'] ?? '';
        $port = isset($baseParts['port']) ? ':'.$baseParts['port'] : '';
        $path = $baseParts['path'] ?? '/';

        $directory = rtrim(str_replace(basename($path), '', $path), '/');

        $relativePath = Str::startsWith($relative, '/')
            ? $relative
            : ($directory ? '/'.$directory.'/'.$relative : '/'.$relative);

        return sprintf('%s://%s%s%s', $scheme, $host, $port, $this->normalizePath($relativePath));
    }

    private function normalizePath(string $path): string
    {
        $segments = [];

        foreach (explode('/', $path) as $segment) {
            if ($segment === '' || $segment === '.') {
                continue;
            }

            if ($segment === '..') {
                array_pop($segments);
                continue;
            }

            $segments[] = $segment;
        }

        return '/'.implode('/', $segments);
    }
}
