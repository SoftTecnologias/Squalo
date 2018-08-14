<?php

namespace App\Http\Controllers;

use Anouar\Fpdf\Fpdf;
use App\Alumno;
use App\Justificante;
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
        date_default_timezone_set("America/Mexico_City");
        $hoy = date("Y-m-j");
        $alumnos = DB::table('alumnos as a')->select('a.id', 'a.nombre', 'a.ape_paterno', 'a.ape_materno', 'a.adeudo', 'a.fecha_nac',
            'a.asignado', 'p.nombre as npadre', 'p.ape_paterno as appadre', 'p.ape_materno as ampadre','a.activo')
            ->join('padres as p', 'padreid', '=', 'p.id')
            ->where('a.activo','=',1)
            ->get();
        $hoy = strtotime('+0 day', strtotime($hoy));
        foreach ($alumnos as $alumno) {
            $al = DB::table('alumnos as a')->select(DB::raw('MAX(fc.fecha) as fecha'))
                ->join('grupo_alumnos as ga', 'ga.idAlumno', '=', 'a.id')
                ->join('grupo as g', 'g.id', '=', 'ga.idGrupo')
                ->join('fecha_clase as fc', 'fc.id', '=', 'g.idfecha')
                ->where('a.id', '=', $alumno->id)
                ->first();

            if ($alumno->adeudo == 0) {
                $alumno->color = 'verde';

                if($alumno->asignado == 0){
                    $alumno->color = 'blanco';
                }

                if($al->fecha != null) {
                    $fecha = strtotime('+0 day', strtotime($al->fecha));
                    $hoy = strtotime('+0 day', strtotime($hoy));

                    if ($hoy > $fecha) {
                        $alumno->asignado = 0;
                        $alumno->color = 'blanco';
                    }
                }
            } else {
                if ($al->fecha != null) {
                    $fecha = strtotime('+0 day', strtotime($al->fecha));

                    if($alumno->asignado == 1 && $hoy > $fecha && $alumno->adeudo){

                    }
                    if ($hoy > $fecha) {
                        $alumno->color = 'rojo';
                        break;
                    }
                    $f = date('Y-m-j', $fecha);
                    $fecha1 = strtotime('-4 day', strtotime($f));

                    if ($hoy > $fecha1 && $hoy < $fecha) {
                        $alumno->color = 'amarillo';
                        break;
                    }else{
                        $alumno->color = 'azul';
                    }
                } else {
                    $alumno->color = 'blanco';
                }
            }
        }

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
                "asignado" => 0,
                "activo" => 1
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
                    "asistencia" => 1,
                    "remplazo" => null
                ]);

                $grupoid = DB::table('grupo')->insertGetId([
                    "id_asis_maestro" => $asismaestroid,
                    "idfecha" => $fcid
                ]);

                $gru_al = DB::table('grupo_alumnos')->insertGetId([
                    "idAlumno" => $request->input('idasignar'),
                    "idGrupo" => $grupoid,
                    "asistencia" => 1,
                ]);
            }

            $respuesta = ["code" => 200, "msg" => 'El maestros fue registrado exitosamente', 'detail' => 'success'];
        } catch (Exception $e) {
            $respuesta = ["code" => 500, "msg" => $e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

    public function asignargrupo(Request $request, $id)
    {
        try {
            $fechas = explode(',', $request->input('alldates'));
            $alumno = Alumno::findOrFail($id);
            $grupoid = DB::table('grupo as g')
                ->select('g.id')
                ->join('fecha_clase as fc','fc.id','=','g.idfecha')
                ->join('clase as c', 'c.id', '=', 'fc.idclase')
                ->where('c.id','=',$request->input('gruposdisp'))
                ->get();

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
                    "idclase" => $request->input('gruposdisp')
                ]);

                $asismaestroid = DB::table('asistencia_maestros')->insertGetId([
                    "asistencia" => 1,
                    "remplazo" => null
                ]);

                $gi= DB::table('grupo')->insertGetId([
                    "id_asis_maestro" => $asismaestroid,
                    "idfecha" => $fcid
                ]);

                $gru_al = DB::table('grupo_alumnos')->insertGetId([
                    "idAlumno" => $request->input('idasignar'),
                    "idGrupo" => $gi,
                    "asistencia" => 1,
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
            $hoy = date("Y-m-j");
            $nuevafecha = strtotime ( '-6 hour' , strtotime ( $hoy ) ) ;
            $hoy = date ( 'Y-m-j' , $nuevafecha );


            $fecha = $hoy;
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
            $hoy = date("Y-m-j");
            $nuevafecha = strtotime ( '-6 hour' , strtotime ( $hoy ) ) ;
            $hoy = date ( 'Y-m-j' , $nuevafecha );



            $fecha = $hoy;

            $idpago = DB::table('pagos')->insertGetId([
                "idAlumno" => $id,
                "abono" => $request->abonar,
                "cancel" => 0,
                "fecha" => $fecha,
            ]);


            $respuesta = ["code" => 200, "msg" => $idpago, 'detail' => 'success'];
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
                ->orderBy('h.Hora')
                ->get();

            $respuesta = ["code" => 200, "msg" => $fechas, 'detail' => 'success'];
        }catch (Exception $e){
            $respuesta = ["code" => 500, "msg" => $e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

    public function getGruposDisponibles($id){
        try{
            $hoy = date("Y-m-j");
            $nuevafecha = strtotime ( '-6 hour' , strtotime ( $hoy ) ) ;
            $hoy = date ( 'Y-m-j' , $nuevafecha );

            $fecha = $hoy;
            $fechas = DB::table('clase as c')
                ->select('c.id as idclase','h.hora as horario')
                ->join('horarios as h','c.idhorario','=','h.id')
                ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
                ->where('tc.id','=',$id)
                ->distinct()
                ->get();

            $respuesta = ["code" => 200, "msg" => $fechas, 'detail' => 'success'];
        }catch (Exception $e){
            $respuesta = ["code" => 500, "msg" => $e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

    public function infoGrupo($id){
        try{

            $clase = DB::table('clase as c')
                ->select('m.id as maestro','h.id as hora')
                ->join('maestros as m', 'm.id', '=', 'c.idmaestro')
                ->join('horarios as h','h.id','=','c.idhorario')
                ->where('c.id','=',$id)
                ->get();
            $respuesta = ["code" => 200, "msg" => $clase, 'detail' => 'success'];
        }catch (Exception $e){
            $respuesta = ["code" => 500, "msg" => $e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

    public function todos()
    {
        $alumnos = DB::table('alumnos as a')->select('a.id', 'a.nombre', 'a.ape_paterno', 'a.ape_materno', 'a.adeudo', 'a.fecha_nac',
            'a.asignado', 'p.nombre as npadre', 'p.ape_paterno as appadre', 'p.ape_materno as ampadre','a.activo')
            ->join('padres as p', 'padreid', '=', 'p.id')
            ->get();

        return Datatables::of(collect($alumnos))->make(true);
    }

    public function inactivos()
    {
        $alumnos = DB::table('alumnos as a')->select('a.id', 'a.nombre', 'a.ape_paterno', 'a.ape_materno', 'a.adeudo', 'a.fecha_nac',
            'a.asignado', 'p.nombre as npadre', 'p.ape_paterno as appadre', 'p.ape_materno as ampadre','a.activo')
            ->join('padres as p', 'padreid', '=', 'p.id')
            ->where('a.activo','=',0)
            ->get();
        return Datatables::of(collect($alumnos))->make(true);
    }

    public  function activos()
    {
            $alumnos = DB::table('alumnos as a')->select('a.id', 'a.nombre', 'a.ape_paterno', 'a.ape_materno', 'a.adeudo', 'a.fecha_nac',
                'a.asignado', 'p.nombre as npadre', 'p.ape_paterno as appadre', 'p.ape_materno as ampadre','a.activo')
                ->join('padres as p', 'padreid', '=', 'p.id')
                ->where('a.activo','=',1)
                ->get();

            return Datatables::of(collect($alumnos))->make(true);
    }

    public function fechasClases($id){
        try{
        $alumnos = DB::table('alumnos as a')->select('a.nombre', 'a.ape_paterno', 'a.ape_materno','a.fecha_nac',
            'p.nombre as npadre', 'p.ape_paterno as appadre', 'p.ape_materno as ampadre','fc.fecha','ga.asistencia')
            ->join('padres as p', 'padreid', '=', 'p.id')
            ->join('grupo_alumnos as ga','ga.idAlumno','=','a.id')
            ->join('grupo as g','g.id','=','ga.idGrupo')
            ->join('fecha_clase as fc','fc.id','=','g.idfecha')
            ->where('a.id','=',$id)
            ->get();

        foreach ($alumnos as $alumno){
            if($alumno->asistencia == 0){
                $alumno->asistencia = 'falta';
            }elseif ($alumno->asistencia == 1){
                $alumno->asistencia = 'asistio';
            }

        }
            $respuesta = ["code" => 200, "msg" => $alumnos, 'detail' => 'success'];
        }catch (Exception $e){
            $respuesta = ["code" => 500, "msg" => $e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

    public function bajaAlumno($id){
        try {
            $alumno = Alumno::findOrFail($id);
            $up = ([
                "activo" => 0
            ]);

            $alumno->fill($up);
            $alumno->save();
            $respuesta = ["code" => 200, "msg" => 'El Alumno se dio de Baja Correctamente', 'detail' => 'success'];
        } catch (Exception $e) {
            $respuesta = ["code" => 500, "msg" => $e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

    public function altaAlumno($id)
    {
        try {
            $alumno = Alumno::findOrFail($id);
            $up = ([
                "activo" => 1
            ]);

            $alumno->fill($up);
            $alumno->save();
            $respuesta = ["code" => 200, "msg" => 'El Alumno se dio de Alta Correctamente', 'detail' => 'success'];
        } catch (Exception $e) {
            $respuesta = ["code" => 500, "msg" => $e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

    function getJustificantes($id){
        try {
            $justificantes = Justificante::where('idAlumno','=',$id)->get();
            $respuesta = ["code"=>200, 'data'=>$justificantes,"detail"=>"success"];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(),"detail"=>"error"];
        }
        return Response::json($respuesta);
    }

    function generaRecibo($id){

        $datos = explode('&',$id);

        $id = $datos[0];

        date_default_timezone_set("America/Mexico_City");
        $hoy = date("Y-m-j");

        $alumno = DB::table('alumnos as a')->select('a.id', 'a.nombre', 'a.ape_paterno', 'a.ape_materno', 'a.adeudo', 'a.fecha_nac',
            'p.nombre as npadre', 'p.ape_paterno as appadre', 'p.ape_materno as ampadre','p.colonia','p.calle','p.numero',
            'p.tel_celular')
            ->join('padres as p', 'padreid', '=', 'p.id')
            ->where('a.id','=',$id)
            ->first();

        $final = DB::table('alumnos as a')->select(DB::raw('MAX(fc.fecha) as fecha'),'h.Hora','descripcion')
            ->join('grupo_alumnos as ga', 'ga.idAlumno', '=', 'a.id')
            ->join('grupo as g', 'g.id', '=', 'ga.idGrupo')
            ->join('fecha_clase as fc', 'fc.id', '=', 'g.idfecha')
            ->join('clase as c','c.id','=','fc.idclase')
            ->join('horarios as h','h.id','=','c.idhorario')
            ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
            ->groupBy('Hora')
            ->groupBy('descripcion')
            ->where('a.id', '=', $id)
            ->first();

        $inicial = DB::table('alumnos as a')->select(DB::raw('MIN(fc.fecha) as fecha'))
            ->join('grupo_alumnos as ga', 'ga.idAlumno', '=', 'a.id')
            ->join('grupo as g', 'g.id', '=', 'ga.idGrupo')
            ->join('fecha_clase as fc', 'fc.id', '=', 'g.idfecha')
            ->where('a.id', '=', $id)
            ->first();

        $namea = $alumno->nombre.' '.$alumno->ape_paterno.' '.$alumno->ape_materno;
        $namep = $alumno->npadre.' '.$alumno->appadre.' '.$alumno->ampadre;
        $domicilio = $alumno->colonia.' calle: '.$alumno->calle.' #'.$alumno->numero;
        $tel = $alumno->tel_celular;
        $horario = $final->Hora;
        $periodo = $inicial->fecha."  a  ".$final->fecha;
        $categoria = $final->descripcion;
        $total = $datos[1];
        $idp = $datos[2];

        $pdf = new Fpdf();
        $pdf->addPage();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(700,85,$pdf->Image(asset('impresion.jpg'),5,12,200),0,0,'C');
        $pdf->Cell(700,85,$pdf->Image(asset('impresion.jpg'),5,147,200),0,0,'C');

        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(500,12,"                                             
                                                                                            ".$idp);
        $pdf->Ln(12);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(500,12,"                                             
                                                                                        ".$hoy);
        $pdf->Ln(13);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(500,12,"                                             ".$namea);

        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(500,12,"                                             ".$namep);

        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(500,12,"                                 ".$domicilio);

        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(500,12,"                                 Nayarit                           
                                    ".$tel);

        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(500,12,"                                 ".$horario."                           
         ".$periodo);

        $pdf->Ln(9);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(500,12,"                                 ".$categoria);

        $pdf->Ln(42);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(500,12,"                                 "."
                                                                                 ".$total);
        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(500,12,"------------------------------------------------------------------------------------------------------------------------------------");

        $pdf->Ln(15);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(500,12,"                                             
                                                                                            ".$idp);
        $pdf->Ln(12);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(500,12,"                                             
                                                                                        ".$hoy);

        $pdf->Ln(12);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(500,12,"                                             ".$namea);

        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(500,12,"                                             ".$namep);

        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(500,12,"                                 ".$domicilio);

        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(500,12,"                                 Nayarit                           
                                    ".$tel);

        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(500,12,"                                 ".$horario."                           
         ".$periodo);

        $pdf->Ln(9);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(500,12,"                                 ".$categoria);

        $pdf->Ln(42);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(500,12,"                                 "."
                                                                                 ".$total);

        #$pdf->Output();
        $fichero='Recibo.pdf';
        $pdfdoc = $pdf->Output($fichero, "D");
        exit;
    }
}

