<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VentasController;

Auth::routes([
    'register' => false, // Deshabilita el registro
    'reset' => false,    // Opcional: desactiva el reset de contraseña
    'verify' => false,   // Opcional: desactiva verificación de email
]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/productos/buscar', [ProductController::class, 'BuscarProductos']);
Route::post('/productos/{id}/actualizar-campo', [ProductController::class, 'actualizarCampo']);
Route::resource('productos', ProductController::class);
Route::post('/ventas/registrar-ventas', [VentasController::class, 'storeVenta']);