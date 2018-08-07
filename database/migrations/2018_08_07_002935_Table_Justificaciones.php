<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;


class TableJustificaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('justificacion',function (Blueprint $table){
            $table->increments('id');
            $table->integer('idAlumno');
            $table->date('fecha');
            $table->longText('motivo');

            $table->foreign('idAlumno')->references('id')->on('alumnos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('justificacion');
    }
}
