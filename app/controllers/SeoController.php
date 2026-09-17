<?php

declare(strict_types=1);

namespace Portfolio\Controllers;

use Portfolio\Core\Controller;
use Portfolio\Models\BlogPost;
use Portfolio\Models\Certificate;
use Portfolio\Models\Project;
use Portfolio\Services\SiteService;

/**
 * Lightweight SEO endpoint controllers (sitemap.xml / robots.txt).
 */
final class SeoController extends Controller
{
    public function sitemap(): void
    {
        $base = rtrim(APP_URL, '/');
        $now = date('Y-m-d');

        $urls = [];

        $staticPages = ['/', '/about', '/services', '/projects', '/certifications', '/experience', '/education', '/blog', '/contact'];
        foreach ($staticPages as $path) {
            $urls[] = ['loc' => $base . $path, 'lastmod' => $now, 'priority' => $path === '/' ? '1.0' : '0.8'];
        }

        foreach ((new Project())->featured(100, true) as $project) {
            $urls[] = ['loc' => $base . '/projects/' . $project['slug'], 'lastmod' => substr((string) $project['updated_at'], 0, 10), 'priority' => '0.9'];
        }

        foreach ((new BlogPost())->latest(100) as $post) {
            $urls[] = ['loc' => $base . '/blog/' . $post['slug'], 'lastmod' => substr((string) ($post['published_at'] ?? $post['updated_at']), 0, 10), 'priority' => '0.7'];
        }

        foreach ((new Certificate())->featuredPublished(100) as $cert) {
            $urls[] = ['loc' => $base . '/certifications', 'lastmod' => substr((string) $cert['updated_at'], 0, 10), 'priority' => '0.5'];
        }

        $xml = $this->buildSitemap($urls, $base . '/sitemap.xml');

        http_response_code(200);
        header('Content-Type: application/xml; charset=utf-8');
        echo $xml;
        exit;
    }

    public function robots(): void
    {
        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n";
        $robots .= "Disallow: /admin\n";
        $robots .= "Sitemap: " . rtrim(APP_URL, '/') . "/sitemap.xml\n";

        http_response_code(200);
        header('Content-Type: text/plain; charset=utf-8');
        echo $robots;
        exit;
    }

    private function buildSitemap(array $urls, string $canonicalSelf): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . e($u['loc']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . e($u['lastmod']) . '</lastmod>' . "\n";
            $xml .= '    <priority>' . e($u['priority']) . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }
        $xml .= '</urlset>';
        return $xml;
    }
}