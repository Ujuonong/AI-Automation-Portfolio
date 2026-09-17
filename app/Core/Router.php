<?php

declare(strict_types=1);

namespace Portfolio\Core;

/**
 * Minimal regex-based router with middleware support.
 *
 * Route definitions:
 *   GET  /about            => HomeController@about
 *   POST /admin/login      => AuthController@login
 *   GET  /projects/{slug}  => ProjectController@show
 */
final class Router
{
    private array $routes = [];
    private ?array $matched = null;
    private array $middleware = [];

    public function get(string $pattern, string $handler, array $middleware = []): void
    {
        $this->add('GET', $pattern, $handler, $middleware);
    }

    public function post(string $pattern, string $handler, array $middleware = []): void
    {
        $this->add('POST', $pattern, $handler, $middleware);
    }

    public function any(string $pattern, string $handler, array $middleware = []): void
    {
        $this->add('GET|POST', $pattern, $handler, $middleware);
    }

    private function add(string $methods, string $pattern, string $handler, array $middleware): void
    {
        $methodsList = explode('|', $methods);

        // Every state-changing request must pass the CSRF check.
        if (in_array('POST', $methodsList, true) && !in_array('CsrfToken', $middleware, true)) {
            $middleware[] = 'CsrfToken';
        }

        $this->routes[] = [
            'methods'     => $methodsList,
            'pattern'     => $pattern,
            'handler'     => $handler,
            'middleware'  => $middleware,
        ];
    }

    public function middleware(string $name): void
    {
        $this->middleware[] = $name;
    }

    /**
     * Dispatch the current request. Returns true if a route matched.
     */
    public function dispatch(Request $request): bool
    {
        $path = $request->path();
        $method = $request->method();

        foreach ($this->routes as $route) {
            if (!in_array($method, $route['methods'], true)) {
                continue;
            }

            $regex = $this->compile($route['pattern']);
            if (preg_match($regex, $path, $matches)) {
                array_shift($matches); // remove full match
                $this->matched = ['route' => $route, 'params' => array_values($matches)];
                return $this->runMiddlewareAndHandler($route, $this->matched['params']);
            }
        }

        return false;
    }

    private function compile(string $pattern): string
    {
        // Escape pattern and allow named placeholders like {slug}
        $pattern = preg_quote($pattern, '#');
        $pattern = preg_replace('#\\\{(\w+)\\\}#', '([^/]+)', $pattern);
        return '#^' . $pattern . '$#i';
    }

    private function runMiddlewareAndHandler(array $route, array $params): bool
    {
        foreach ($route['middleware'] as $alias) {
            $result = $this->resolveMiddleware($alias);
            if ($result !== true && $result !== null) {
                return false;
            }
            if ($result === false) {
                return false;
            }
        }

        [$controllerClass, $method] = explode('@', $route['handler']);
        $controllerClass = 'Portfolio\\Controllers\\' . $controllerClass;

        if (!class_exists($controllerClass)) {
            throw new \RuntimeException("Controller not found: {$controllerClass}");
        }

        $controller = new $controllerClass();
        if (!method_exists($controller, $method)) {
            throw new \RuntimeException("Controller method not found: {$controllerClass}@{$method}");
        }

        $controller->{$method}(...$params);
        return true;
    }

    private function resolveMiddleware(string $alias): mixed
    {
        $class = 'Portfolio\\Middleware\\' . $alias;
        if (!class_exists($class)) {
            throw new \RuntimeException("Middleware not found: {$class}");
        }

        $instance = new $class($this);
        return $instance->handle();
    }
}