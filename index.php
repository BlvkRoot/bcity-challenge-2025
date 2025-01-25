<?php

use App\App;
use App\Config;
use App\Container;
use App\Modules\Controllers\HomeController;
use App\Routes\Router;

require __DIR__ . '/src/vendor/autoload.php';

define('VIEW_PATH', __DIR__ . '/src/views');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$container = new Container();
$router    = new Router($container);

$router
    ->get('/', [HomeController::class, 'index']);

(new App(
    $container,
    $router,
    ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']],
    new Config($_ENV)
))->run();
