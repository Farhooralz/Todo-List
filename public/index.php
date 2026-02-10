<?php

session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use Route\Route;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

require_once __DIR__ . '/../route/router.php';
require_once __DIR__ . '/../route/web.php';

Route::dispatch($method, $uri);
