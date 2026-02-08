<?php

declare(strict_types=1);

session_start();

require __DIR__ . "/../vendor/autoload.php";

use App\AuthController;
use App\Request;
use App\TaskController;

$request = new Request();
$uri = parse_url($request->uri, PHP_URL_PATH);
$method = $request->method ?? 'GET';

$authController = new AuthController();
$taskController = new TaskController();

if ($uri === '/' || $uri === '') {
    require __DIR__ . '/../views/welcome.html';
    exit;
}

if ($uri === '/login' && $method === 'GET') {
    require __DIR__ . '/../views/login.php';
    exit;
}

if ($uri === '/login' && $method === 'POST') {
    $authController->login();
    exit;
}

if ($uri === '/register' && $method === 'GET') {
    require __DIR__ . '/../views/register.php';
    exit;
}

if ($uri === '/register' && $method === 'POST') {
    $authController->register();
    exit;
}

if ($uri === '/logout') {
    $authController->logout();
    exit;
}

if ($uri === '/tasks' && $method === 'GET') {
    if (empty($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    require __DIR__ . '/../views/tasks.php';
    exit;
}

http_response_code(404);
echo '404 Not Found';