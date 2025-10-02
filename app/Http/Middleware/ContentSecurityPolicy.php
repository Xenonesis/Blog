<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only apply CSP to HTML responses
        if ($response->headers->get('Content-Type') && strpos($response->headers->get('Content-Type'), 'text/html') === false) {
            return $response;
        }

        // Build CSP policy
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://fonts.googleapis.com https://www.googletagmanager.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com",
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            "img-src 'self' data: blob: https: http:",
            "connect-src 'self' https://api.github.com",
            "media-src 'self' blob: data:",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
            "upgrade-insecure-requests"
        ];

        // In development, be more permissive
        if (config('app.debug')) {
            $csp = [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' *",
                "style-src 'self' 'unsafe-inline' *",
                "font-src 'self' *",
                "img-src 'self' data: blob: *",
                "connect-src 'self' *",
                "media-src 'self' blob: data: *",
                "object-src 'none'",
                "base-uri 'self'",
                "form-action 'self'"
            ];
        }

        $cspHeader = implode('; ', $csp);
        
        // Use report-only mode in development for debugging
        $headerName = config('app.debug') ? 'Content-Security-Policy-Report-Only' : 'Content-Security-Policy';
        
        $response->headers->set($headerName, $cspHeader);

        return $response;
    }
}