<?php

namespace App;


class Request
{
    public ?string $uri;
    public ?string $method;
    public ?array $body;
    public function __construct() {
        $this->uri = $_SERVER["REQUEST_URI"];
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->body = $_POST;
    }

}