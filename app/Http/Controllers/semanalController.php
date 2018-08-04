<?php

namespace App\Http\Controllers;

use App\Horarios;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class semanalController extends Controller
{
    function getRepSemanal($id){
        date_default_timezone_set("America/Mexico_City");
        $hoy = date("Y-m-j");

        $dia = date("N");
        $lunes = "";
        switch ($dia){
            case 1:
                $lunes = $hoy;
                break;
            case 2:
                $nuevafecha = strtotime ( '-1 day' , strtotime ( $hoy ) ) ;
                $lunes = date ( 'Y-m-j' , $nuevafecha );
                break;
            case 3:
                $nuevafecha = strtotime ( '-2 day' , strtotime ( $hoy ) ) ;
                $lunes = date ( 'Y-m-j' , $nuevafecha );
                break;
            case 4:
                $nuevafecha = strtotime ( '-3 day' , strtotime ( $hoy ) ) ;
                $lunes = date ( 'Y-m-j' , $nuevafecha );
                break;
            case 5:
                $nuevafecha = strtotime ( '-4 day' , strtotime ( $hoy ) ) ;
                $lunes = date ( 'Y-m-j' , $nuevafecha );
                break;
            case 6:
                $nuevafecha = strtotime ( '-5 day' , strtotime ( $hoy ) ) ;
                $lunes = date ( 'Y-m-j' , $nuevafecha );
                break;
            case 7:
                $nuevafecha = strtotime ( '-6 day' , strtotime ( $hoy ) ) ;
                $lunes = date ( 'Y-m-j' , $nuevafecha );
                break;
        }
        $respuesta = [];

        $nuevafecha = strtotime ( '+1 day' , strtotime ( $hoy ) ) ;
        $martes = date ( 'Y-m-j' , $nuevafecha );
        $nuevafecha = strtotime ( '+2 day' , strtotime ( $hoy ) ) ;
        $miercoles = date ( 'Y-m-j' , $nuevafecha );
        $nuevafecha = strtotime ( '+3 day' , strtotime ( $hoy ) ) ;
        $jueves = date ( 'Y-m-j' , $nuevafecha );
        $nuevafecha = strtotime ( '+4 day' , strtotime ( $hoy ) ) ;
        $viernes = date ( 'Y-m-j' , $nuevafecha );
        $nuevafecha = strtotime ( '+5 day' , strtotime ( $hoy ) ) ;
        $sabado = date ( 'Y-m-j' , $nuevafecha );

        $l = $this->getHorarios($lunes,$id);
        $ma = $this->getHorarios($martes,$id);
        $mi = $this->getHorarios($miercoles,$id);
        $j = $this->getHorarios($jueves,$id);
        $v = $this->getHorarios($viernes,$id);
        $s = $this->getHorarios($sabado,$id);

        $lunes = [];
        $martes = [];
        $miercoles = [];
        $jueves = [];
        $viernes = [];
        $sabado = [];

        $horarios = Horarios::orderBy('Hora')->get();

        foreach ($horarios as $horario){
            $estado = false;
            foreach ($l as $item){
                if($horario->Hora == $item->Hora){
                    array_push($lunes,$item);
                    $estado = true;
                    break;
                }else{
                    $estado = false;
                }
            }
            if($estado == false){
                array_push($lunes,[
                    "Hora"=>$horario->Hora,
                    "nombre"=>'',
                    "descripcion"=>'',
                    "ape_paterno"=>'',
                    "ape_materno"=>'',
                ]);
            }
        }

        foreach ($horarios as $horario){
            $estado = false;
            foreach ($ma as $item){
                if($horario->Hora == $item->Hora){
                    array_push($martes,$item);
                    $estado = true;
                    break;
                }else{
                    $estado = false;
                }
            }
            if($estado == false){
                array_push($martes,[
                    "Hora"=>$horario->Hora,
                    "nombre"=>'',
                    "descripcion"=>'',
                    "ape_paterno"=>'',
                    "ape_materno"=>'',
                ]);
            }
        }

        foreach ($horarios as $horario){
            $estado = false;
            foreach ($mi as $item){
                if($horario->Hora == $item->Hora){
                    array_push($miercoles,$item);
                    $estado = true;
                    break;
                }else{
                    $estado = false;
                }
            }
            if($estado == false){
                array_push($miercoles,[
                    "Hora"=>$horario->Hora,
                    "nombre"=>'',
                    "descripcion"=>'',
                    "ape_paterno"=>'',
                    "ape_materno"=>'',
                ]);
            }
        }

        foreach ($horarios as $horario){
            $estado = false;
            foreach ($j as $item){
                if($horario->Hora == $item->Hora){
                    array_push($jueves,$item);
                    $estado = true;
                    break;
                }else{
                    $estado = false;
                }
            }
            if($estado == false){
                array_push($jueves,[
                    "Hora"=>$horario->Hora,
                    "nombre"=>'',
                    "descripcion"=>'',
                    "ape_paterno"=>'',
                    "ape_materno"=>'',
                ]);
            }
        }

        foreach ($horarios as $horario){
            $estado = false;
            foreach ($v as $item){
                if($horario->Hora == $item->Hora){
                    array_push($viernes,$item);
                    $estado = true;
                    break;
                }else{
                    $estado = false;
                }
            }
            if($estado == false){
                array_push($viernes,[
                    "Hora"=>$horario->Hora,
                    "nombre"=>'',
                    "descripcion"=>'',
                    "ape_paterno"=>'',
                    "ape_materno"=>'',
                ]);
            }
        }

        foreach ($horarios as $horario){
            $estado = false;
            foreach ($s as $item){
                if($horario->Hora == $item->Hora){
                    array_push($sabado,$item);
                    $estado = true;
                    break;
                }else{
                    $estado = false;
                }
            }
            if($estado == false){
                array_push($sabado,[
                    "Hora"=>$horario->Hora,
                    "nombre"=>'',
                    "descripcion"=>'',
                    "ape_paterno"=>'',
                    "ape_materno"=>'',
                ]);
            }
        }

        return Response::json([
            "lunes"=>$lunes,
            "martes"=>$martes,
            "miercoles"=>$miercoles,
            'jueves'=>$jueves,
            'viernes'=>$viernes,
            'sabado'=> $sabado
        ]);
    }

    function getHorarios($dia,$id){
        $res = DB::table('grupo as g')
            ->select('h.Hora','a.nombre','a.ape_paterno','a.ape_materno','tc.descripcion')
            ->join('fecha_clase as fc','fc.id','=','g.idfecha')
            ->join('clase as c','c.id','=','fc.idclase')
            ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
            ->join('maestros as m','m.id','=','c.idmaestro')
            ->join('horarios as h','h.id','=','c.idhorario')
            ->join('grupo_alumnos as ga','g.id','=','ga.idGrupo')
            ->join('alumnos as a','a.id','=','ga.idAlumno')
            ->distinct()
            ->where('fc.fecha','=',$dia)
            ->where('m.id','=',$id)
            ->orderBy('h.Hora')
            ->get();
        return $res;
    }
}
