<?php

namespace App\Http\Controllers;

use Anouar\Fpdf\Fpdf;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class PagosController extends Controller
{
    private $datos;
    public function getInfo(Request $request){
        try{
            //Insercion del producto
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

    public function exportpdf() {
        $pdf = new Fpdf();
       $pdf->addPage();
        $pdf->SetFont('Arial', 'B', 16);
//inserto la cabecera poniendo una imagen dentro de una celda
        $pdf->Cell(700,85,$pdf->Image(asset('img.jpg'),30,12,170),0,0,'C');
        $pdf->Cell(100,12,"Presupuesto: ");
        $pdf->Cell(100,12,"Fecha: ". date('d/m/Y'));
        $pdf->Line(35,40,190,40);
        $pdf->Ln(7);
        $pdf->Cell(100,12,"Nombre : ");
$pdf->Line(35,48,190,48);
$pdf->Ln(7);
$pdf->Cell(100,12,"Domicilio: ");
$pdf->Line(35,56,190,56);
$pdf->Ln(7);
$pdf->Cell(90,12,"Teléfono: ");
$pdf->Line(35,62,190,62);
$pdf->Ln(7);
$pdf->Cell(100,12,"Equipo: ");
$pdf->Line(35,68,190,68);
$pdf->Ln(9);
$pdf->SetFont('Arial','B',10);

$pdf->Cell(60,12,'PRESUPUESTO');

$pdf->Ln(2);

$pdf->SetFont('Arial','',8);
$pdf->Output();
        $fichero='presupuesto-00.pdf';
        //$pdfdoc = $pdf->Output($fichero, "D");
        exit;
    }
}
