<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AsistenciaMaestro extends Model
{
    protected $table = "asistencia_maestros";
    public $timestamps = false;
    protected $fillable = [
        'asistencia','remplazo','fecha'
    ];
}
