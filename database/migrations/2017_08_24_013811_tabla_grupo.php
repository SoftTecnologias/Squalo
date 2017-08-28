<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class TablaGrupo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('grupo',function (Blueprint $tabla){
           $tabla->increments('id');
           $tabla->integer('idalumno');
           $tabla->boolean('asistencia_alumno')->nullable();
           $tabla->integer('id_asis_maestro')->nullable();
           $tabla->integer('idfecha');

           $tabla->foreign('idalumno')->references('id')->on('alumnos');
           $tabla->foreign('id_asis_maestro')->references('id')->on('asistencia_maestros');
           $tabla->foreign('idfecha')->references('id')->on('fecha_clase');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::drop('grupo');
    }
}
