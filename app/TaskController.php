<?php

namespace App;

use PDO;
use App\Request;
use PDOException;

class TaskController
{

    private PDO $pdo;

    private Request $request;

    public function __construct() {
        $dbPath = __DIR__ . "/../database/app.db";

        $this->pdo = new PDO("sqlite:" . $dbPath);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec('PRAGMA foreign_keys = ON;');
    }

    public function list(): array {
        if (empty($this->request->session['user_id'])) {
            return [];
        }

        $userId = (int) $this->request->session['user_id'];

        $stmt = $this->pdo->prepare(
            'SELECT id, task, done, created_at 
             FROM tasks 
             WHERE user_id = :user_id 
             ORDER BY created_at DESC, id DESC'
        );
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
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