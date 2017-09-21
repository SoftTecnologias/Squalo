<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class TableMaestroPago extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maestro_pago', function (Blueprint $table) {
            $table->integer('idmaestro');
            $table->float('claseIndividual');
            $table->float('claseGrupal');
            $table->float('claseEspecial');


            $table->foreign('idmaestro')->references('id')->on('maestros');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('maestro_pago');
    }
}
