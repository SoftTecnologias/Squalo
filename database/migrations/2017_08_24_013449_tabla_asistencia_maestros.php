<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class TablaAsistenciaMaestros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asistencia_maestros',function (Blueprint $tabla){
            $tabla->increments('id');
            $tabla->boolean('asistencia')->nullable();
            $tabla->integer('remplazo')->nullable();

            $tabla->foreign('remplazo')->references('id')->on('maestros');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('asistencia_maestros');
    }
}
