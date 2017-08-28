<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class TablaFechaClase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fecha_clase',function (Blueprint $table){
            $table->increments('id');
            $table->date('fecha');
            $table->integer('idclase');

            $table->foreign('idclase')->references('id')->on('clase');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fecha_clase');
    }
}
