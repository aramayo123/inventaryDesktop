<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Auth::routes([
    'register' => false, // Deshabilita el registro
    'reset' => false,    // Opcional: desactiva el reset de contraseña
    'verify' => false,   // Opcional: desactiva verificación de email
]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('productos', ProductController::class);