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
        'descripcion',
        'stock',
        'stock_minimo',
        'precio_compra',
        'precio_venta',
    ];
}
