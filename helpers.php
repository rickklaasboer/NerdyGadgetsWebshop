<?php

use App\Auth\Auth;
use App\Support\Facades\Facade;
use App\Support\LoadEnvironmentVariables;
use App\Support\Url;
use App\Translation\Translation;
use App\Util\Cart;
use App\Util\Response;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Request;

/**
 * Get something out of the environment, if the given key
 * was not set in the environment, return null or the given
 * default value
 *
 * @param $key
 * @param null $fallback
 * @return string|null
 */
function env($key, $fallback = null)
{

    if (!LoadEnvironmentVariables::hasBeenBootstrapped()) {
        (new LoadEnvironmentVariables)->bootstrap(__DIR__);
    }

    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }

    return $fallback;
}

/**
 * Helper function for starting the session if it is
 * not yet started
 *
 * @return void
 */
function start_session_if_not_exists()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Helper function to determine if an option inside a <select> should be active
 *
 * @param $parameter
 * @param $option
 * @param string $if_true
 * @param string $if_false
 * @return mixed|string
 */
function option_is_active($parameter, $option, $if_true = 'selected', $if_false = '')
{
    if ($parameter === $option) {
        return $if_true;
    }

    return $if_false;
}

/**
 * Get a parameter from $_GET with null checking
 *
 * @param $key
 * @param null $fallback
 * @return mixed|null
 */
function get_parameter($key, $fallback = null)
{
    if (isset($_GET[$key]) && !empty($_GET[$key])) {
        return $_GET[$key];
    }

    return $fallback;
}

/**
 * Get a parameter from $_POST with null checking
 *
 * @param $key
 * @param null $fallback
 * @return mixed|null
 */
function post_parameter($key, $fallback = null)
{
    if (isset($_POST[$key]) && !empty($_POST[$key])) {
        return $_POST[$key];
    }

    return $fallback;
}

/**
 * Generate a query string from $params and exclude values in $except
 *
 * @param array $params
 * @param array $except
 * @return array
 */
function params_except($params = [], $except = [])
{
    if (is_null($params)) {
        $params = $_GET;
    }

    $bag = [];

    foreach ($params as $key => $value) {
        if (is_array($value)) {
            foreach ($value as $request_key => $request_value) {
                if (!str_arr_contains($key, $except)) {
                    $bag[$key . "[$request_key]"] = $request_value;
                }
            }
        }

        if (!in_array($key, $except)) {
            $bag[$key] = $value;
        }
    };

    return $bag;
}

/**
 * Include existing get parameters by using hidden inputs (Ugh..)
 *
 * We should probably find a better way to do this
 * but when creating this it was 02:00 at night
 * so I could not be bothered.
 *
 * @param array $params
 */
function include_existing_parameters($params = [])
{
    foreach ($params as $key => $value) {
        if (is_array($value)) {
            continue;
        }

        $key = htmlspecialchars($key);
        $value = htmlspecialchars($value);

        print("<input type='hidden' name='$key' value='$value'/>");
    }
}

/**
 * Translate a string
 *
 * @param $key
 * @param array $replace
 * @param null $fallback
 * @return mixed
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 */
function __($key, array $replace = [], $fallback = null): mixed
{
    return app(Translation::class)->translate($key, $replace, $fallback);
}

/**
 * Call isset and empty at once
 *
 * @param $arg
 * @param $key
 * @return bool
 */
function isset_not_empty($arg, $key)
{
    return isset($arg[$key]) && !empty($arg[$key]);
}

/**
 * Get the stock string
 *
 * @param $stock
 * @return string
 */
function get_stock_string($stock)
{
    if ($stock < 500) {
        return 'stock.quantity.limited';
    }

    if ($stock > 1000) {
        return 'stock.quantity.many';
    }

    return 'stock.quantity.plenty';
}

/**
 * Get color associated with stock
 *
 * @param $stock
 * @return string
 */
function get_stock_color($stock)
{
    if ($stock < 500) {
        return 'danger';
    }

    return 'primary';
}

/**
 * Test if string contains value in array
 *
 * @param $needle
 * @param $haystack
 * @return bool
 */
function str_arr_contains($needle, $haystack)
{
    foreach ($haystack as $value) {
        if (str_contains($value, $needle)) {
            return true;
        }
    }

    return false;
}

/**
 * Filter all special characters from string
 *
 * @param $arg
 * @return string|string[]|null
 */
function sanitize_param($arg)
{
    return preg_replace("/[^a-zA-Z0-9]/", "", $arg);
}

/**
 * Prettify an custom field
 *
 * Example input: MySuperCoolInput
 * Example Output: My super cool input
 *
 * @param $value
 * @return string|string[]|null
 */
function prettify_custom_field($value)
{
    return ucfirst(ltrim(strtolower(preg_replace('/(?<!\ )[A-Z]/', ' $0', $value))));
}

/**
 * Get the product image or a backup image
 *
 * @param $item
 * @param $key
 * @param string $backup
 * @return mixed|string
 */
function product_image_or_backup($item, $key, $backup = '/assets/img/products/no-image.jpg')
{
    if (isset_not_empty($item, $key)) {
        return '/assets/img/products/' . $item[$key];
    }

    return $backup;
}

/**
 * Format price in Dutch format (€ 9.999,99)
 * because Dutch is the only right format
 *
 * @param $number
 * @param float $tax_rate
 * @return string
 */
function format_price_with_tax($number, $tax_rate = 0.21)
{
    return number_format(round($number * (1 + $tax_rate), 2), 2, ',', '.');
}

/**
 * Wrap an item in an array
 *
 * @param $item
 * @return array
 */
function array_wrap($item)
{
    if (!is_array($item)) {
        return [$item];
    }

    return $item;
}

/**
 * Convert Fahrenheit to Celsius
 *
 * @param $given_value
 * @return mixed
 */
function fahrenheit_to_celsius($given_value)
{
    $celsius = 5 / 9 * ($given_value - 32);
    return $celsius;
}

/**
 * Shorthand for returning a (twig) response
 *
 * @param string|null $content
 * @param int $status
 * @param array $headers
 * @return \Symfony\Component\HttpFoundation\Response | Response
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 */
function response(string $content = null, int $status = 200, array $headers = []): \Symfony\Component\HttpFoundation\Response|Response
{
    $response = app()->make(Response::class);

    if (is_null($content)) {
        return $response;
    }

    return $response->view($content, $status, $headers);
}

/**
 * Shorthand for accessing cart
 *
 * @return Cart
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 */
function cart(): Cart
{
    return app(Cart::class);
}

/**
 * Shorthand function to contract something from container
 *
 * @param string|null $accessor
 * @return mixed|\Psr\Container\ContainerInterface
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 */
function app(string $accessor = null)
{
    if (is_null($accessor)) {
        return Facade::getAppAccessor();
    }

    return Facade::getAppAccessor()->get($accessor);
}

/**
 * Shorthand function to access request
 *
 * @param string|null $key
 * @return Request
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 */
function request()
{
    return app(Request::class);
}

/**
 * Shorthand function to access session from request
 *
 * @param string|null $key
 * @return mixed
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 */
function session(string $key = null)
{
    if (is_null($key)) {
        return app(Request::class)->getSession();
    }

    return app(Request::class)->get($key);
}

/**
 * Shorthand function to access auth
 *
 * @return Auth
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 */
function auth()
{
    return app(Auth::class);
}

/**
 * Shorthand function to create an URL instance
 *
 * @return Url
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 */
function url(): Url
{
    return app()->make(Url::class);
}

/**
 * Utility function for getting only certain functions out of request parameters
 *
 * @param Request $request
 * @param array $keys
 * @return array
 */
function only(Request $request, array $keys)
{
    $bag = [];

    foreach ($keys as $key) {
        $bag[$key] = $request->get($key);
    }

    return $bag;
}

/**
 * Tap an instance of object, allows interacting with the object and returns the object with changes
 *
 * @param mixed $value
 * @param callable $callback
 * @return mixed
 */
function tap(mixed $value, Closure $callback)
{
    $callback($value);

    return $value;
}

function now($date = null)
{
    if (is_null($date)) {
        $date = Carbon::now();
    }

    return Carbon::make($date);
}
