<?php

namespace App\Http\Controllers;

use App\Horarios;
use App\Maestro;
use App\Padre;
use App\Tipos;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class UserController extends Controller
{
    //Formularios
   public function getIndex(){
       return view('index');
   }
   public function  getMaestrosForm(){
       try {
           return view('administrador.maestros');
       }catch (Exception $e){
           return $e;
       }
   }
   public function  getPadresForm(){
       try{
           return view('administrador.padresForm');
       }catch (Exception $e){
           return $e;
       }
   }
   public function  getAlumnosForm(){
       try{
           $padres = Padre::all();
           $tipos= Tipos::all();
           $maestros = Maestro::all();
           $horarios = Horarios::all();
           return view('administrador.alumnos',['padres' => $padres,'tipos' => $tipos,'maestros' => $maestros,
                                    'horarios' => $horarios]);
       }catch (Exception $e){
           return $e;
       }
   }
   public function getTiposForm(){
       try{
           return view('administrador.tipoclase');
       }catch (Exception $e){
           return $e;
       }
   }
   public function getAsistenciasForm(){
       try{
           $maestros = Maestro::all();
           $tipos = DB::table('tipo_clase')
                    ->select('*')
                    ->where('tipo_clase','=','G')
                    ->get();
           $horarios = Horarios::all();
           return view('administrador.asistencias',['maestros'=>$maestros,'horarios'=>$horarios,'tipos'=>$tipos]);
       }catch (Exception $e){
           return $e;
       }
   }
    public function getReemplazoForm(){
        try{
            return view('administrador.reemplazos');
        }catch (Exception $e){
            return $e;
        }
    }

    public  function  getClasesForm(){
        try{
            return view('administrador.clases');
        }catch (Rxception $e){
            return $e;
        }
    }
}
