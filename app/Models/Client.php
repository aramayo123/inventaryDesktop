<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
    //
    protected $table = 'clients';
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'licencia_expires_at',
        'secret_hash',
        'firma',
        'last_used_at',
        'ip_sesion',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'licencia_expires_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];
}
