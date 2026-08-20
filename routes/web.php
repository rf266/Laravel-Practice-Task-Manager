<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;

Route::get('/register', [AuthController::class, 'showRegister' ])->name('register');
Route::post('/register', [Authcontroller::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth.custom')->group(function() {
    Route::get('/', function () {
        return redirect()->route('tasks.index');

    //these are protected as they can only be accessed when logged in
    Route::resource('tasks', TaskController::class);
    });
});
