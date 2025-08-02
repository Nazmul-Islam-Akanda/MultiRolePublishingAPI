<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ArticleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:3,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'users']);
    Route::get('/roles', [UserController::class, 'roles']);
    Route::post('/users/{id}/assign-role', [UserController::class, 'assignRole']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/articles', [ArticleController::class, 'articles']);
    Route::get('/articles/mine', [ArticleController::class, 'mine']);
    Route::post('/articles', [ArticleController::class, 'store']);
    Route::put('/articles/{id}', [ArticleController::class, 'update']);
    Route::delete('/articles/{id}', [ArticleController::class, 'delete']);
    Route::patch('/articles/{id}/publish', [ArticleController::class, 'publish']);
});
