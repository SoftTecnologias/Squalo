<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class TableTipoClase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_clase',function (Blueprint $table){
           $table->increments('id');
           $table->string('tipo_clase',60);
           $table->string('descripcion');
           $table->float('costo');
           $table->integer('numero_clases');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tipo_clase');
    }
}
