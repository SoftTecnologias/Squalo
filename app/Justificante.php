<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Justificante extends Model
{
    protected $table = "justificacion";
    public $timestamps = false;
    protected $fillable = [
        'idAlumno','fecha','motivo'
    ];
}
