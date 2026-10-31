<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectMotoHubTheme
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        if (
            $request->is('api', 'api/*', 'livewire/*', 'admin', 'admin/*')
            || ! str_contains((string) $response->headers->get('Content-Type'), 'text/html')
        ) {
            return $response;
        }

        $html = $response->getContent();

        if (! is_string($html) || ! str_contains(strtolower($html), '</head>')) {
            return $response;
        }

        if (str_contains($html, 'motohub-unified.css')) {
            return $response;
        }

        $stylesheet = '<link rel="stylesheet" href="' . e(asset('css/motohub-unified.css')) . '?v=1">';

        $response->setContent(
            preg_replace('/<\\/head>/i', $stylesheet . PHP_EOL . '</head>', $html, 1) ?? $html
        );

        return $response;
    }
}
