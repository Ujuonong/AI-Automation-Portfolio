<?php

declare(strict_types=1);

namespace Portfolio\Core;

use Throwable;

/**
 * Application bootstrap: loads config, autoloader, session and routes.
 */
final class App
{
    public static bool $booted = false;

    public static function boot(): void
    {
        if (self::$booted) {
            return;
        }

        // Load environment + constants
        require_once BASE_PATH . '/app/Env.php';
        \Portfolio\Env::load(BASE_PATH . '/.env');
        require_once BASE_PATH . '/config/constants.php';

        // Timezone
        date_default_timezone_set(APP_TIMEZONE);

        // Autoloader (PSR-4 for Portfolio\ => app/)
        spl_autoload_register(static function (string $class): void {
            $prefix = 'Portfolio\\';
            if (!str_starts_with($class, $prefix)) {
                return;
            }

            $relative = explode('\\', substr($class, strlen($prefix)));

            $dir = APP_PATH;
            foreach ($relative as $index => $segment) {
                if ($index === count($relative) - 1) {
                    $file = $dir . '/' . $segment . '.php';
                    if (is_file($file)) {
                        require_once $file;
                        return;
                    }

                    $entry = self::findEntry($dir, $segment . '.php');
                    if ($entry !== null) {
                        require_once $dir . '/' . $entry;
                    }
                    return;
                }

                $next = $dir . '/' . $segment;
                if (is_dir($next)) {
                    $dir = $next;
                    continue;
                }

                $entry = self::findEntry($dir, $segment);
                if ($entry === null) {
                    return;
                }
                $dir = $dir . '/' . $entry;
            }
        });

        require_once APP_PATH . '/helpers/helpers.php';

        self::configureErrorHandling();
        Session::start();
        Session::set('_app_config', config('app'));

        // Share site settings with layouts where available (guarded for early boot failures).
        try {
            View::share('site', \Portfolio\Services\SiteService::settings());
        } catch (Throwable $e) {
            error_log('[App] Settings unavailable: ' . $e->getMessage());
            View::share('site', []);
        }

        error_log('[App] Booted in ' . APP_ENV . ' mode');

        self::$booted = true;
    }

    private static function findEntry(string $dir, string $name): ?string
    {
        if (!is_dir($dir)) {
            return null;
        }

        $needle = strtolower($name);
        foreach (scandir($dir) as $entry) {
            if (strtolower($entry) === $needle) {
                return $entry;
            }
        }

        return null;
    }

    public static function dispatch(): void
    {
        $router = new Router();
        require ROUTES_PATH . '/web.php';
        $request = Request::capture();

        $path = $request->path();
        View::share('path', $path);
        View::share('canonical_url', rtrim(APP_URL, '/') . $path);

        try {
            $matched = $router->dispatch($request);
            if (!$matched) {
                http_status(404);
                View::output('errors/404', [
                    'status'  => 404,
                    'title'   => '404 — Page Not Found',
                    'message' => 'The page you are looking for could not be found.',
                ], 'public');
            }
        } catch (\Portfolio\Exceptions\HttpException $e) {
            http_status($e->status);
            View::output('errors/' . $e->status, [
                'status'  => $e->status,
                'title'   => $e->status . ' — ' . $e->getMessage(),
                'message' => $e->getMessage(),
            ], 'public');
        } catch (Throwable $e) {
            self::renderServerError($e);
        }
    }

    private static function configureErrorHandling(): void
    {
        error_reporting(E_ALL);
        ini_set('display_errors', APP_DEBUG ? '1' : '0');
        ini_set('log_errors', '1');
        ini_set('error_log', STORAGE_PATH . '/logs/app.log');
    }

    private static function renderServerError(Throwable $e): void
    {
        error_log('[App] ' . $e->getMessage() . "\n" . $e->getTraceAsString());

        if (http_response_code() < 400) {
            http_status(500);
        }

        $message = APP_DEBUG ? $e->getMessage() : 'An unexpected error occurred. Please try again later.';

        if (PHP_SAPI === 'cli') {
            fwrite(STDERR, "[Error] {$message}\n");
            exit(1);
        }

        View::output('errors/500', [
            'status'  => 500,
            'title'   => '500 — Internal Server Error',
            'message' => $message,
        ], 'public');
    }
}