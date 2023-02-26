<?php

use \Bootstrap\Route;

Route::post('/users', [\App\Http\Controllers\User\CreateController::class, 'create']);
Route::post('/auth', \App\Http\Controllers\User\AuthController::class);



Route::get('/', \App\Http\Controllers\User\MainController::class)->middleware('auth');

Route::get('/category', [\App\Http\Controllers\Category\MainController::class, 'categories'])->middleware('auth');
Route::post('/category', [\App\Http\Controllers\Category\MainController::class, 'create'])->middleware('auth');
Route::get('/category/(\d+)', \App\Http\Controllers\Category\MainController::class)->middleware('auth');
Route::put('/category/(\d+)', [\App\Http\Controllers\Category\MainController::class, 'update'])->middleware('auth');
Route::delete('/category/(\d+)', [\App\Http\Controllers\Category\MainController::class, 'delete'])->middleware('auth');

Route::get('/users', \App\Http\Controllers\User\CreateController::class)->middleware('auth');
Route::delete('/users', [\App\Http\Controllers\User\MainController::class, 'delete'])->middleware('auth');

Route::put('/tasks/update/status/(\d+)', \App\Http\Controllers\Tasks\MainController::class)->middleware('auth');
Route::get('/category/(\d+)/task/(\d+)', [\App\Http\Controllers\Tasks\MainController::class, 'index'])->middleware('auth');
Route::post('/task', [\App\Http\Controllers\Tasks\MainController::class, 'create'])->middleware('auth');
Route::put('/task', [\App\Http\Controllers\Tasks\MainController::class, 'update'])->middleware('auth');
Route::delete('/task', [\App\Http\Controllers\Tasks\MainController::class, 'delete'])->middleware('auth');

