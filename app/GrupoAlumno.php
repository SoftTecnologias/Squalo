<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrupoAlumno extends Model
{
    protected $table = "grupo_alumnos";
    public $timestamps = false;
    protected $fillable = [
        'idAlumno','idGrupo','asistencia'
    ];
}
