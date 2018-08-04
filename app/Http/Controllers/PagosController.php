<?php

namespace App\Http\Controllers;

use Anouar\Fpdf\Fpdf;
use App\AsistenciaMaestro;
use App\Maestro;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class PagosController extends Controller
{
    private $datos;
    public function getInfo(Request $request){
        try{
            $fechas = [];

            $in = 0;
            $gru = 0;
            $esp = 0;
            $rem = 0;

            for($i=$request->inicial;$i<=$request->final;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){

                $clasesIndividuales = DB::table('maestros as m')
                    ->select('*')
                    ->join('clase as c','c.idmaestro','=','m.id')
                    ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
                    ->join('fecha_clase as fc','fc.idclase','=','c.id')
                    ->join('grupo as g','g.idfecha','=','fc.id')
                    ->join('asistencia_maestros as am','g.id_asis_maestro','=','am.id')
                    ->where('m.id','=',$request->maestro)
                    ->where('tc.tipo_clase','=','I')
                    ->where('fc.fecha','=',$i)
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
                    ->where('fc.fecha','=',$i)
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
                    ->where('fc.fecha','=',$i)
                    ->where('am.asistencia','=',1)
                    ->count();

                $reemplazos = DB::table('asistencia_maestros')
                    ->select('*')
                    ->where('remplazo','=',$request->maestro)
                    ->where('fecha','=',$i)
                    ->count();

                $in = $in+$clasesIndividuales;
                $gru = $gru+$clasesGrupales;
                $esp = $esp+$clasesEspeciales;
                $rem = $rem+$reemplazos;

                array_push($fechas,[
                    "fecha" => $i,
                    "individuales" => $clasesIndividuales,
                    "grupales" => $clasesGrupales,
                    "especiales" => $clasesEspeciales,
                    "reemplazos" => $reemplazos
                ]);
            }





            $pagos = DB::table('maestros as m')
                ->select('claseIndividual','claseGrupal','claseEspecial')
                ->join('maestro_pago as mp','m.id','=','mp.idmaestro')
                ->where('m.id','=',$request->maestro)
                ->first();

            $pagosclases = DB::table('pago_clase as m')
                ->select('*')
                ->get();


            foreach ($pagosclases as $pagoclase){
                switch ($pagoclase->tipoclase){
                    case 'I': $individial = ($in*$pagoclase->pago_por_clase);
                        $reemplazo = ($rem*$pagoclase->pago_por_clase);
                        break;
                    case 'G': $grupal = ($gru*$pagoclase->pago_por_clase);
                        break;
                    case 'E': $especial = ($esp*$pagoclase->pago_por_clase);
                        break;
                    case 'R':
                }
            }



            $datos = [
                'clases'=>$fechas,
                'individual'=>$individial,
                'grupal'=>$grupal,
                'especial'=>$especial,
                'reemplazo' => $reemplazo,
                "tcin" => $in,
                "tcgru" => $gru,
                "tcesp" => $esp,
                "tcrem" => $rem
            ];
            $respuesta = ["code"=>200, "msg"=>$datos, 'detail' => 'success'];
        }catch (Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

    public function exportpdf($id) {
        $datos = explode('&',$id);
        $reemplazos = DB::table('asistencia_maestros')
            ->select('*')
            ->where('remplazo','=',$datos[0])
            ->count();
        $clasesIndividuales = DB::table('maestros as m')
            ->select('*')
            ->join('clase as c','c.idmaestro','=','m.id')
            ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
            ->join('fecha_clase as fc','fc.idclase','=','c.id')
            ->join('grupo as g','g.idfecha','=','fc.id')
            ->join('asistencia_maestros as am','g.id_asis_maestro','=','am.id')
            ->where('m.id','=',$datos[0])
            ->where('tc.tipo_clase','=','I')
            ->where('fc.fecha','>=',$datos[1])
            ->where('fc.fecha','<=',$datos[2])
            ->where('am.asistencia','=',1)
            ->count();
        $clasesGrupales = DB::table('maestros as m')
            ->select('*')
            ->join('clase as c','c.idmaestro','=','m.id')
            ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
            ->join('fecha_clase as fc','fc.idclase','=','c.id')
            ->join('grupo as g','g.idfecha','=','fc.id')
            ->join('asistencia_maestros as am','g.id_asis_maestro','=','am.id')
            ->where('m.id','=',$datos[0])
            ->where('tc.tipo_clase','=','G')
            ->where('fc.fecha','>=',$datos[1])
            ->where('fc.fecha','<=',$datos[2])
            ->where('am.asistencia','=',1)
            ->count();
        $clasesEspeciales = DB::table('maestros as m')
            ->select('*')
            ->join('clase as c','c.idmaestro','=','m.id')
            ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
            ->join('fecha_clase as fc','fc.idclase','=','c.id')
            ->join('grupo as g','g.idfecha','=','fc.id')
            ->join('asistencia_maestros as am','g.id_asis_maestro','=','am.id')
            ->where('m.id','=',$datos[0])
            ->where('tc.tipo_clase','=','E')
            ->where('fc.fecha','>=',$datos[1])
            ->where('fc.fecha','<=',$datos[2])
            ->where('am.asistencia','=',1)
            ->count();
        $pagos = DB::table('maestros as m')
            ->select('claseIndividual','claseGrupal','claseEspecial','m.nombre','m.ape_paterno','m.ape_materno')
            ->join('maestro_pago as mp','m.id','=','mp.idmaestro')
            ->where('m.id','=',$datos[0])
            ->get(1);

        $pagosclases = DB::table('pago_clase as m')
            ->select('*')
            ->get();

        foreach ($pagos as  $pago){
            $individial = ($pago->claseIndividual/100);
            $grupal = ($pago->claseGrupal/100);
            $especial = ($pago->claseEspecial/100);
            $maestro = $pago->nombre.' '.$pago->ape_paterno.' '.$pago->ape_materno;
        }

        foreach ($pagosclases as $pagoclase){
            switch ($pagoclase->tipoclase){
                case 'I': $individial = ($individial*$pagoclase->pago_por_clase);
                    break;
                case 'G': $grupal = ($grupal*$pagoclase->pago_por_clase);
                    break;
                case 'E': $especial = ($especial*$pagoclase->pago_por_clase);
                    break;
            }
        }
        $pdf = new Fpdf();
       $pdf->addPage();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(700,85,$pdf->Image(asset('img.jpg'),30,12,45),0,0,'C');
        $pdf->Cell(100,12,"Presupuesto: ");
        $pdf->Cell(100,12,"Fecha: ". date('d/m/Y'));
        $pdf->Ln(80);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(100,12,"Nombre :             ".$maestro);
        $pdf->Line(40,100,190,100);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Ln(15);
        $pdf->Cell(90,12,"");
        $pdf->Cell(20,12,"                                                         Del: ".$datos[1]."        Al: ".$datos[2]);
        $pdf->Line(151,114,170,114);
        $pdf->Line(176,114,195,114);
        $pdf->Ln(10);
        $pdf->Cell(100,12,"Clases");
        $pdf->Ln(10);
        $pdf->Cell(30,12,"");
        $pdf->Cell(50,12,"Individuales:     ".$clasesIndividuales);
        $pdf->Cell(50,12,"Pago p/clase:    $".$individial);
        $pdf->Cell(50,12,"Total Individual:    $".($clasesIndividuales*$individial));
        $pdf->Line(57,133,80,133);
        $pdf->Line(108,133,131,133);
        $pdf->Line(161,133,184,133);
        $pdf->Ln(10);
        $pdf->Cell(30,12,"");
        $pdf->Cell(50,12,"Grupales:    ".$clasesGrupales);
        $pdf->Cell(50,12,"Pago p/clase:    $".$grupal);
        $pdf->Cell(50,12,"Total Grupal:    $".($clasesGrupales*$grupal));
        $pdf->Line(53,143,76,143);
        $pdf->Line(108,143,131,143);
        $pdf->Line(161,143,184,143);
        $pdf->Ln(10);
        $pdf->Cell(30,12,"");
        $pdf->Cell(50,12,"Especiales:     ".$clasesEspeciales);
        $pdf->Cell(50,12,"Pago p/clase:    $".$especial);
        $pdf->Cell(50,12,"Total Especial:    $".($clasesEspeciales*$especial));
        $pdf->Line(56,153,79,153);
        $pdf->Line(108,153,131,153);
        $pdf->Line(161,153,184,153);
        $pdf->Ln(10);
        $pdf->Cell(135,12,"");
        $pdf->Cell(50,12,"SubTotal:    $".(($clasesIndividuales*$individial)+($clasesGrupales*$grupal)+($clasesEspeciales*$especial)));
        $pdf->Line(158,163,181,163);
        $pdf->Ln(10);
        $pdf->Cell(30,12,"");
        $pdf->Cell(50,12,"Reemplazos:     ".$reemplazos);
        $pdf->Cell(50,12,"Pago p/remplazo:    $".$especial);
        $pdf->Cell(50,12,"Total Reemplazos:    $".($reemplazos*$especial));
        $pdf->Line(58,173,81,173);
        $pdf->Line(114,173,137,173);
        $pdf->Line(165,173,188,173);
        $pdf->Ln(10);
        $pdf->Cell(135,12,"");
        $pdf->Cell(100,12,"Total:    $".(($clasesIndividuales*$individial)+($clasesGrupales*$grupal)+($clasesEspeciales*$especial)+($reemplazos*$especial)));
        $pdf->Line(153,183,185,183);
        $pdf->Output();
        //$fichero='Factura - '.$datos[0].' ('.$datos[1].' al '.$datos[2].').pdf';
        //$pdfdoc = $pdf->Output($fichero, "D");
        exit;
    }

    public function exportallpdf($id) {
        $datos = explode('&',$id);
        $maestros = Maestro::all();
        $pdf = new Fpdf();
        $cont = 0;
        $y = 0;
        $alto = 0;
        foreach ($maestros as $maestro){
            $alto = 20;
            if($cont % 2 == 0 || $cont == 0){
                $pdf->addPage();
                $alto = 50;
                $y = 70;
                $cont++;
            }
            $reemplazos = DB::table('asistencia_maestros')
                ->select('*')
                ->where('remplazo','=',$maestro->id)
                ->count();
        $clasesIndividuales = DB::table('maestros as m')
            ->select('*')
            ->join('clase as c','c.idmaestro','=','m.id')
            ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
            ->join('fecha_clase as fc','fc.idclase','=','c.id')
            ->join('grupo as g','g.idfecha','=','fc.id')
            ->join('asistencia_maestros as am','g.id_asis_maestro','=','am.id')
            ->where('m.id','=',$maestro->id)
            ->where('tc.tipo_clase','=','I')
            ->where('fc.fecha','>=',$datos[0])
            ->where('fc.fecha','<=',$datos[1])
            ->where('am.asistencia','=',1)
            ->count();
        $clasesGrupales = DB::table('maestros as m')
            ->select('*')
            ->join('clase as c','c.idmaestro','=','m.id')
            ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
            ->join('fecha_clase as fc','fc.idclase','=','c.id')
            ->join('grupo as g','g.idfecha','=','fc.id')
            ->join('asistencia_maestros as am','g.id_asis_maestro','=','am.id')
            ->where('m.id','=',$maestro->id)
            ->where('tc.tipo_clase','=','G')
            ->where('fc.fecha','>=',$datos[0])
            ->where('fc.fecha','<=',$datos[1])
            ->where('am.asistencia','=',1)
            ->count();
        $clasesEspeciales = DB::table('maestros as m')
            ->select('*')
            ->join('clase as c','c.idmaestro','=','m.id')
            ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
            ->join('fecha_clase as fc','fc.idclase','=','c.id')
            ->join('grupo as g','g.idfecha','=','fc.id')
            ->join('asistencia_maestros as am','g.id_asis_maestro','=','am.id')
            ->where('m.id','=',$maestro->id)
            ->where('tc.tipo_clase','=','E')
            ->where('fc.fecha','>=',$datos[0])
            ->where('fc.fecha','<=',$datos[1])
            ->where('am.asistencia','=',1)
            ->count();
        $pagos = DB::table('maestros as m')
            ->select('claseIndividual','claseGrupal','claseEspecial','m.nombre','m.ape_paterno','m.ape_materno')
            ->join('maestro_pago as mp','m.id','=','mp.idmaestro')
            ->where('m.id','=',$maestro->id)
            ->get(1);

        $pagosclases = DB::table('pago_clase as m')
            ->select('*')
            ->get();

        foreach ($pagos as  $pago){
            $individial = ($pago->claseIndividual/100);
            $grupal = ($pago->claseGrupal/100);
            $especial = ($pago->claseEspecial/100);
            $maestro = $pago->nombre.' '.$pago->ape_paterno.' '.$pago->ape_materno;
        }

        foreach ($pagosclases as $pagoclase){
            switch ($pagoclase->tipoclase){
                case 'I': $individial = ($individial*$pagoclase->pago_por_clase);
                    break;
                case 'G': $grupal = ($grupal*$pagoclase->pago_por_clase);
                    break;
                case 'E': $especial = ($especial*$pagoclase->pago_por_clase);
                    break;
            }
        }
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(700,85,$pdf->Image(asset('img.jpg'),30,12,45),0,0,'C');
            $pdf->Cell(100,12,"Presupuesto: ");
            $pdf->Cell(100,12,"Fecha: ". date('d/m/Y'));
            $pdf->Ln($alto);
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(100,12,"Nombre :             ".$maestro);
            $pdf->Line(40,$y+0,190,$y+0);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Ln(15);
            $pdf->Cell(90,12,"");
            $pdf->Cell(20,12,"                                                         Del: ".$datos[0]."        Al: ".$datos[1]);
            $y = $y+13;
            $pdf->Line(151,$y,170,$y);
            $pdf->Line(176,$y,195,$y);
            $pdf->Ln(10);
            $pdf->Cell(100,12,"Clases");
            $pdf->Ln(10);
            $y=$y+20;
            $pdf->Cell(30,12,"");
            $pdf->Cell(50,12,"Individuales:     ".$clasesIndividuales);
            $pdf->Cell(50,12,"Pago p/clase:    $".$individial);
            $pdf->Cell(50,12,"Total Individual:    $".($clasesIndividuales*$individial));
            $pdf->Line(57,$y,80,$y);
            $pdf->Line(108,$y,131,$y);
            $pdf->Line(161,$y,184,$y);
            $pdf->Ln(10);
            $y=$y+10;
            $pdf->Cell(30,12,"");
            $pdf->Cell(50,12,"Grupales:    ".$clasesGrupales);
            $pdf->Cell(50,12,"Pago p/clase:    $".$grupal);
            $pdf->Cell(50,12,"Total Grupal:    $".($clasesGrupales*$grupal));
            $pdf->Line(53,$y,76,$y);
            $pdf->Line(108,$y,131,$y);
            $pdf->Line(161,$y,184,$y);
            $pdf->Ln(10);
            $y=$y+10;
            $pdf->Cell(30,12,"");
            $pdf->Cell(50,12,"Especiales:     ".$clasesEspeciales);
            $pdf->Cell(50,12,"Pago p/clase:    $".$especial);
            $pdf->Cell(50,12,"Total Especial:    $".($clasesEspeciales*$especial));
            $pdf->Line(56,$y,79,$y);
            $pdf->Line(108,$y,131,$y);
            $pdf->Line(161,$y,184,$y);
            $pdf->Ln(10);
            $y=$y+10;
            $pdf->Cell(135,12,"");
            $pdf->Cell(50,12,"SubTotal:    $".(($clasesIndividuales*$individial)+($clasesGrupales*$grupal)+($clasesEspeciales*$especial)));
            $pdf->Line(158,$y,181,$y);
            $pdf->Ln(10);
            $y=$y+10;
            $pdf->Cell(30,12,"");
            $pdf->Cell(50,12,"Reemplazos:     ".$reemplazos);
            $pdf->Cell(50,12,"Pago p/remplazo:    $".$especial);
            $pdf->Cell(50,12,"Total Reemplazos:    $".($reemplazos*$especial));
            $pdf->Line(58,$y,81,$y);
            $pdf->Line(114,$y,137,$y);
            $pdf->Line(165,$y,188,$y);
            $pdf->Ln(10);
            $y=$y+10;
            $pdf->Cell(135,12,"");
            $pdf->Cell(100,12,"Total:    $".(($clasesIndividuales*$individial)+($clasesGrupales*$grupal)+($clasesEspeciales*$especial)+($reemplazos*$especial)));
            $pdf->Line(153,$y,185,$y);
            $y=$y+22;
        }
        $pdf->Output();
        //$fichero='Factura - todos ('.$datos[0].' al '.$datos[1].').pdf';
        //$pdfdoc = $pdf->Output($fichero, "D");
        exit;
    }
}
