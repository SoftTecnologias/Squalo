<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class TablePagos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos',function (Blueprint $table){
            $table->increments('id');
            $table->integer('idAlumno');
            $table->float('abono');
            $table->tinyInteger('cancel');
            $table->date('fecha');

            $table->foreign('idAlumno')->references('id')->on('alumnos');
        });

        /*DB::exec('
        use Squalo;
        create trigger Triggerpagos on pagos
        for insert,update
        as
        begin
	    declare @deudaActual float
	    declare @abono float
	    declare @accion varchar(15)
	
			if (select COUNT(*) from inserted) > 0 and (select COUNT(*) from deleted) = 0
			begin 
			set @accion = \'insert\'
			end
			if (select COUNT(*) from inserted) > 0 and (select COUNT(*) from deleted) > 0
			begin 
			set @accion = \'update\'
			end				
			
			if @accion = \'insert\'
				begin 
					select @abono = abono from inserted
					
					update alumnos set adeudo=(adeudo-@abono)
				end
			if @accion = \'update\'
				begin
					select @abono = abono from deleted
					
					update alumnos set adeudo=(adeudo+@abono)
				end
				
        end');*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pagos');
    }
}
