<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AsistenciaAlumno extends Model
{
    protected $table = "grupo_alumnos";
    public $timestamps = false;
    protected $fillable = [
        'asistencia'
    ];
}
