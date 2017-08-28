<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class TablaClase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clase',function (Blueprint $table){
            $table->increments('id');
            $table->integer('idtipo_clase');
            $table->integer('idmaestro');
            $table->date('fechainicio');
            $table->integer('idhorario');


            $table->foreign('idtipo_clase')->references('id')->on('tipo_clase');
            $table->foreign('idmaestro')->references('id')->on('maestros');
            $table->foreign('idhorario')->references('id')->on('horarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('clase');
    }
}
