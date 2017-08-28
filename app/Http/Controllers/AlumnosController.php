<?php

namespace App\Http\Controllers;

use App\Alumno;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use yajra\Datatables\Datatables;

class AlumnosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      /*  $alumnos = DB::table('alumnos as a')
            ->select('a.id','a.nombre as nombre','a.ape_paterno as app','a.ape_materno as apm','a.fecha_nac as fecha',
                'tc.tipo_clase as tipo_clase',
                'm.nombre as maestro','a.adeudo as adeudo','h.Hora as hora')
            ->join('grupo as g','g.idalumno','=','a.id')
            ->join('fecha_clase as fc','fc.id','=','g.idfecha')
            ->join('clase as c','c.id','=','fc.idclase')
            ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
            ->join('maestros as m','m.id','=','c.idmaestro')
            ->join('horarios as h','h.id','=','c.idhorario')
            ->get();*/

        $alumnos = DB::table('alumnos as a')->select('a.id','a.nombre','a.ape_paterno','a.ape_materno','a.adeudo','a.fecha_nac',
            'a.asignado','p.nombre as npadre','p.ape_paterno as appadre','p.ape_materno as ampadre')
        ->join('padres as p','padreid','=','p.id')->get();
        return Datatables::of(collect($alumnos))->make(true);
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
        try{
            //Insercion del producto
            $tipoid = DB::table('alumnos')->insertGetId([
                "nombre"        => $request->input('name')   ,
                "ape_paterno"        => $request->input('ape_pat')   ,
                "ape_materno"   => $request->input('ape_mat')   ,
                "fecha_nac"      => $request->input('fecha_nac'),
                "padreid" => $request->input('padre'),
                "adeudo" => 0
            ]);

            $respuesta = ["code"=>200, "msg"=>'El maestros fue registrado exitosamente', 'detail' => 'success'];
        }catch (Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
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
