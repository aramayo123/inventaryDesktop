<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    //
    protected $table = 'ventas';
    protected $fillable = [
        'product_id',
        'factura_id',
        'cantidad_unidades',
        'cantidad_bultos',
        'total_venta',
    ];
}