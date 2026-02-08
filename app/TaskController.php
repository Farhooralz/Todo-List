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
        if (empty($this->request->session['user_id'])) {
            header("Location: /login");
            exit;
        }

        $userId = (int) $this->request->session['user_id'];
        $task = trim($this->request->body['task'] ?? '');

        if ($task === '') {
            $this->request->session['error'] = 'Task text is required.';
            header("Location: /tasks");
            exit;
        }

        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO tasks (user_id, task) VALUES (:user_id, :task)"
            );
            $stmt->execute([
                ':uesr_id' => $userId,
                ':task' => $task
            ]);
        } catch (PDOException $e) {
            $this->request->session["error"] = "Could not add task.";
            header("Location: /tasks");
            exit;
        }

        $this->request->session['success'] = 'Task added.';
        header("Location: /tasks");
        exit;
    }

    public function update() {
        if (empty($this->request->session["user_id"])) {
            header("Location: /login");
            exit;
        }

        $userId = (int) $this->request->session["user_id"];
        $id = (int) ($this->request->body["id"] ?? 0);
        $task = trim($this->request->body["task"] ?? 0);

        if ($id <= 0 || $task === '') {
            $this->request->session["error"] = "Invalid task data.";
            header("Location: /tasks");
            exit;
        }

        try {
            $stmt = $this->pdo->prepare("
                UPDATE tasks 
                SET task = :task 
                WHERE id = :id AND user_id = :user_id
            ");
            $stmt->execute([
                ':task' => $task,
                ':id' => $id,
                ':user_id' => $userId
            ]);
        } catch (PDOException $e) {
            $this->request->session['error'] = "Could not update task.";
            header("Location: /tasks");
            exit;
        }

        $this->request->session["success"] = "Task updated.";
        header("Location: /tasks");
        exit;
    }

    public function done() {
        if (empty($this->request->session["user_id"])) {
            header("Location: /login");
            exit;
        }

        $userId = (int) $this->request->session['user_id'];
        $id = (int) ($this->request->body['id'] ?? 0);

        if ($id <= 0) {
            $this->request->session['error'] = "Invalid task.";
            header("Location: /tasks");
            exit;
        }

        try {
            $stmt = $this->pdo->prepare("
                UPDATE tasks 
                SET done = 1 
                WHERE id = :id AND user_id = :user_id
            ");
            $stmt->execute([
                ':id' => $id,
                ':user_id' => $userId
            ]);
        } catch (PDOException $e) {
            $this->request->session['error'] = "Could not mark task as done.";
            header("Location: /tasks");
            exit;
        }

        $this->request->session['success'] = 'Task marked as done.';
        header('Location: /tasks');
        exit;
    }

    public function delete() {

    }
}