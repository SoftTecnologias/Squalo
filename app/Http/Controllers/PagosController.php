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
                ->select('claseIndividual','claseGrupal','claseEspecial')
                ->join('maestro_pago as mp','m.id','=','mp.idmaestro')
                ->where('m.id','=',$request->maestro)
                ->get(1);

            $pagosclases = DB::table('pago_clase as m')
                ->select('*')
                ->get();

            foreach ($pagos as  $pago){
                $individial = ($pago->claseIndividual/100);
                $grupal = ($pago->claseGrupal/100);
                $especial = ($pago->claseEspecial/100);
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



            $datos = ['clasesIndividuales'=>$clasesIndividuales,
                'clasesGrupales'=>$clasesGrupales,
                'clasesEspeciales'=>$clasesEspeciales,
                'individual'=>$individial,
                'grupal'=>$grupal,
                'especial'=>$especial,
                'totalI' => ($clasesIndividuales*$individial),
                'totalG' => ($clasesGrupales*$grupal),
                'totalE' => ($clasesEspeciales*$especial),
                'ptotal' => (($clasesIndividuales*$individial)+($clasesGrupales*$grupal)+($clasesEspeciales*$especial))];
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
        $pdf->Cell(100,12,"Nombre :             ".$maestro);
        $pdf->Line(40,100,190,100);
        $pdf->Ln(15);
        $pdf->Cell(20,12,"                                                         Del: ".$datos[1]."        Al: ".$datos[2]);
        $pdf->Line(112,114,145,114);
        $pdf->Line(162,114,195,114);
        $pdf->Ln(15);
        $pdf->Cell(100,12,"Clases");
        $pdf->Ln(15);
        $pdf->Cell(90,12,"Individuales:  ".$clasesIndividuales."      Pago p/clase: $".$individial."     Total Individual: $".($clasesIndividuales*$individial));
        $pdf->Line(45,145,65,145);
        $pdf->Line(104,145,124,145);
        $pdf->Line(169,145,190,145);
        $pdf->Ln(15);
        $pdf->Cell(90,12,"Grupales:  ".$clasesGrupales."      Pago p/clase: $".$grupal."     Total Individual: $".($clasesGrupales*$grupal));
        $pdf->Line(37,160,57,160);
        $pdf->Line(96,160,116,160);
        $pdf->Line(161,160,182,160);
        $pdf->Ln(15);
        $pdf->Cell(90,12,"Especiales:  ".$clasesEspeciales."      Pago p/clase: $".$especial."     Total Individual: $".($clasesEspeciales*$especial));
        $pdf->Line(41,175,61,175);
        $pdf->Line(100,175,120,175);
        $pdf->Line(165,175,186,175);
        $pdf->Ln(15);
        $pdf->Cell(100,12,"                                                                               SubTotal: $".(($clasesIndividuales*$individial)+($clasesGrupales*$grupal)+($clasesEspeciales*$especial)));
        $pdf->Line(160,190,190,190);
        $pdf->Ln(15);
        $pdf->Cell(90,12,"Reemplazos:  ".$reemplazos."      Pago p/remplazo: $".$especial."     Total Individual: $".($reemplazos*$especial));
        $pdf->Line(44,205,64,205);
        $pdf->Line(105,205,125,205);
        $pdf->Line(170,205,190,205);
        $pdf->Ln(15);
        $pdf->Cell(100,12,"                                                                                 Total: $".(($clasesIndividuales*$individial)+($clasesGrupales*$grupal)+($clasesEspeciales*$especial)+($reemplazos*$especial)));
        $pdf->Line(155,220,190,220);
        //$pdf->Output();
        $fichero='Factura - '.$datos[0].' ('.$datos[1].' al '.$datos[2].').pdf';
        $pdfdoc = $pdf->Output($fichero, "D");
        exit;
    }

    public function exportallpdf($id) {
        $datos = explode('&',$id);
        $maestros = Maestro::all();
        $pdf = new Fpdf();
        foreach ($maestros as $maestro){
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
        $pdf->addPage();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(700,85,$pdf->Image(asset('img.jpg'),30,12,90),0,0,'C');
        $pdf->Cell(100,12,"Presupuesto: ");
        $pdf->Cell(100,12,"Fecha: ". date('d/m/Y'));
        $pdf->Ln(80);
        $pdf->Cell(100,12,"Nombre :             ".$maestro);
        $pdf->Line(40,100,190,100);
        $pdf->Ln(15);
        $pdf->Cell(20,12,"                                                         Del: ".$datos[0]."        Al: ".$datos[1]);
        $pdf->Line(112,114,145,114);
        $pdf->Line(162,114,195,114);
        $pdf->Ln(15);
        $pdf->Cell(100,12,"Clases");
        $pdf->Ln(15);
        $pdf->Cell(90,12,"Individuales:  ".$clasesIndividuales."      Pago p/clase: $".$individial."     Total Individual: $".($clasesIndividuales*$individial));
        $pdf->Line(45,145,65,145);
        $pdf->Line(104,145,124,145);
        $pdf->Line(169,145,190,145);
        $pdf->Ln(15);
        $pdf->Cell(90,12,"Grupales:  ".$clasesGrupales."      Pago p/clase: $".$grupal."     Total Individual: $".($clasesGrupales*$grupal));
        $pdf->Line(37,160,57,160);
        $pdf->Line(96,160,116,160);
        $pdf->Line(161,160,182,160);
        $pdf->Ln(15);
        $pdf->Cell(90,12,"Especiales:  ".$clasesEspeciales."      Pago p/clase: $".$especial."     Total Individual: $".($clasesEspeciales*$especial));
        $pdf->Line(41,175,61,175);
        $pdf->Line(100,175,120,175);
        $pdf->Line(165,175,186,175);
        $pdf->Ln(15);
        $pdf->Cell(100,12,"                                                                               SubTotal: $".(($clasesIndividuales*$individial)+($clasesGrupales*$grupal)+($clasesEspeciales*$especial)));
        $pdf->Line(160,190,190,190);
        $pdf->Ln(15);
        $pdf->Cell(90,12,"Reemplazos:  ".$reemplazos."      Pago p/remplazo: $".$especial."     Total Individual: $".($reemplazos*$especial));
            $pdf->Line(44,205,64,205);
            $pdf->Line(105,205,125,205);
            $pdf->Line(170,205,190,205);
            $pdf->Ln(15);
            $pdf->Cell(100,12,"                                                                                 Total: $".(($clasesIndividuales*$individial)+($clasesGrupales*$grupal)+($clasesEspeciales*$especial)+($reemplazos*$especial)));
            $pdf->Line(155,220,190,220);
        }
        //$pdf->Output();
        $fichero='Factura - todos ('.$datos[0].' al '.$datos[1].').pdf';
        $pdfdoc = $pdf->Output($fichero, "D");
        exit;
    }
}
