<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CommentController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
// Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:api')->group(function () {

    Route::apiResource('posts', PostController::class);
    Route::post('/posts/{id}/update', [PostController::class, 'customUpdate']);

    Route::apiResource('categories', CategoryController::class);
    Route::post('/categories/{id}/update', [CategoryController::class, 'customUpdate']);

    Route::apiResource('tags', TagController::class);
    Route::post('/tags/{id}/update', [TagController::class, 'customUpdate']);

    Route::apiResource('comments', CommentController::class);
    Route::post('/comments/{id}/update', [CommentController::class, 'customUpdate']);

});