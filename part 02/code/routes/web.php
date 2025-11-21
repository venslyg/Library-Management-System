<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowController;

// authentication routes
//main page is login page
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin']); // Alias

// Handle Login/Logout/Register
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


// book listing
Route::resource('books', BookController::class)->only(['index', 'show']);


// librarian management
Route::middleware(['auth', 'librarian'])->group(function () {
    Route::resource('books', BookController::class)->except(['index', 'show']);
});


// Customer routes

Route::middleware('auth')->group(function () {
    Route::get('/my-loans', [BorrowController::class, 'myLoans'])->name('my-loans');
    Route::post('/borrow', [BorrowController::class, 'store'])->name('borrow.store');
    Route::post('/return/{borrow}', [BorrowController::class, 'update'])->name('borrow.update');
});
