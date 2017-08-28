<?php

namespace App\Http\Controllers;

use App\Maestro;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use yajra\Datatables\Datatables;

class MaestrosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $servicios =Maestro::all();

        return Datatables::of(collect($servicios))->make(true);
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
            $maestroid = DB::table('maestros')->insertGetId([
                "nombre"        => $request->input('name')   ,
                "ape_paterno"        => $request->input('ape_pat')   ,
                "ape_materno"   => $request->input('ape_mat')   ,
                "colonia"      => $request->input('colonia'),
                "calle"     => $request->input('calle'),
                "numero"  => $request->input('numero'),
                "tel_fijo"=> $request->input('tel'),
                "tel_celular" => $request->input('phone'),
                "fecha_nac" => $request->input('fecha'),
                "email" => $request->input('email')
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
            $padres = Maestro::findOrFail($id);
            $up=([
                "nombre"        => $request->input('name')   ,
                "ape_paterno"        => $request->input('ape_pat')   ,
                "ape_materno"   => $request->input('ape_mat')   ,
                "colonia"      => $request->input('colonia'),
                "calle"     => $request->input('calle'),
                "numero"  => $request->input('numero'),
                "tel_fijo"=> $request->input('tel'),
                "tel_celular" => $request->input('phone'),
                "fecha_nac" => $request->input('fecha'),
                "email" => $request->input('email')
            ]);

            $padres->fill($up);
            $padres->save();

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
            $tipo = Maestro::findOrFail($id);
            $tipo->delete();

            $respuesta = ["code"=>200, "msg"=>'El producto ha sido eliminado', 'detail' => 'success'];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }
}
