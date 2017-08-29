<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
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
        $fecha = $hoy['year'].'-'.$hoy['mon'].'-'.($hoy['mday']-1);

        $asistencias = DB::table('grupo as g')
            ->select('tc.descripcion','fc.fecha','h.Hora','m.nombre')
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
}
