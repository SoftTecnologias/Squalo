<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class TableAlumnos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumnos',function (Blueprint $table){
            $table->increments('id');
            $table->string('nombre',100);
            $table->string('ape_paterno',50);
            $table->string('ape_materno',50);
            $table->integer('padreid');
            $table->date('fecha_nac');
            $table->float('adeudo');
            $table->tinyInteger('asignado');

            $table->foreign('padreid')->references('id')->on('padres');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::drop('alumnos');
    }
}
