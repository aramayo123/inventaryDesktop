<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    //
    protected $table = 'ventas';
    protected $fillable = [
        'cliente',
        'product_id',
        'cantidad_unidades',
        'cantidad_bultos',
        'total_venta',
    ];
}