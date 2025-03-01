<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Route::get('/', function () {
//     return view('welcome');
// });


// Show Forms
Route::get('/register', [AuthController::class, 'getRegister']);
Route::get('/login', [AuthController::class, 'getLogin']);
Route::get('/', [AuthController::class, 'getLogin']);
Route::get('/logout', [AuthController::class, 'logout']);

// Handle Form Submissions
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/dashboard', [AuthController::class, 'getDashboard']);
