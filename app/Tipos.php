<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipos extends Model
{
    protected $table = "tipo_clase";
    public $timestamps = false;
    protected $fillable = [
        'tipo_clase','descripcion','costo','numero_clases'
    ];
}
