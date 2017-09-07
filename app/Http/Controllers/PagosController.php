<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class PagosController extends Controller
{
    public function getInfo(Request $request){
        try{
            //Insercion del producto
            $clasesIndividuales = DB::table('maestros as m')
                ->select('*')
                ->join('clase as c','c.idmaestro','=','m.id')
                ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
                ->join('fecha_clase as fc','fc.idclase','=','c.id')
                ->join('grupo as g','g.idfecha','=','fc.id')
                ->join('asistencia_maestros as am','g.id_asis_maestro','=','am.id')
                ->where('m.id','=',$request->maestro)
                ->where('tc.tipo_clase','=','I')
                ->where('fc.fecha','>=',$request->inicial)
                ->where('fc.fecha','<=',$request->final)
                ->where('am.asistencia','=',1)
                ->count();
            $clasesGrupales = DB::table('maestros as m')
                ->select('*')
                ->join('clase as c','c.idmaestro','=','m.id')
                ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
                ->join('fecha_clase as fc','fc.idclase','=','c.id')
                ->join('grupo as g','g.idfecha','=','fc.id')
                ->join('asistencia_maestros as am','g.id_asis_maestro','=','am.id')
                ->where('m.id','=',$request->maestro)
                ->where('tc.tipo_clase','=','G')
                ->where('fc.fecha','>=',$request->inicial)
                ->where('fc.fecha','<=',$request->final)
                ->where('am.asistencia','=',1)
                ->count();
            $clasesEspeciales = DB::table('maestros as m')
                ->select('*')
                ->join('clase as c','c.idmaestro','=','m.id')
                ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
                ->join('fecha_clase as fc','fc.idclase','=','c.id')
                ->join('grupo as g','g.idfecha','=','fc.id')
                ->join('asistencia_maestros as am','g.id_asis_maestro','=','am.id')
                ->where('m.id','=',$request->maestro)
                ->where('tc.tipo_clase','=','E')
                ->where('fc.fecha','>=',$request->inicial)
                ->where('fc.fecha','<=',$request->final)
                ->where('am.asistencia','=',1)
                ->count();
            $pagos = DB::table('maestros as m')
                ->select('tc.cuota_maestro')
                ->join('maestro_pago as mp','m.id','=','mp.idmaestro')
                ->join('clase as c','c.idmaestro','=','m.id')
                ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
                ->where('m.id','=',$request->maestro)
                ->get(1);

            $datos = ['clasesIndividuales'=>$clasesIndividuales,
                'clasesGrupales'=>$clasesGrupales,
                'clasesEspeciales'=>$clasesEspeciales];
            dd($pagos);

            $respuesta = ["code"=>200, "msg"=>'El maestros fue registrado exitosamente', 'detail' => 'success'];
        }catch (Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }
}
