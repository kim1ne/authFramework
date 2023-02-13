<?php

use \Bootstrap\Route;

Route::get('/', [\App\Controllers\User\IndexController::class, 'index']);
Route::get('/auth', [\App\Controllers\User\IndexController::class, 'auth']);
Route::post('/register', [\App\Controllers\User\MainController::class, 'register']);
Route::post('/user/auth', [\App\Controllers\User\MainController::class, 'auth']);
Route::get('/logout', [\App\Controllers\User\MainController::class, 'logout']);