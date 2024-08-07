<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::any('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register']);

Route::get('/', function () {
    return view('welcome');
});
