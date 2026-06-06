<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Helpers\Env;
use App\Services\Router;
use App\Helpers\Session;

Env::load(__DIR__ . '/../.env');
Session::start();

$router = new Router();
require_once __DIR__ . '/../routes/web.php';

$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$router->dispatch($uri, $method);
