<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Http\HttpForbiddenException;
use App\Http\Controllers\Controller;
use App\Util\Cart;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class ProfileController extends Controller
{
    public function __construct(Environment $twig, Cart $cart, EntityManager $manager, Request $request)
    {
        parent::__construct($twig, $cart, $manager, $request);

        if (!auth()->isLoggedIn()) {
            throw new HttpForbiddenException();
        }
    }

    /**
     * Profile page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
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