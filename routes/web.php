<?php

/**
 * Config file where all routes are defined
 *
 * @var RouteCollector $router
 */

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RatingController;
use FastRoute\RouteCollector;

$router->get('/', [HomeController::class, 'index']);

$router->get('/product/{id}', [ProductController::class, 'show']);

$router->post('/product/{stockItem}/rating', [RatingController::class, 'store']);

$router->get('/browse', [ProductController::class, 'index']);

$router->get('/cart', [CartController::class, 'show']);
$router->post('/cart', [CartController::class, 'add']);
$router->patch('/cart', [CartController::class, 'modify']);
$router->delete('/cart', [CartController::class, 'remove']);

$router->post('/cart/coupon', [CouponController::class, 'apply']);
$router->delete('/cart/coupon', [CouponController::class, 'unapply']);

$router->get('/checkout', [CheckoutController::class, 'overview']);
$router->post('/checkout/pay', [CheckoutController::class, 'pay']);
$router->get('/checkout/complete/{order}', [CheckoutController::class, 'callback']);

$router->post('/change', [LanguageController::class, 'change']);

$router->get('/login', [LoginController::class, 'view']);
$router->post('/login', [LoginController::class, 'handle']);

$router->get('/register', [RegisterController::class, 'view']);
$router->post('/register', [RegisterController::class, 'handle']);

$router->get('/profile', [ProfileController::class, 'show']);
$router->get('/profile/wishlist', [ProfileController::class, 'wishlist']);

$router->post('/logout', [ProfileController::class, 'logout']);

$router->addGroup('/api', function (RouteCollector $router) {
    $router->get('/cart', [CartController::class, 'api']);
});