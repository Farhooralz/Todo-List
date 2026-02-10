<?php

namespace App;

class IndexController
{
    public function welcome() {
        return require_once __DIR__ . "/../views/welcome.html";
    }
}