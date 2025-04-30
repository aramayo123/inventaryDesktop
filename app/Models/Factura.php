<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    //
    protected $table = 'facturas';
    protected $fillable = [
        'cliente',
        'cantidad_productos',
        'cantidad_unidades',
        'total_venta',
    ];
}
