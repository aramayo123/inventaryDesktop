<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ventas;
use App\Http\Requests\VentasRequest;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Factura;

class VentasController extends Controller
{
    //
    public function storeVenta(Request $request){
        $productos = $request->ProductosFinales;
        $VentaCompletada = true;
        $errores = [];
        $VentasConcretadas = [];
        $totalVenta = 0;
        $totalCantidades = 0;
        $totalProductos = 0;

        // CONTROL DE STOCK DE UNIDADES Y / O BULTOS
        foreach($productos as $aux){
            $succesProduct = false;
            $CantidadReq = $aux['cantidad'];
            $idProduct = $aux['id'];
            $producto = Product::where('id', $idProduct)->first();
            $total = 0;

            if($producto){
                if(!$producto->cantidad_unidades && !$producto->cantidad_bultos){
                    $errores[] = 'El producto '.$producto->nombre.' no tiene unidades ni cajas.';
                    $VentaCompletada = false;
                    break;
                }
                if($producto->cantidad_unidades >= $CantidadReq){
                    $producto->cantidad_unidades -= $CantidadReq;
                    $succesProduct = true;
                }else{// hay que romper un bulto
                    do{
                        $producto->cantidad_unidades += $producto->cantidad_por_bulto;
                        $producto->cantidad_bultos -= 1;
                    }while($producto->cantidad_unidades < $CantidadReq && $producto->cantidad_bultos > 0);
                    
                    if($producto->cantidad_unidades >= $CantidadReq){
                        $producto->cantidad_unidades -= $CantidadReq;
                        $succesProduct = true;
                    }else{
                        $VentaCompletada = false;
                        $errores[] = 'El producto '.$producto->nombre.' no tiene suficientes unidads / o cajas.';
                        break;
                    }
                }
                $total = $producto->precio_venta_unitario * $CantidadReq;
            }else{
                $errores[] = 'El producto con ID '.$idProduct.' no existe.';
                $VentaCompletada = false;
                break;
            }

            if($succesProduct == true){
                $totalVenta += $total;
                $totalCantidades += $CantidadReq;
                $totalProductos += 1;
                array_push($VentasConcretadas, array("producto" => $producto->getAttributes(), "cantidad_producto" => $CantidadReq, "total_producto" => $total));
            }
        }
        if($VentaCompletada == false){
            return response()->json([
                'error' => 'No se pudo concretar la venta.',
                'errores' => $errores
            ]);
        }

        // generamos una factura
        $factura = new Factura();
        $factura->cliente = "Consumidor final";
        $factura->cantidad_productos = $totalProductos;
        $factura->cantidad_unidades = $totalCantidades;
        $factura->total_venta = $totalVenta;
        $factura->save();

        // GUARDAR VENTA
        foreach($VentasConcretadas as $aux){
            $NewProduct = $aux['producto']; 

            // actualizamos el producto
            $producto = Product::where('id', $NewProduct['id'])->first();
            $producto->cantidad_unidades = $NewProduct['cantidad_unidades'];
            $producto->cantidad_bultos = $NewProduct['cantidad_bultos'];
            $producto->save();

            $cantidades = $aux['cantidad_producto'];
            $bultos = intdiv($cantidades, $NewProduct['cantidad_por_bulto']);
            $cantidades -= ($NewProduct['cantidad_por_bulto'] * $bultos);
      
            // guardamos la venta
            $venta = new Ventas();
            $venta->product_id = $NewProduct['id'];
            $venta->factura_id = $factura->id;
            $venta->cantidad_unidades = $cantidades;
            $venta->cantidad_bultos = $bultos;
            $venta->total_venta = (float)$aux['total_producto'];
            $venta->save();
        }
        return response()->json([
            'success' => 'La venta ha sido concretada con exito!.',
        ]);
        //Log::alert($VentasConcretadas);
        //Log::alert($errores);
    }
}
