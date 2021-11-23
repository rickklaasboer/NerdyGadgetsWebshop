<?php

namespace App\Http\Middleware;

use App\Auth\Auth;
use App\Entities\User;
use Carbon\Carbon;
use Closure;
use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class Authenticate
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
        try {
            $session = $request->getSession();

            if ($user = $session->get('user')) {
                ['id' => $id, 'validUntil' => $validUntil] = $user;

                // Session has expired
                if (!Carbon::now()->isBefore($validUntil)) {
                    // If session has expired, remove user from session.
                    $session->remove('user');
                    return $next($request);
                }

                $user = db()->getRepository(User::class)->find($id);

                // Terminate when user could not be found
                if (!$user) {
                    return $next($request);
                }

                /** @var Auth $auth */
                $auth = app(Auth::class);

                // Bind user to the auth service
                $auth->setUser($user);

            }
        } catch (Exception) {
            // Just assume user is not logged in
        }

        return $next($request);
    }
}