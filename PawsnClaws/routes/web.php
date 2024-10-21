<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Server;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/server/users', UserController::class, 'users');
Route::get('/server/users/create', [UserController::class, 'create'])->name('users.create');