<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pagos extends Model
{
    protected $table = "pagos";
    public $timestamps = false;
    protected $fillable = [
        'idAlumno','abono','cancel','fecha'
    ];
}
