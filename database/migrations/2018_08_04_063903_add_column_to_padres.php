<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToPadres extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('padres', function (Blueprint $table) {
            $table->string('RFC')->nullable();
            $table->string('Trabajo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('padres', function (Blueprint $table) {
            $table->dropColumn('RFC');
            $table->dropColumn('Trabajo');
        });
    }
}
