<?php

namespace App;

use PDO;
use PDOException;

class TaskController
{

    private PDO $pdo;

    public function __construct() {
        $dbPath = __DIR__ . "/../database/app.db";

        $this->pdo = new PDO("sqlite:" . $dbPath);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec('PRAGMA foreign_keys = ON;');
    }
    public function add() {
        
    }

    public function update() {

    }

    public function done() {

    }

    public function delete() {

    }
}