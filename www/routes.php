<?php

use \Bootstrap\Route;

Route::get('/', [\App\Http\Controllers\User\IndexController::class, 'index'])->name('index')->middleware('auth');
Route::get('/auth', [\App\Http\Controllers\User\IndexController::class, 'auth'])->name('login');
Route::post('/register', [\App\Http\Controllers\User\MainController::class, 'register']);
Route::post('/user/auth', [\App\Http\Controllers\User\MainController::class, 'auth']);
Route::get('/logout', [\App\Http\Controllers\User\MainController::class, 'logout'])->name('logout');


Route::get('/posts', [\App\Http\Controllers\User\MainController::class, 'posts'])->name('posts');