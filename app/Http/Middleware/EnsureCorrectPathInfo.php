<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Request;

class EnsureCorrectPathInfo
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
        // Check if route has any unnecessary trailing slashes and remove them.
        if (str_ends_with($request->getPathInfo(), '/')) {

            $trim = rtrim($request->getPathInfo(), '/');
            $url = empty($trim) ? '/' : $trim;

            if ($url !== '/') {
                return response()->redirect($url);
            }
        }

        return $next($request);
    }
}