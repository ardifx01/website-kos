<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log aktivitas setelah request selesai diproses
        $this->logActivity($request, $response);

        return $response;
    }

    protected function logActivity($request, $response)
    {
        // Skip logging untuk routes tertentu
        $skipRoutes = [
            'activity-log',
            'livewire',
            '_ignition',
            'telescope',
            'horizon'
        ];

        foreach ($skipRoutes as $skipRoute) {
            if (str_contains($request->path(), $skipRoute)) {
                return;
            }
        }

        // Skip untuk asset files
        if (in_array($request->method(), ['GET']) && 
            preg_match('/\.(js|css|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|eot)$/', $request->path())) {
            return;
        }

        if (Auth::check()) {
            $event = $this->getEventType($request);
            $tableName = $this->getTableName($request);
            
            if ($event && $tableName) {
                ActivityLogger::logCustom(
                    $event,
                    $tableName,
                    null,
                    [
                        'route' => $request->route() ? $request->route()->getName() : $request->path(),
                        'method' => $request->method(),
                        'url' => $request->fullUrl(),
                        'status' => $response->getStatusCode()
                    ],
                    'HTTP Request: ' . $request->method() . ' ' . $request->path()
                );
            }
        }
    }

    protected function getEventType($request)
    {
        $method = $request->method();
        
        switch ($method) {
            case 'POST':
                return 'http_post';
            case 'PUT':
            case 'PATCH':
                return 'http_put';
            case 'DELETE':
                return 'http_delete';
            case 'GET':
                return 'http_get';
            default:
                return 'http_request';
        }
    }

    protected function getTableName($request)
    {
        // Coba extract nama table dari route atau path
        $path = $request->path();
        
        // Contoh: users/1/edit -> users
        if (preg_match('/^([a-zA-Z0-9_-]+)/', $path, $matches)) {
            return $matches[1];
        }
        
        return 'general';
    }
}