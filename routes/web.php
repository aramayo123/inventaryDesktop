<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\FacturaController;
use App\Http\Middleware\CheckLicenseValidity;
use App\Services\UpdateChecker;
use Illuminate\Support\Facades\Log;
use App\Jobs\CheckUpdatesJob;
use App\Http\Controllers\HomeController;

Auth::routes([
    'register' => false, // Deshabilita el registro
    'reset' => false,    // Opcional: desactiva el reset de contraseña
    'verify' => false,   // Opcional: desactiva verificación de email
]);

Route::middleware([CheckLicenseValidity::class])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->middleware('auth')->name('home');
    Route::get('/productos/buscar', [ProductController::class, 'BuscarProductos']);
    Route::post('/productos/{id}/actualizar-campo', [ProductController::class, 'actualizarCampo']);
    Route::resource('productos', ProductController::class);
    Route::post('/ventas/registrar-ventas', [VentasController::class, 'storeVenta']);
    Route::post('/facturas/eliminar-factura', [FacturaController::class, 'EliminarFactura']);
    Route::get('/facturas/todas-las-ventas/{id}', [FacturaController::class, 'VerVentas']);
    Route::get('/facturas/facturas-hoy', [FacturaController::class, 'FacturasHoy']);
    Route::get('/facturas/resumen-por-dias/{dias}', [FacturaController::class, 'resumenPorDias']);
    Route::get('/facturas/top-productos-vendidos/{dias}', [FacturaController::class, 'topProductosVendidosPorDias']);
    Route::get('/facturas/resumen-por-fecha/{fecha}', [FacturaController::class, 'resumenPorFecha']);
    Route::get('/facturas/top-productos-vendidos-por-fecha/{fecha}', [FacturaController::class, 'topProductosVendidosPorFecha']);
    Route::get('/check-updates', function(UpdateChecker $updateChecker) {
        $file = storage_path('app/update_progress.json');

        // Al iniciar, lo reseteamos/borramos
        if (File::exists($file)) {
            File::delete($file);
        }
        CheckUpdatesJob::dispatch(); // dispara el job en segundo plano
        return response()->json(['status' => 'iniciado']);
    });
    
    Route::get('/update-progress', function () {
        //Log::info("llega a ruta /update-progress en rotutes.php");
        $progress = \App\Services\UpdateChecker::getProgress();
        return response()->json($progress);
    });
});

