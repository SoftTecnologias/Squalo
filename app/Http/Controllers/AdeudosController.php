<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class AdeudosController extends Controller
{
    function getAdeudos(Request $request){
        try{
            if ($request->cookie('admin') != null) {
                date_default_timezone_set("America/Mexico_City");
                $hoy = date("Y-m-j");
                $alumnos = DB::table('alumnos as a')->select('a.id', 'a.nombre', 'a.ape_paterno', 'a.ape_materno', 'a.adeudo', 'a.fecha_nac',
                    'a.asignado', 'p.nombre as npadre', 'p.ape_paterno as appadre', 'p.ape_materno as ampadre','a.activo')
                    ->join('padres as p', 'padreid', '=', 'p.id')
                    ->where('a.activo','=',1)
                    ->where('a.adeudo','!=',0)
                    ->where('a.asignado','=',1)
                    ->get();
                $hoy = strtotime('+0 day', strtotime($hoy));
                foreach ($alumnos as $alumno) {
                    $al = DB::table('alumnos as a')->select(DB::raw('MAX(fc.fecha) as fecha'))
                        ->join('grupo_alumnos as ga', 'ga.idAlumno', '=', 'a.id')
                        ->join('grupo as g', 'g.id', '=', 'ga.idGrupo')
                        ->join('fecha_clase as fc', 'fc.id', '=', 'g.idfecha')
                        ->where('a.id', '=', $alumno->id)
                        ->first();

                    if ($al->fecha != null) {
                        $alumno->limite = $al->fecha;
                        $fecha = strtotime('+0 day', strtotime($al->fecha));


                        if ($hoy > $fecha) {
                            $alumno->color = 'danger';
                            break;
                        }
                        $f = date('Y-m-j', $fecha);
                        $fecha1 = strtotime('-4 day', strtotime($f));
                        if ($hoy > $fecha1) {

                            $alumno->color = 'warning';
                            break;
                        }else{
                            $alumno->color = 'success';
                        }
                    }
                }

                return view('administrador.adeudos',["alumnos" => $alumnos]);
            } else {
                //no existe una session de administrador y lo manda al login
                return view('login');
            }
        }catch (Exception $e){
            return $e;
        }

    }
}
