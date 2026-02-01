<?php

declare(strict_types=1);

require (__DIR__) . "../../vendor/autoload.php";

function json_response(array $data, int $status = 200): never {
    http_response_code($status);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($data, JSON_UNESCAPED_SLASHES);
    exit;
}

$method = $_SERVER["REQUEST_METHOD"] ?? "GET";
$uri = $_SERVER["REQUEST_URI"] ?? "/";
$path = parse_url($uri, PHP_URL_PATH) ?: "/";

if (!str_starts_with($path, "/api/")) {
    json_response(["error" => "Not Found"], 404);
}

if ($method === "GET" && $path === "/api/health") {
    json_response(["ok" => true]);
}

json_response(["error" => "Not Found"], 404);