<?php

namespace App\Providers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigServiceProvider implements Provider
{
    /**
     * Register a service
     *
     * @return Environment
     */
    public function register(): Environment
    {
        // Create Twig loader
        $loader = new FilesystemLoader(BASE_PATH . './views/');

        // Create a new Twig instance
        $twig = new Environment($loader, [
            'cache' => BASE_PATH . './cache/',
            'debug' => env('APP_ENV') === 'dev',
            'auto_reload' => env('APP_ENV') === 'dev',
        ]);

        // Pass all functions to our Twig instance
        foreach ($this->registerFunctions() as $fn) {
            $twig->addFunction($fn);
        }

        // Finally, return our Twig instance
        return $twig;
    }

    /**
     * Register all functions which will be usable by twig
     *
     * @return TwigFunction[]
     */
    private function registerFunctions(): array
    {
        return [
            new TwigFunction('__', function (...$vars) {
                return __(...$vars);
            }),
            new TwigFunction('dd', function (...$vars) {
                dd($vars);
            }),
            new TwigFunction('dump', function (...$vars) {
                dump(...$vars);
            }),
            new TwigFunction('format_price_with_tax', function (...$vars) {
                return format_price_with_tax(...$vars);
            }),
            new TwigFunction('get_stock_color', function (...$vars) {
                return get_stock_color(...$vars);
            }),
            new TwigFunction('get_stock_string', function (...$vars) {
                return get_stock_string(...$vars);
            }),
            new TwigFunction('fahrenheit_to_celsius', function (...$vars) {
                return fahrenheit_to_celsius(...$vars);
            }),
            new TwigFunction('product_image_or_backup', function (...$vars) {
                return product_image_or_backup(...$vars);
            }),
            new TwigFunction('prettify_custom_field', function (...$vars) {
                return prettify_custom_field(...$vars);
            }),
            new TwigFunction('method_override', function ($method) {
                return "<input type='hidden' name='_method' value='$method' />";
            }),
            new TwigFunction('params_except', function (...$vars) {
                return params_except(...$vars);
            }),
            new TwigFunction('include_existing_parameters', function (...$vars) {
                return include_existing_parameters(...$vars);
            }),
            new TwigFunction('get_parameter', function (...$vars) {
                return get_parameter(...$vars);
            }),
            new TwigFunction('count', function (...$vars) {
                return count(...$vars);
            }),
            new TwigFunction('option_is_active', function (...$vars) {
                return option_is_active(...$vars);
            }),
            new TwigFunction('app', function (...$vars) {
                return app(...$vars);
            }),
            new TwigFunction('cart', function () {
                return cart();
            }),
            new TwigFunction('slice', function (...$vars) {
                return array_slice(...$vars);
            }),
            new TwigFunction('session', function (...$vars) {
                return session(...$vars);
            }),
            new TwigFunction('auth', function () {
                return auth();
            }),
            new TwigFunction('url', function () {
                return url();
            }),
            new TwigFunction('request', function () {
                return request();
            }),
        ];
    }
}