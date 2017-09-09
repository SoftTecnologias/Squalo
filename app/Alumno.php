<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $table = "alumnos";
    public $timestamps = false;
    protected $fillable = [
        'nombre','ape_paterno','ape_materno','padreid','fecha_nac','adeudo','asignado','activo'
    ];
}
