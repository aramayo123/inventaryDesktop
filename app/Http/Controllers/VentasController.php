<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ventas;
use App\Http\Requests\VentasRequest;
use Illuminate\Support\Facades\Log;
use App\Models\Product;

class VentasController extends Controller
{
    //
    public function storeVenta(Request $request){
        $productos = $request->ProductosFinales;
        $VentaCompletada = true;
        $errores = [];
        $VentasConcretadas = [];
        $totalVenta = 0;

        // CONTROL DE STOCK DE UNIDADES Y / O BULTOS
        foreach($productos as $aux){
            $succesProduct = false;
            $CantidadReq = $aux['cantidad'];
            $idProduct = $aux['id'];
            $producto = Product::where('id', $idProduct)->first();

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
                $totalVenta = $producto->precio_venta_unitario * $CantidadReq;
            }else{
                $errores[] = 'El producto con ID '.$idProduct.' no existe.';
                $VentaCompletada = false;
                break;
            }

            if($succesProduct == true){
                array_push($VentasConcretadas, array("producto" => $producto->getAttributes(), "cantidades" => $CantidadReq, "total_venta" => $totalVenta));
            }
        }
        if($VentaCompletada == false){
            return response()->json([
                'error' => 'No se pudo concretar la venta.',
                'errores' => $errores
            ]);
        }

        // GUARDAR VENTA
        foreach($VentasConcretadas as $aux){
            $NewProduct = $aux['producto']; 

            //Log::alert($NewProduct['cantidad_unidades']);
            // actualizamos el producto
           
            $producto = Product::where('id', $NewProduct['id'])->first();
            $producto->cantidad_unidades = $NewProduct['cantidad_unidades'];
            $producto->cantidad_bultos = $NewProduct['cantidad_bultos'];
            $producto->save();

            $cantidades = $aux['cantidades'];
            $bultos = intdiv($cantidades, $NewProduct['cantidad_por_bulto']);
            $cantidades -= ($NewProduct['cantidad_por_bulto'] * $bultos);
            //Log::alert($cantidades);
            Log::alert($aux['total_venta']);
            
            // guardamos la venta
            $venta = new Ventas();
            $venta->cliente = "Consumidor final";
            $venta->product_id = $NewProduct['id'];
            $venta->cantidad_unidades = $cantidades;
            $venta->cantidad_bultos = $bultos;
            $venta->total_venta = (float)$aux['total_venta'];
            $venta->save();
        }
        return response()->json([
            'success' => 'La venta ha sido concretada con exito!.',
        ]);
        //Log::alert($VentasConcretadas);
        //Log::alert($errores);
    }
}
