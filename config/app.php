<?php

use App\Auth\Auth;
use App\Providers\AuthServiceProvider;
use App\Providers\DoctrineServiceProvider;
use App\Providers\MollieServiceProvider;
use App\Providers\RequestServiceProvider;
use App\Providers\TwigServiceProvider;
use App\Translation\Translation;
use App\Util\Cart;
use Doctrine\ORM\EntityManager;
use Mollie\Api\MollieApiClient;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

return [
    Environment::class => function () {
        return (new TwigServiceProvider())->register();
    },
    EntityManager::class => function () {
        return (new DoctrineServiceProvider())->register();
    },
    Request::class => function () {
        return (new RequestServiceProvider())->register();
    },
    Cart::class => function (ContainerInterface $c) {
        return new Cart($c->get(Request::class), $c->get(EntityManager::class));
    },
    Translation::class => function (ContainerInterface $c) {
        return new Translation($c->get(Request::class));
    },
    Auth::class => function () {
        return (new AuthServiceProvider())->register();
    },
    MollieApiClient::class => function () {
        return (new MollieServiceProvider())->register();
    }
];
