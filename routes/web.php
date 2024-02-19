<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SesiController;
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

Route::middleware(['guest'])->group(function () {
    Route::get('/', [SesiController::class, 'index'])->name('login');
    Route::post('/', [SesiController::class, 'login']);

    Route::get('/register', [SesiController::class, 'registerView'])->name('register');
    Route::post('/register', [SesiController::class, 'register']);

    Route::get('/login/google', [SesiController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/google/callback', [SesiController::class, 'handleGoogleCallback'])->name('login.google.callback');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [AdminController::class, 'index'])->name('home.landing');
    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/admin/user', [AdminController::class, 'user'])->middleware('userAkses:user');
    Route::get('/admin/admin', [AdminController::class, 'admin'])->middleware('userAkses:admin');
    Route::get('/admin/operator', [AdminController::class, 'operator'])->middleware('userAkses:operator');
    Route::get('/logout', [SesiController::class, 'logout']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'admin'])->middleware('userAkses:admin')->name('admin');
});
