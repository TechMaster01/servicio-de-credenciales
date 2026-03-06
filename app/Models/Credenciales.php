<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credenciales extends Model
{
    protected $table = 'credenciales';
    protected $primaryKey = 'clave';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'clave',
        'des_dns',
        'des_usuario',
        'des_db',
        'des_password',
    ];
}
