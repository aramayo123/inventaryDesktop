<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\FacturaController;
use App\Http\Middleware\CheckLicenseValidity;
use App\Services\UpdateChecker;

Auth::routes([
    'register' => false, // Deshabilita el registro
    'reset' => false,    // Opcional: desactiva el reset de contraseña
    'verify' => false,   // Opcional: desactiva verificación de email
]);

Route::middleware([CheckLicenseValidity::class])->group(function () {
    Route::get('/', function () {
        app(UpdateChecker::class)->check();
        return app()->make(App\Http\Controllers\HomeController::class)->index();
        
    })->middleware('auth')->name('home');

    Route::get('/productos/buscar', [ProductController::class, 'BuscarProductos']);
    Route::post('/productos/{id}/actualizar-campo', [ProductController::class, 'actualizarCampo']);
    Route::resource('productos', ProductController::class);
    Route::post('/ventas/registrar-ventas', [VentasController::class, 'storeVenta']);
    Route::post('/facturas/eliminar-factura', [FacturaController::class, 'EliminarFactura']);
    Route::post('/facturas/eliminar-factura', [FacturaController::class, 'EliminarFactura']);
    Route::get('/facturas/todas-las-ventas/{id}', [FacturaController::class, 'VerVentas']);
    Route::get('/facturas/facturas-hoy', [FacturaController::class, 'FacturasHoy']);
    Route::get('/facturas/resumen-por-dias/{dias}', [FacturaController::class, 'resumenPorDias']);
    Route::get('/facturas/top-productos-vendidos/{dias}', [FacturaController::class, 'topProductosVendidosPorDias']);
    Route::get('/facturas/resumen-por-fecha/{fecha}', [FacturaController::class, 'resumenPorFecha']);
    Route::get('/facturas/top-productos-vendidos-por-fecha/{fecha}', [FacturaController::class, 'topProductosVendidosPorFecha']);
});

