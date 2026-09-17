<?php

declare(strict_types=1);

namespace Portfolio\Services;

/**
 * Converts known video URLs (YouTube/Vimeo) into safe embed URLs.
 * Returns null if the URL cannot be safely embedded.
 */
final class VideoService
{
    public static function embedUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        $url = trim($url);

        // YouTube: watch URLs, youtu.be short links, and direct embed/youtube-nocookie
        if (preg_match('~youtube\.com/watch\?v=([\w-]{5,20})~i', $url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/' . $m[1];
        }
        if (preg_match('~youtu\.be/([\w-]{5,20})~i', $url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/' . $m[1];
        }
        if (preg_match('~youtube\.com/embed/([\w-]{5,20})~i', $url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/' . $m[1];
        }
        if (preg_match('~youtube-nocookie\.com/embed/([\w-]{5,20})~i', $url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/' . $m[1];
        }
        if (preg_match('~youtube\.com/shorts/([\w-]{5,20})~i', $url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/' . $m[1];
        }

        // Vimeo
        if (preg_match('~vimeo\.com/(\d{5,12})~i', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        return null;
    }
}