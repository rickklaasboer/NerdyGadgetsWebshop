<?php

namespace App\Http\Controllers;

use App\Util\Cart;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class Controller
{
    protected Environment $twig;
    protected Cart $cart;
    protected EntityManager $manager;
    protected Request $request;

    public function __construct(Environment $twig, Cart $cart, EntityManager $manager, Request $request)
    {
        $this->twig = $twig;
        $this->cart = $cart;
        $this->manager = $manager;
        $this->request = $request;
    }
}