<?php

namespace Route;

use App\AuthController;
use App\TaskController;
use App\IndexController;

Route::get('/', [IndexController::class, "welcome"]);
Route::get('login', [AuthController::class, 'loginForm']);
Route::post('login', [AuthController::class, 'login']);

Route::get('register', [AuthController::class, "registerForm"]);
Route::post('register', [AuthController::class, "register"]);

Route::get('tasks', [TaskController::class, "list"]);
Route::post('tasks', [TaskController::class, "add"]);
Route::post('tasks/update', [TaskController::class, "update"]);
Route::post('tasks/done', [TaskController::class, "done"]);
Route::post('tasks/delete', [TaskController::class, "delete"]);

Route::get('logout', [AuthController::class, "logout"]);
