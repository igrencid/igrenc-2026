<?php

namespace App\Http\Middleware;

use App\Models\VisitorLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->isMethod('get')) {
            return $next($request);
        }

        if ($request->ajax() || $request->expectsJson() || $request->isJson()) {
            return $next($request);
        }

        if ($request->is('admin*') || $request->is('livewire*') || $request->is('storage*') || $request->is('build*') || $request->is('_debugbar*') || $request->is('favicon.ico')) {
            return $next($request);
        }

        $userAgent = $request->userAgent();
        $agent = strtolower($userAgent ?: '');

        if (Str::contains($agent, [
            'bot',
            'spider',
            'crawl',
            'slurp',
            'mediapartners-google',
            'facebookexternalhit',
            'twitterbot',
            'bingpreview',
            'curl',
            'python-requests',
            'wget',
            'java',
            'libwww-perl',
            'scan',
            'scanner',
        ])) {
            return $next($request);
        }

        VisitorLog::create([
            'session_id' => $request->session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent,
            'url' => $request->path(),
            'method' => $request->method(),
            'referer' => $request->headers->get('referer'),
            'visited_at' => now(),
        ]);

        return $next($request);
    }
}
