<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FechaClase extends Model
{
    protected $table = "fecha_clase";
    public $timestamps = false;
    protected $fillable = [
        'idclase','fecha'
    ];
}
