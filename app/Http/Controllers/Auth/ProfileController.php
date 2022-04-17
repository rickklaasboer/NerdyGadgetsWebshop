<?php

namespace App\Http\Controllers\Auth;

use App\Auth\Annotations\Authorize;
use App\Http\Controllers\Controller;

#[Authorize(redirectTo: "/login")]
class ProfileController extends Controller
{
    /**
     * Profile page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show()
    {
        return response()->twig('auth/profile.twig', [
            'user' => auth()->getUser(),
        ]);
    }

    /**
     * Wishlist page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function wishlist()
    {
        return response()->twig('auth/wishlist.twig', [
            'user' => auth()->getUser(),
        ]);
    }

    /**
     * Log a user out
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function logout()
    {
        $session = $this->request->getSession();

        // Drop user from session
        $session->remove('user');

        return response()->redirect('/');
    }
}