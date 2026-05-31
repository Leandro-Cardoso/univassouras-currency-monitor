<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CurrencyController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Proteção da Home
Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});

// Autenticação
Route::get('/login', function () { return view('login'); })->name('login');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

// Área Logada do Monitor
Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', [CurrencyController::class, 'index']);
    Route::post('/dashboard/search', [CurrencyController::class, 'consultar']);
});
