<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Maestro extends Model
{
    protected $table = "maestros";
    public $timestamps = false;
    protected $fillable = [
        'nombre','ape_paterno','ape_materno','colonia','calle','numero','tel_fijo',
        'tel_celular','fecha_nac','email','RFC','Trabajo'
    ];


}
