<?php

namespace App\Http\Controllers;

use App\AsistenciaMaestro;
use App\Maestro;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use yajra\Datatables\Datatables;

class AsistenciasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hoy = getdate();
        if($hoy['mon']<10){
            $hoy['mon'] = '0'.$hoy['mon'];
        }
        if($hoy['mday']<10){
            $hoy['mday'] = '0'.$hoy['mday'];
        }
        $fecha = $hoy['year'].'-'.$hoy['mon'].'-'.($hoy['mday']);

        $asistencias = DB::table('grupo as g')
            ->select('tc.descripcion','fc.fecha','h.Hora','m.nombre','c.id')
            ->join('fecha_clase as fc','fc.id','=','g.idfecha')
            ->join('clase as c','c.id','=','fc.idclase')
            ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
            ->join('maestros as m','m.id','=','c.idmaestro')
            ->join('horarios as h','h.id','=','c.idhorario')
            ->where('fc.fecha','=',$fecha)
            ->get();

        return Datatables::of(collect($asistencias))->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public  function AlumnosClase($id){
        try {
            $hoy = getdate();
            if ($hoy['mon'] < 10) {
                $hoy['mon'] = '0' . $hoy['mon'];
            }
            if ($hoy['mday'] < 10) {
                $hoy['mday'] = '0' . $hoy['mday'];
            }
            $fecha = $hoy['year'] . '-' . $hoy['mon'] . '-' . ($hoy['mday']);

            $asistencias = DB::table('clase as c')
                ->select('g.id', 'a.nombre as na', 'a.ape_paterno as apa', 'a.ape_materno as ama', 'ga.asistencia as asal',
                    'am.asistencia as asma', 'm.nombre as nm', 'm.ape_paterno as apm', 'm.ape_materno as amm','a.id as alumn')
                ->join('fecha_clase as fc', 'c.id', '=', 'fc.idclase')
                ->join('grupo as g', 'g.idfecha', '=', 'fc.id')
                ->join('grupo_alumnos as ga','ga.idGrupo','=','g.id')
                ->join('alumnos as a', 'a.id', '=', 'ga.idAlumno')
                ->join('asistencia_maestros as am', 'am.id', '=', 'g.id_asis_maestro')
                ->join('maestros as m', 'm.id', '=', 'c.idmaestro')
                ->where('fc.fecha', '=', $fecha)
                ->where('c.id','=',$id)
                ->get();
            $respuesta = ["code"=>200, 'data'=>$asistencias,"detail"=>"success"];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(),"detail"=>"error"];
        }
        return Response::json($respuesta);
    }

    public function asistenciaMaestro(Request $request,$id){
        try {
            $grupo = DB::table('grupo')
                ->select('*')
                ->where('id','=',$id)->get();
            $asismaestro = '';
            foreach ($grupo as $g) {
                $asismaestro = AsistenciaMaestro::findOrFail($g->id_asis_maestro);
            }
                $up=([
                    "asistencia" => $request->check,
                    "remplazo" => null
                ]);

                $asismaestro->fill($up);
                $asismaestro->save();


            $respuesta = ["code"=>200, 'data'=>'',"detail"=>"success"];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(),"detail"=>"error"];
        }
        return Response::json($respuesta);
    }

    public function asistenciaAlumno(Request $request,$id){
        try {
            $asis =0;
            if($request->check == 'true'){
                $asis = 1;
            }else{
                $asis = 0;
            }
            DB::table('grupo_alumnos')
                ->where('idAlumno','=',$id)
                ->where('idGrupo','=',$request->grupo)
                ->update(['asistencia'=>$asis]);

            $respuesta = ["code"=>200, 'data'=>'',"detail"=>"success"];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(),"detail"=>"error"];
        }
        return Response::json($respuesta);
    }

    public function remplazo(Request $request,$id){
        try {

            $grupo = DB::table('grupo')
                ->select('*')
                ->where('id','=',$id)->get();
            $asismaestro = '';
            foreach ($grupo as $g) {
                $asismaestro = AsistenciaMaestro::findOrFail($g->id_asis_maestro);
            }
            $up=([
                "remplazo" => $request->remplazo
            ]);

            $asismaestro->fill($up);
            $asismaestro->save();


            $respuesta = ["code"=>200, 'data'=>'',"detail"=>"success"];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(),"detail"=>"error"];
        }
        return Response::json($respuesta);
    }
}
