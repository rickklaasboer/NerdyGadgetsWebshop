<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Request;

class DetermineUserLanguage
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
        $session = $request->getSession();

        // Automatically set preferred language based on the browser's language
        // Only executes when the language has not yet been set.
        if (!$session->get('lang')) {
            $session->set('lang', substr($request->server->get('HTTP_ACCEPT_LANGUAGE', 'en_US'), 0, 2));
        }

        return $next($request);
    }
}