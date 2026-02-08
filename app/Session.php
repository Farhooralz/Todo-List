<?php

namespace App;

class Session
{
    public static function get(string $key) {
        return $_SESSION[$key];
    }

    public static function set(string $key, mixed $value) {
        return $_SESSION[$key] = $value;
    }
}