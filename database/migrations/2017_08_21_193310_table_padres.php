<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class TablePadres extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('padres', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',100);
            $table->string('ape_paterno',50);
            $table->string('ape_materno',50);
            $table->string('colonia',100);
            $table->string('calle',100);
            $table->integer('numero');
            $table->string('tel_fijo',15)->nullable();
            $table->string('tel_celular',20)->nullable();
            $table->date('fecha_nac');
            $table->longText('email');
            //$table->foreign('userid')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('padres');
    }
}
