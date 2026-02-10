<?php

namespace Route;

use App\Request;
use App\Session;
use Exception;

class Route
{
    protected static $routes = ['GET' => [], 'POST' => []];

    public static function get($path, $action) {
        static::$routes['GET'][$path] = $action;
    }
    
    public static function post($path, $action) {
        static::$routes['POST'][$path] = $action;
    }

    public static function dispatch($method, $uri) {
        $method = strtoupper($method);
        $uri = ltrim($uri, "/");
        
        if (!isset(static::$routes[$method][$uri])) {
            http_response_code(404);
            die("404 | Route not found");
        }

        $handler = static::$routes[$method][$uri];
        $controller = $handler[0];
        $method = $handler[1];
        
        try{
            $controller = new $controller();
            $controller->$method();
        } catch(Exception $e){
            http_response_code(500);
            die("500 | Server error");
        }
    }
}