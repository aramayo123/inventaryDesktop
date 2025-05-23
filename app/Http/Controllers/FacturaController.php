<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Factura;
use App\Models\Ventas;
use App\Models\Product;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
       
    }
    public function FacturasHoy(){
        // Obtener la fecha actual
        $fechaActual = now()->format('Y-m-d');

        // Obtener todas las facturas de hoy
        $facturasHoy = Factura::whereDate('created_at', $fechaActual)->get();
        //Log::debug($facturasHoy);

        $ventasTotales = 0;
        $facturasFormateadas = [];
        // Formatear los datos de las facturas
        foreach($facturasHoy as $factura){
            $ventas = Ventas::where('factura_id', $factura->id)->get();
            $gananciaVenta = 0;
            foreach ($ventas as $venta) {
                $producto = Product::findOrFail($venta->product_id);
                $precioVenta = $producto->precio_venta_unitario;
                $precioCompra = $producto->precio_compra_unitario;

                $productosTotales = $venta->cantidad_unidades + ($venta->cantidad_bultos * $producto->cantidad_por_bulto);
                $ventasTotales += ($productosTotales * $precioVenta);
                $gananciaVenta += ($productosTotales * $precioVenta) - ($productosTotales * $precioCompra);
            }
            array_push($facturasFormateadas, array(
                'factura_id' => $factura->id,
                'venta_total' => $ventasTotales,
                'ganancia' => $gananciaVenta
            ));
        }

        return response()->json($facturasFormateadas);
    }
    public function EliminarFactura(Request $request){
        //
        //Log::alert();
        $id = $request->input('factura_id');
        if(!$id){
            return response()->json([
                'error' => 'No se pudo completar la eliminacion de la factura.'
            ]);
        }

        // Validar el ID de la factura
        Factura::destroy($id);
        return response()->json([
            'success' => 'La factura ha sido eliminada con exito.'
        ]);
    }
    public function VerVentas($id){
        // Paso 1: Obtener todas las ventas de la factura
        $ventas = Ventas::where('factura_id', $id)->get();

        // Paso 2: Armar una colección con los datos necesarios
        $ventasFormateadas = $ventas->map(function ($venta) {
            $producto = Product::findOrFail($venta->product_id);

            return [
                'producto_nombre' => $producto->nombre,
                'cantidad_unidades' => $venta->cantidad_unidades,
                'cantidad_bultos' => $venta->cantidad_bultos,
                'cantidad_por_bulto' => $producto->cantidad_por_bulto,
                'total_venta' => $venta->total_venta,
                'fecha' => $venta->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json($ventasFormateadas);
    }
    public function resumenPorDias($dias) {
        $fechaInicio = now()->subDays($dias - 1)->startOfDay();
        $fechaFin = now()->endOfDay();
        $facturas = Factura::whereBetween('created_at', [$fechaInicio, $fechaFin])->get();

        $ventasTotales = 0;
        $gananciaTotal = 0;
        $productosVendidos = 0;

        foreach($facturas as $factura){
            $ventas = Ventas::where('factura_id', $factura->id)->get();
            foreach ($ventas as $venta) {
                $producto = Product::find($venta->product_id);
                if (!$producto) continue;
                $precioVenta = $producto->precio_venta_unitario;
                $precioCompra = $producto->precio_compra_unitario;
                $cantidadTotal = $venta->cantidad_unidades + ($venta->cantidad_bultos * $producto->cantidad_por_bulto);
                $ventasTotales += ($cantidadTotal * $precioVenta);
                $gananciaTotal += ($cantidadTotal * $precioVenta) - ($cantidadTotal * $precioCompra);
                $productosVendidos += $cantidadTotal;
            }
        }
        return response()->json([
            'ventas_totales' => $ventasTotales,
            'ganancia_total' => $gananciaTotal,
            'productos_vendidos' => $productosVendidos
        ]);
    }
    public function topProductosVendidosPorDias($dias) {
        $fechaInicio = now()->subDays($dias - 1)->startOfDay();
        $fechaFin = now()->endOfDay();
        $facturas = Factura::whereBetween('created_at', [$fechaInicio, $fechaFin])->get();

        $productos = [];
        foreach($facturas as $factura){
            $ventas = Ventas::where('factura_id', $factura->id)->get();
            foreach ($ventas as $venta) {
                $producto = Product::find($venta->product_id);
                if (!$producto) continue;
                $key = $producto->id;
                $cantidadTotal = $venta->cantidad_unidades + ($venta->cantidad_bultos * $producto->cantidad_por_bulto);
                $totalRecaudado = $cantidadTotal * $producto->precio_venta_unitario;
                if (!isset($productos[$key])) {
                    $productos[$key] = [
                        'nombre' => $producto->nombre,
                        'codigo' => $producto->codigo,
                        'cantidad_vendida' => 0,
                        'total_recaudado' => 0
                    ];
                }
                $productos[$key]['cantidad_vendida'] += $cantidadTotal;
                $productos[$key]['total_recaudado'] += $totalRecaudado;
            }
        }
        // Ordenar por cantidad_vendida desc
        usort($productos, function($a, $b) {
            return $b['cantidad_vendida'] <=> $a['cantidad_vendida'];
        });
        return response()->json(array_values($productos));
    }
    public function resumenPorFecha($fecha) {
        $fechaInicio = \Carbon\Carbon::parse($fecha)->startOfDay();
        $fechaFin = \Carbon\Carbon::parse($fecha)->endOfDay();
        $facturas = Factura::whereBetween('created_at', [$fechaInicio, $fechaFin])->get();

        $ventasTotales = 0;
        $gananciaTotal = 0;
        $productosVendidos = 0;

        foreach($facturas as $factura){
            $ventas = Ventas::where('factura_id', $factura->id)->get();
            foreach ($ventas as $venta) {
                $producto = Product::find($venta->product_id);
                if (!$producto) continue;
                $precioVenta = $producto->precio_venta_unitario;
                $precioCompra = $producto->precio_compra_unitario;
                $cantidadTotal = $venta->cantidad_unidades + ($venta->cantidad_bultos * $producto->cantidad_por_bulto);
                $ventasTotales += ($cantidadTotal * $precioVenta);
                $gananciaTotal += ($cantidadTotal * $precioVenta) - ($cantidadTotal * $precioCompra);
                $productosVendidos += $cantidadTotal;
            }
        }
        return response()->json([
            'ventas_totales' => $ventasTotales,
            'ganancia_total' => $gananciaTotal,
            'productos_vendidos' => $productosVendidos
        ]);
    }
    public function topProductosVendidosPorFecha($fecha) {
        $fechaInicio = \Carbon\Carbon::parse($fecha)->startOfDay();
        $fechaFin = \Carbon\Carbon::parse($fecha)->endOfDay();
        $facturas = Factura::whereBetween('created_at', [$fechaInicio, $fechaFin])->get();

        $productos = [];
        foreach($facturas as $factura){
            $ventas = Ventas::where('factura_id', $factura->id)->get();
            foreach ($ventas as $venta) {
                $producto = Product::find($venta->product_id);
                if (!$producto) continue;
                $key = $producto->id;
                $cantidadTotal = $venta->cantidad_unidades + ($venta->cantidad_bultos * $producto->cantidad_por_bulto);
                $totalRecaudado = $cantidadTotal * $producto->precio_venta_unitario;
                if (!isset($productos[$key])) {
                    $productos[$key] = [
                        'nombre' => $producto->nombre,
                        'codigo' => $producto->codigo,
                        'cantidad_vendida' => 0,
                        'total_recaudado' => 0
                    ];
                }
                $productos[$key]['cantidad_vendida'] += $cantidadTotal;
                $productos[$key]['total_recaudado'] += $totalRecaudado;
            }
        }
        // Ordenar por cantidad_vendida desc
        usort($productos, function($a, $b) {
            return $b['cantidad_vendida'] <=> $a['cantidad_vendida'];
        });
        return response()->json(array_values($productos));
    }
}
