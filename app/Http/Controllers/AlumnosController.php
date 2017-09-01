<?php

namespace App\Http\Controllers;

use App\Alumno;
use App\pagos;
use App\Tipos;
use DateTime;
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
        $alumnos = DB::table('alumnos as a')->select('a.id', 'a.nombre', 'a.ape_paterno', 'a.ape_materno', 'a.adeudo', 'a.fecha_nac',
            'a.asignado', 'p.nombre as npadre', 'p.ape_paterno as appadre', 'p.ape_materno as ampadre')
            ->join('padres as p', 'padreid', '=', 'p.id')->get();
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            //Insercion
            $tipoid = DB::table('alumnos')->insertGetId([
                "nombre" => $request->input('name'),
                "ape_paterno" => $request->input('ape_pat'),
                "ape_materno" => $request->input('ape_mat'),
                "fecha_nac" => $request->input('fecha_nac'),
                "padreid" => $request->input('padre'),
                "adeudo" => 0,
                "asignado" => 0
            ]);

            $respuesta = ["code" => 200, "msg" => 'El maestros fue registrado exitosamente', 'detail' => 'success'];
        } catch (Exception $e) {
            $respuesta = ["code" => 500, "msg" => $e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function asignar(Request $request, $id)
    {
        try {
            $fechas = explode(',', $request->input('alldates'));
            $finicial = date(str_replace('/','-',$fechas[0]));
            $f = strtotime($finicial);

            $claseid = DB::table('clase')->insertGetId([
                "idtipo_clase" => $request->input('tipoc'),
                "idmaestro" => $request->input('maestroc'),
                "fechainicio" => date('Y-m-d',$f),
                "idhorario" => $request->input('horario'),
            ]);

            $alumno = Alumno::findOrFail($id);
            $tipo_clase = Tipos::findOrFail($request->input('tipoc'));
            $up=([
                "adeudo" => $tipo_clase->costo,
                "asignado" => 1
            ]);

            $alumno->fill($up);
            $alumno->save();

            foreach ($fechas as $fecha) {
                $fecha = date(str_replace('/','-',$fecha));
                $f = strtotime($fecha);
                $fcid = DB::table('fecha_clase')->insertGetId([
                    "fecha" => date('Y-m-d',$f),
                    "idclase" => $claseid
                ]);

                $asismaestroid = DB::table('asistencia_maestros')->insertGetId([
                    "asistencia" => 0,
                    "remplazo" => null
                ]);

                $grupoid = DB::table('grupo')->insertGetId([
                    "id_asis_maestro" => $asismaestroid,
                    "idfecha" => $fcid
                ]);

                $gru_al = DB::table('grupo_alumnos')->insertGetId([
                    "idAlumno" => $request->input('idasignar'),
                    "idGrupo" => $grupoid,
                    "asistencia" => 0,
                ]);
            }

            $respuesta = ["code" => 200, "msg" => 'El maestros fue registrado exitosamente', 'detail' => 'success'];
        } catch (Exception $e) {
            $respuesta = ["code" => 500, "msg" => $e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

    public  function getPagos($id){
        try {
            $hoy = getdate();
            if ($hoy['mon'] < 10) {
                $hoy['mon'] = '0' . $hoy['mon'];
            }
            if ($hoy['mday'] < 10) {
                $hoy['mday'] = '0' . $hoy['mday'];
            }
            $fecha = $hoy['year'] . '-' . $hoy['mon'] . '-' . ($hoy['mday']);

            $asistencias = DB::table('pagos as p')
                ->select('p.abono','p.fecha','p.id','p.cancel','a.nombre','a.ape_paterno','a.ape_materno')
                ->join('alumnos as a','p.idAlumno','=','a.id')
                ->where('a.id','=',$id)
                ->get();
            $respuesta = ["code"=>200, 'data'=>$asistencias,"detail"=>"success"];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(),"detail"=>"error"];
        }
        return Response::json($respuesta);
    }

    public function abonar(Request $request,$id){
        try{
            $hoy = getdate();
            if ($hoy['mon'] < 10) {
                $hoy['mon'] = '0' . $hoy['mon'];
            }
            if ($hoy['mday'] < 10) {
                $hoy['mday'] = '0' . $hoy['mday'];
            }
            $fecha = $hoy['year'] . '-' . $hoy['mon'] . '-' . ($hoy['mday']);

            $idpago = DB::table('pagos')->insertGetId([
                "idAlumno" => $id,
                "abono" => $request->abonar,
                "cancel" => 0,
                "fecha" => $fecha,
            ]);

            $respuesta = ["code" => 200, "msg" => 'El maestros fue registrado exitosamente', 'detail' => 'success'];
        } catch (Exception $e) {
            $respuesta = ["code" => 500, "msg" => $e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);

    }

    public function cancelAbono(Request $request,$id){
        try{
            $pago = pagos::findOrFail($id);
            $up=([
                "cancel" => 1
            ]);

            $pago->fill($up);
            $pago->save();
            $respuesta = ["code" => 200, "msg" => 'El pago ha sido cancelado', 'detail' => 'success'];
     }catch (Exception $e){
            $respuesta = ["code" => 500, "msg" => $e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

    public function getHorarios(Request $request){
        try{

            $fechas = DB::table('horarios as h')
                ->select('fc.fecha','h.Hora')
                ->join('clase as c', 'c.idhorario', '=', 'h.id')
                ->join('fecha_clase as fc','fc.idclase','=','c.id')
                ->join('maestros as m','m.id','=','c.idmaestro')
                ->where('m.id','=',$request->maestro)
               ->where('h.id','=',$request->horario)
                ->get();

            $respuesta = ["code" => 200, "msg" => $fechas, 'detail' => 'success'];
        }catch (Exception $e){
            $respuesta = ["code" => 500, "msg" => $e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

}

