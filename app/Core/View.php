<?php

declare(strict_types=1);

namespace Portfolio\Core;

/**
 * View renderer with layout + section support.
 */
final class View
{
    private static array $shared = [];

    public static function share(string $key, mixed $value): void
    {
        self::$shared[$key] = $value;
    }

    /**
     * Render a view inside a layout.
     *
     * @param array<string, mixed> $data
     */
    public static function render(string $view, array $data = [], string $layout = null): string
    {
        $data = array_merge(self::$shared, $data);
        $content = self::capture($view, $data);

        if ($layout === null) {
            return $content;
        }

        $data['layout_content'] = $content;
        return self::capture('layouts/' . $layout, $data);
    }

    /**
     * Render a partial view and echo it.
     *
     * @param array<string, mixed> $data
     */
    public static function partial(string $view, array $data = []): void
    {
        echo self::capture($view, $data);
    }

    /**
     * Render a view and echo it.
     */
    public static function output(string $view, array $data = [], string $layout = null): void
    {
        echo self::render($view, $data, $layout);
    }

    /**
     * Return rendered component with variables injected via the given prefix
     * so partials can read plain variables (e.g. $project).
     *
     * @param array<string, mixed> $data
     */
    public static function capture(string $view, array $data = []): string
    {
        $viewPath = self::resolve($view);
        if ($viewPath === null) {
            throw new \RuntimeException("View not found: {$view}");
        }

        extract($data, EXTR_SKIP);
        ob_start();
        include $viewPath;
        return (string) ob_get_clean();
    }

    private static function resolve(string $view): ?string
    {
        $relative = str_replace('.', '/', $view);
        $candidates = [
            APP_PATH . '/views/' . $relative . '.php',
            APP_PATH . '/views/' . $relative . '/index.php',
        ];
        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }
        return null;
    }
}