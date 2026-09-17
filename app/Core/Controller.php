<?php

declare(strict_types=1);

namespace Portfolio\Core;

/**
 * Base controller providing request/response access and view rendering.
 */
abstract class Controller
{
    protected Request $request;
    protected Response $response;

    public function __construct()
    {
        $this->request  = Request::capture();
        $this->response = new Response();
    }

    /**
     * Render a view (with optional layout) and echo it to the output.
     *
     * @param array<string, mixed> $data
     */
    protected function view(string $view, array $data = [], ?string $layout = null): void
    {
        echo View::render($view, $data, $layout);
    }

    /**
     * Return the rendered view string without echoing.
     */
    protected function render(string $view, array $data = [], ?string $layout = null): string
    {
        return View::render($view, $data, $layout);
    }

    protected function redirect(string $path): never
    {
        redirect($path);
    }

    protected function back(string $fallback = '/'): never
    {
        redirect($this->request->referer() !== '' ? $this->request->referer() : $fallback);
    }

    /**
     * Abort with an HTTP status page.
     */
    protected function abort(int $status, string $message = ''): never
    {
        http_status($status);
        View::output('errors/' . $status, [
            'title'   => $status . ' — ' . $this->statusText($status),
            'message' => $message,
            'status'  => $status,
        ], 'public');
        exit;
    }

    private function statusText(int $status): string
    {
        return match ($status) {
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            419 => 'Page Expired',
            500 => 'Internal Server Error',
            default => 'Error',
        };
    }

    protected function json(mixed $data, int $status = 200): never
    {
        $this->response->json($data, $status);
    }

    protected function withErrors(array $errors, array $input = []): never
    {
        Session::set('errors', $errors);
        Session::rememberOld($input);
        $this->back();
    }

    protected function withInput(): never
    {
        $this->back();
    }
}