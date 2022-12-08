<?php

use App\Http\Controllers\KategoriPostController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('layouts.master');
});
Route::prefix('kategori')->group(function(){
    Route::get('/', [KategoriPostController::class, 'index'])->name('kategori.index');
    Route::post('/', [KategoriPostController::class, 'save'])->name('kategori.save');
    Route::get('/{kategori:id_kategori}', [KategoriPostController::class, 'show'])->name('kategori.show');
    Route::post('/{kategori:id_kategori}', [KategoriPostController::class, 'destroy'])->name('kategori.delete');
});

Route::prefix('post')->group(function(){
    Route::get('/', [PostController::class, 'index'])->name('post.index');
    Route::post('/', [PostController::class, 'save'])->name('post.save');
    Route::get('/{post:id_post}', [PostController::class, 'show'])->name('post.show');
    Route::post('/{post:id_post}', [PostController::class, 'destroy'])->name('post.delete');
});