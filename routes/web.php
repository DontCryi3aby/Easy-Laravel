<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostMetaController;
use App\Http\Controllers\PostTagController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('posts', PostController::class);
    Route::resource('tags', TagController::class);
    Route::resource('posts.categories', PostCategoryController::class);
    Route::resource('posts.comments', PostCommentController::class)->shallow();
    Route::resource('posts.metas', PostMetaController::class);
    Route::resource('posts.tags', PostTagController::class);
});