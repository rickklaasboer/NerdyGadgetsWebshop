<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Request;

class TransformRequest
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
        foreach ($request->request->all() as $key => $value) {

            // If value is numeric, add 0 to it, so it will appear as an integer in request
            if (is_numeric($value)) {
                $request->request->set($key, $value + 0);
            }

            // Remove whitespaces from string
            if (is_string($value)) {
                $request->request->set($key, trim($value));
            }

            // If value is an empty string, replace it with null
            if ($value === '') {
                $request->request->set($key, null);
            }
        }

        return $next($request);
    }
}