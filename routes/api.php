<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AppController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UploadController;
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

Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
    return $request->user();
});

Route::get('/ping', [AppController::class, 'ping']);

Route::get('/posts', [PostController::class, 'index']);


Route::get('/posts/{id}', [PostController::class, 'read']);



Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'create']);
Route::patch('/categories', [CategoryController::class, 'update']);
Route::delete('/categories', [CategoryController::class, 'destroy']);

Route::post('/sign-up', [AuthController::class, 'signUp']);
Route::post('/sign-in', [AuthController::class, 'signIn']);


//로그인 후 사용 가능
Route::middleware('auth:sanctum')->group( function () {
    Route::post('/posts', [PostController::class, 'create']);
    Route::patch('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);

    Route::post('posts/{post_id}/comments', [CommentController::class, 'create']);
    Route::delete('posts/{post_id}/comments/{id}', [CommentController::class, 'destroy']);
    Route::post('/uploads', [UploadController::class, 'upload']);

});
