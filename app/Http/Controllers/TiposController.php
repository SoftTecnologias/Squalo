<?php

namespace App\Http\Controllers;

use App\Tipos;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use yajra\Datatables\Datatables;

class TiposController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipos =Tipos::all();

        return Datatables::of(collect($tipos))->make(true);
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
            $tipoid = DB::table('tipo_clase')->insertGetId([
                "tipo_clase"        => $request->input('tipo')   ,
                "descripcion"        => $request->input('desc')   ,
                "costo"   => $request->input('costo')   ,
                "numero_clases"      => $request->input('noclases'),
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
        try{
            //$id = base64_decode($id);
            $tipo = Tipos::findOrFail($id);
            $up=([
                "tipo_clase"        => $request->input('tipo')   ,
                "descripcion"        => $request->input('desc')   ,
                "costo"   => $request->input('costo')   ,
                "numero_clases"      => $request->input('noclases'),
            ]);

            $tipo->fill($up);
            $tipo->save();

            /*No hay manera de revisar (hasta el momento) para revisar que cambiaron todos asi que los actualizaré a ambos*/

            $respuesta = ["code"=>200, "msg"=>"Servicio Actualizado","detail"=>"success"];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(),"detail"=>"error"];
        }
        return Response::json($respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            // $id = base64_decode($id);
            $tipo = Tipos::findOrFail($id);
            $tipo->delete();

            $respuesta = ["code"=>200, "msg"=>'El producto ha sido eliminado', 'detail' => 'success'];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

}
