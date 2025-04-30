<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\FacturaController;
use App\Http\Middleware\CheckLicenseValidity;

Auth::routes([
    'register' => false, // Deshabilita el registro
    'reset' => false,    // Opcional: desactiva el reset de contraseña
    'verify' => false,   // Opcional: desactiva verificación de email
]);


Route::middleware([CheckLicenseValidity::class])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/productos/buscar', [ProductController::class, 'BuscarProductos']);
    Route::post('/productos/{id}/actualizar-campo', [ProductController::class, 'actualizarCampo']);
    Route::resource('productos', ProductController::class);
    Route::post('/ventas/registrar-ventas', [VentasController::class, 'storeVenta']);
    Route::post('/facturas/eliminar-factura', [FacturaController::class, 'EliminarFactura']);
    Route::post('/facturas/eliminar-factura', [FacturaController::class, 'EliminarFactura']);
    Route::get('/facturas/todas-las-ventas/{id}', [FacturaController::class, 'VerVentas']);
    Route::get('/facturas/facturas-hoy', [FacturaController::class, 'FacturasHoy']);
});