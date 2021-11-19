<?php

use App\Http\Kernel;
use FastRoute\RouteCollector;
use Symfony\Component\HttpFoundation\Request;
use function FastRoute\simpleDispatcher;

// Define application base path
define("BASE_PATH", dirname(__DIR__));

// Register composer's autoloader
require BASE_PATH . './vendor/autoload.php';

// Run the application
$app = require_once __DIR__ . '/../bootstrap/app.php';

$dispatcher = simpleDispatcher(function (RouteCollector $router) {
    require_once BASE_PATH . './routes/web.php';
});

$kernel = $app->make(Kernel::class);

$kernel->handle(
    $app->get(Request::class), $dispatcher
)->send();