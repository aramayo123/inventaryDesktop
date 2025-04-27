<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $table = 'products';
    protected $fillable = [
        'codigo',
        'nombre',
        'cantidad_unidades',
        'cantidad_bultos',
        'bultos_min_aviso',
        'cantidad_por_bulto',
        'precio_compra_unitario',
        'precio_compra_bulto',
        'precio_venta_unitario',
    ];
}
