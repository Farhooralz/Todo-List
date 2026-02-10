<?php

namespace App;

use PDO;
use PDOException;
use App\Session;
use App\Request;

class AuthController
{
    private PDO $pdo;

    private Request $request;

    public function __construct() {
        $dbPath = __DIR__ . "/../database/app.db";

        $this->request = new Request();
        $this->pdo = new PDO("sqlite:" . $dbPath);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec('PRAGMA foreign_keys = ON;');
    }

    public function loginForm(){
        return require_once __DIR__ . "/../views/login.php";
    }

    public function login(): void {
        $username = trim($this->request->body["username"] ?? "");
        $password = $this->request->body["password"] ?? "";

        if ($username === "" || $password === "") {
            Session::set("error", "Username and Password are required");
            header("Location: /login");
            exit;
        }

        $stmt = $this->pdo->prepare("SELECT id, username, password_hash FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([":username" => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            Session::set("error", "Invalid credentials.");
            header("Location: /login");
            exit;
        }

        if (!password_verify($password, $user["password_hash"])) {
            Session::set("error", "Invalid credentials.");
            header("Location: /login");
            exit;
        }

        Session::set('user_id', $user['id']);
        Session::set('username', $user['username']);

        header("Location: /tasks");
        exit;
    }

    public function registerForm() {
        return require_once __DIr__ . "/../views/register.php";
    }

    public function register(): void
    {
        $username = trim($this->request->body['username'] ?? '');
        $password = $this->request->body['password'] ?? '';
        $passwordConfirmation = $this->request->body['password_confirmation'] ?? '';

        if ($username === '' || $password === '' || $passwordConfirmation === '') {
            Session::set("error", "All fields are required.");
            header('Location: /register');
            exit;
        }

        if ($password !== $passwordConfirmation) {
            Session::set("error", "Passwords do not match.");
            header('Location: /register');
            exit;
        }

        $stmt = $this->pdo->prepare('SELECT id FROM users WHERE username = :username LIMIT 1');
        $stmt->execute([':username' => $username]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            Session::set("error", "Username is already taken.");
            header('Location: /register');
            exit;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)'
            );
            $stmt->execute([
                ':username' => $username,
                ':password_hash' => $hash,
            ]);
        } catch (PDOException $e) {
            Session::set("error", 'Registration failed: ' . $e->getMessage());
            header('Location: /register');
            exit;
        }

        Session::set("success", "Account created. Please log in.");
        header('Location: /login');
        exit;
    }

    public function logout(): void {
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                "",
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
        header("Location: /");
        exit;
    }
}