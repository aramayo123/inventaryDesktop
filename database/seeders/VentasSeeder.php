<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Factura;
use App\Models\Ventas;
use Carbon\Carbon;

class VentasSeeder extends Seeder
{
    public function run(): void
    {
        $productos = Product::all();
        $dias = 30;
        for ($i = $dias - 1; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i);
            $facturasPorDia = rand(2, 5);
            for ($j = 0; $j < $facturasPorDia; $j++) {
                $productosFactura = $productos->random(rand(1, 4));
                $totalVenta = 0;
                $cantidadUnidades = 0;
                $cantidadProductos = 0;
                $factura = Factura::create([
                    'cliente' => 'Consumidor final',
                    'cantidad_productos' => 0, // se actualiza luego
                    'cantidad_unidades' => 0, // se actualiza luego
                    'total_venta' => 0, // se actualiza luego
                    'created_at' => $fecha,
                    'updated_at' => $fecha,
                ]);
                foreach ($productosFactura as $producto) {
                    $cantidad = rand(1, 10);
                    $bultos = rand(0, 2);
                    $cantidadTotal = $cantidad + ($bultos * $producto->cantidad_por_bulto);
                    $total = $cantidadTotal * $producto->precio_venta_unitario;
                    Ventas::create([
                        'product_id' => $producto->id,
                        'factura_id' => $factura->id,
                        'cantidad_unidades' => $cantidad,
                        'cantidad_bultos' => $bultos,
                        'total_venta' => $total,
                        'created_at' => $fecha,
                        'updated_at' => $fecha,
                    ]);
                    $totalVenta += $total;
                    $cantidadUnidades += $cantidadTotal;
                    $cantidadProductos++;
                }
                $factura->cantidad_productos = $cantidadProductos;
                $factura->cantidad_unidades = $cantidadUnidades;
                $factura->total_venta = $totalVenta;
                $factura->save();
            }
        }
    }
} 