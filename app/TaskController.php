<?php

namespace App;

use PDO;
use PDOException;
use App\Request;
use App\Session;

class TaskController
{

    private PDO $pdo;

    private Request $request;

    public function __construct() {
        $dbPath = __DIR__ . "/../database/app.db";

        $this->pdo = new PDO("sqlite:" . $dbPath);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec('PRAGMA foreign_keys = ON;');

        $this->request = new Request();
    }

    public function list() {
        if (empty(Session::get('user_id'))) {
            return [];
        }

        $userId = (int) Session::get('user_id');

        $stmt = $this->pdo->prepare(
            'SELECT id, task, done, created_at 
             FROM tasks 
             WHERE user_id = :user_id 
             ORDER BY created_at DESC, id DESC'
        );
        $stmt->execute([':user_id' => $userId]);

        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return require_once __DIR__ . "/../views/tasks.php";
    }

    public function add() {
        if (empty(Session::get('user_id'))) {
            header("Location: /login");
            exit;
        }

        $userId = (int) Session::get('user_id');
        $task = trim($this->request->body['task'] ?? '');

        if ($task === '') {
            Session::set('error', 'Task text is required.');
            header("Location: /tasks");
            exit;
        }

        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO tasks (user_id, task) VALUES (:user_id, :task)"
            );
            $stmt->execute([
                ':user_id' => $userId,
                ':task' => $task
            ]);
        } catch (PDOException $e) {
            Session::set('error', "Could not add task.");
            header("Location: /tasks");
            exit;
        }

        Session::set('success', 'Task added.');
        header("Location: /tasks");
        exit;
    }

    public function update() {
        if (empty(Session::get('user_id'))) {
            header("Location: /login");
            exit;
        }

        $userId = (int) Session::get('user_id');
        $id = (int) ($this->request->body["id"] ?? 0);
        $task = trim($this->request->body["task"] ?? 0);

        if ($id <= 0 || $task === '') {
            Session::set("error", "Invalid task data.");
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
            Session::set('error', "Could not update task.");
            header("Location: /tasks");
            exit;
        }

        Session::set('success', "Task updated.");
        header("Location: /tasks");
        exit;
    }

    public function done() {
        if (empty(Session::get('user_id'))) {
            header("Location: /login");
            exit;
        }

        $userId = (int) Session::get('user_id');
        $id = (int) ($this->request->body['id'] ?? 0);

        if ($id <= 0) {
            Session::set('error', "Invalid task.");
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
            Session::set('error', "Could not mark task as done.");
            header("Location: /tasks");
            exit;
        }

        Session::set('success', 'Task marked as done.');
        header('Location: /tasks');
        exit;
    }

    public function delete() {
        if (empty(Session::get('user_id'))) {
            header("Location: /login");
            exit;
        }

        $userId = (int) Session::get('user_id');
        $id = (int) ($this->request->body['id'] ?? 0);

        if ($id <= 0) {
            Session::set('error', "Invalid task.");
            header("Location: /tasks");
            exit;
        }

        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM tasks 
                WHERE id = :id AND user_id = :user_id
            ");
            $stmt->execute([
                ':id' => $id,
                ':user_id' => $userId
            ]);
        } catch (PDOException $e) {
            Session::set('error', "Could not delete task.");
            header("Location: /tasks");
            exit;
        }

        Session::set('success', "Task deleted.");
        header("Location: /tasks");
        exit;
    }
}