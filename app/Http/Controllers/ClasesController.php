<?php

namespace App\Http\Controllers;

use App\FechaClase;
use App\GrupoAlumno;
use App\Justificante;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use yajra\Datatables\Datatables;

class ClasesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $infoClases= DB::table('clase as c')
            ->select('m.nombre as mnombre','m.ape_paterno as map','m.ape_materno as mam',
                'p.nombre as pnombre','p.ape_paterno as pap','p.ape_materno as pam',
                'a.nombre as anombre', 'a.ape_paterno as aap', 'a.ape_materno as aam',
                'ga.asistencia as asisalumno','fc.fecha','am.asistencia as asismaestro','am.remplazo',
                'c.id as idclase','a.id as idalumno')
            ->join('maestros as m', 'c.idmaestro', '=', 'm.id')
            ->join('fecha_clase as fc', 'fc.idclase', '=', 'c.id')
            ->join('grupo as g', 'g.idfecha', '=', 'fc.id')
            ->join('grupo_alumnos as ga', 'g.id', '=', 'ga.idGrupo')
            ->join('alumnos as a', 'a.id', '=', 'ga.idAlumno')
            ->join('padres as p', 'p.id', '=', 'a.padreid')
            ->join('asistencia_maestros as am', 'am.id', '=', 'g.id_asis_maestro')
            ->get();

        foreach ($infoClases as $clase){
            if($clase->remplazo != null){
                $remplazo = DB::table('maestros')->select('*')->where('id','=',$clase->remplazo)->get();
                foreach ($remplazo as $item){
                    $clase->mnombre = 'R: '.$item->nombre;
                    $clase->map = $item->ape_paterno;
                    $clase->mam = $item->ape_materno;
                }
            }
        }
        return Datatables::of(collect($infoClases))->make(true);
    }

    function Justifica(Request $request,$id){
        try {

            Justificante::create([
                'idAlumno' => $request->idalumno,
                'fecha' => $request->actual,
                'motivo' => $request->motivo
            ]);

            $clase = FechaClase::where('fecha', '=', $request->actual)->where('idclase', '=', $id)->first();

            $clase->fecha = $request->nueva;
            $clase->save();

            return Response::json(["code" => 200,'error'=>"no"]);

        }catch (Exception $e){
            return Response::json(["code" => 500,'error'=>$e->getMessage()]);
        }
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
}
