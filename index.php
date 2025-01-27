<?php

use App\App;
use App\Config;
use App\Container;
use App\Modules\Controllers\ClientController;
use App\Modules\Controllers\ContactController;
use App\Modules\Controllers\HomeController;
use App\Routes\Router;

require __DIR__ . '/src/vendor/autoload.php';

define('VIEW_PATH', __DIR__ . '/src/views');

// Handle CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$container = new Container();
$router    = new Router($container);



$router
    ->get('/', [HomeController::class, 'index'])
    ->get('/clients', [ClientController::class, 'index'])
    ->get('/clients/list', [ClientController::class, 'list'])
    ->get('/client-contacts', [ClientController::class, 'contacts'])
    ->post('/clients', [ClientController::class, 'store'])
    ->post('/clients/link', [ClientController::class, 'link'])
    ->post('/clients/unlink', [ClientController::class, 'unlink'])
    ->get('/contacts', [ContactController::class, 'index'])
    ->get('/contacts/list', [ContactController::class, 'list'])
    ->get('/contact-clients', [ContactController::class, 'clients'])
    ->post('/contacts', [ContactController::class, 'store'])
    ->post('/contacts/link', [ContactController::class, 'link'])
    ->post('/contacts/unlink', [ContactController::class, 'unlink']);

(new App(
    $container,
    $router,
    ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']],
    new Config($_ENV)
))->run();
