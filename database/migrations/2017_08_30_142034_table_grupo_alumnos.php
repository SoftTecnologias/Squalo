<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class TableGrupoAlumnos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupo_alumnos',function (Blueprint $table){
            $table->integer('idAlumno');
            $table->integer('idGrupo');
            $table->tinyInteger('asistencia');

            $table->foreign('idAlumno')->references('id')->on('alumnos');
            $table->foreign('idGrupo')->references('id')->on('grupo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('grupo_alumnos');
    }
}
