<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequests
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $logPath = base_path('.cursor/debug.log');
        // #region agent log
        file_put_contents($logPath, json_encode([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'A,B,C,E',
            'location' => 'LogApiRequests.php:handle',
            'message' => 'API request received',
            'data' => [
                'method' => $request->method(),
                'path' => $request->path(),
                'fullUrl' => $request->fullUrl(),
                'ip' => $request->ip(),
                'userAgent' => $request->userAgent(),
                'hasContent' => $request->hasHeader('Content-Type'),
                'contentType' => $request->header('Content-Type'),
            ],
            'timestamp' => time() * 1000
        ]) . "\n", FILE_APPEND);
        // #endregion

        return $next($request);
    }
}

