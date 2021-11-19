<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Request;

class SavePreviousUrl
{
    /**
     * Handle the incoming request
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->getMethod() === 'GET' && !str_starts_with($request->getPathInfo(), '/api')) {
            $request->getSession()->set('prev', $request->getRequestUri());
        }

        return $next($request);
    }
}