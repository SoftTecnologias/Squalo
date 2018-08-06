<?php

namespace App\Http\Controllers;

use App\Horarios;
use App\Maestro;
use App\Padre;
use App\Tipos;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Mockery\Exception;

class UserController extends Controller
{
    public function logout(){
        Cookie::forget('admin');
        return redirect()->route('index')->withCookie(Cookie::forget('admin'));
    }
    //Formularios
    public function doLogin(Request $request)
    {
        try {
            $cookie = null;
            $users = User::where('user', $request->username)->firstOrFail();
            if ($request->password == $users->password) {
                $datos = [
                    'username' => $users->user,
                ];

                    $cookie = Cookie::make('admin', $datos, 180);
                $respuesta = [
                    'code' => 200,
                    'msg' => $datos,
                    'detail' => 'success'
                ];
            } else {
                $respuesta = [
                    'code' => 500,
                    'msg' => "Las credenciales son incorrectas",
                    'detail' => 'error'
                ];
            }
        } catch (Exception $exception) {
            $respuesta = [
                'code' => 500,
                'msg' => $exception->getMessage(),
                'detail' => 'error'
            ];

        }
        if ($cookie != null)
            return Response::json($respuesta)->withCookie($cookie);
        else
            return Response::json($respuesta);
    }

   public function getIndex(Request $request){
       if ($request->cookie('admin') != null) {
           //Existe la cookie, solo falta averiguar que rol es
           $maestros = Maestro::all();
           foreach ($maestros as $maestro){
               $clases = DB::table('maestros as m')
                   ->select('*')
                   ->join('clase as c','c.idmaestro','=','m.id')
                   ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
                   ->join('fecha_clase as fc','fc.idclase','=','c.id')
                   ->join('grupo as g','g.idfecha','=','fc.id')
                   ->join('asistencia_maestros as am','g.id_asis_maestro','=','am.id')
                   ->where('fc.fecha','<',date('Y-m-j'))
                   ->where('m.id','=',$maestro->id)
                   ->distinct()
                   ->count();
               $asistencias = DB::table('maestros as m')
                   ->select('*')
                   ->join('clase as c','c.idmaestro','=','m.id')
                   ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
                   ->join('fecha_clase as fc','fc.idclase','=','c.id')
                   ->join('grupo as g','g.idfecha','=','fc.id')
                   ->join('asistencia_maestros as am','g.id_asis_maestro','=','am.id')
                   ->where('fc.fecha','<',date('Y-m-j'))
                   ->where('m.id','=',$maestro->id)
                   ->where('am.asistencia','=',1)
                   ->distinct()
                   ->count();
               $faltas = DB::table('maestros as m')
                   ->select('*')
                   ->join('clase as c','c.idmaestro','=','m.id')
                   ->join('tipo_clase as tc','tc.id','=','c.idtipo_clase')
                   ->join('fecha_clase as fc','fc.idclase','=','c.id')
                   ->join('grupo as g','g.idfecha','=','fc.id')
                   ->join('asistencia_maestros as am','g.id_asis_maestro','=','am.id')
                   ->where('fc.fecha','<',date('Y-m-j'))
                   ->where('m.id','=',$maestro->id)
                   ->where('am.asistencia','=',0)
                   ->distinct()
                   ->count();
               $maestro->setAttribute('totalClases',$clases);
               $maestro->setAttribute('totalFaltas',$faltas);
               $maestro->setAttribute('totalAsistencias',$asistencias);
           }

           $cookie = Cookie::get('admin');
           return view('index',['maestros'=>$maestros]);
       } else {
           //no existe una session de administrador y lo manda al login
           return view('login');
       }
   }
   public function  getMaestrosForm(Request $request){
       try {

           if ($request->cookie('admin') != null) {
               //Existe la cookie, solo falta averiguar que rol es
               $maestros = Maestro::all();
               $cookie = Cookie::get('admin');
               return view('administrador.maestros',["maestros"=>$maestros]);
           } else {
               //no existe una session de administrador y lo manda al login
               return view('login');
           }
       }catch (Exception $e){
           return $e;
       }
   }
   public function  getPadresForm(Request $request){
       try{
           if ($request->cookie('admin') != null) {
               //Existe la cookie, solo falta averiguar que rol es
               $cookie = Cookie::get('admin');
               return view('administrador.padresForm');
           } else {
               //no existe una session de administrador y lo manda al login
               return view('login');
           }
       }catch (Exception $e){
           return $e;
       }
   }
   public function  getAlumnosForm(Request $request){
       try{
           if ($request->cookie('admin') != null) {
               //Existe la cookie, solo falta averiguar que rol es
               $cookie = Cookie::get('admin');
               $padres = Padre::all();
               $tipos= Tipos::all();
               $maestros = Maestro::all();
               $horarios = Horarios::orderBy('Hora')->get();
               return view('administrador.alumnos',['padres' => $padres,'tipos' => $tipos,'maestros' => $maestros,
                   'horarios' => $horarios]);
           } else {
               //no existe una session de administrador y lo manda al login
               return view('login');
           }
       }catch (Exception $e){
           return $e;
       }
   }
   public function getTiposForm(Request $request){
       try{
           if ($request->cookie('admin') != null) {
               //Existe la cookie, solo falta averiguar que rol es
               $cookie = Cookie::get('admin');
               return view('administrador.tipoclase');
           } else {
               //no existe una session de administrador y lo manda al login
               return view('login');
           }
       }catch (Exception $e){
           return $e;
       }
   }
   public function getAsistenciasForm(Request $request){
       try{
           if ($request->cookie('admin') != null) {
               //Existe la cookie, solo falta averiguar que rol es

               $cookie = Cookie::get('admin');
               $maestros = Maestro::all();
               $tipos = DB::table('tipo_clase')
                   ->select('*')
                   ->where('tipo_clase','!=','I')
                   ->get();
               $horarios = Horarios::all();
               return view('administrador.asistencias',['maestros'=>$maestros,'horarios'=>$horarios,'tipos'=>$tipos]);
           } else {
               //no existe una session de administrador y lo manda al login
               return view('login');
           }
       }catch (Exception $e){
           return $e;
       }
   }
   public function getReemplazoForm(Request $request){
        try{
            if ($request->cookie('admin') != null) {
                //Existe la cookie, solo falta averiguar que rol es
                $cookie = Cookie::get('admin');
                return view('administrador.reemplazos');
            } else {
                //no existe una session de administrador y lo manda al login
                return view('login');
            }
        }catch (Exception $e){
            return $e;
        }
    }

    public  function  getClasesForm(Request $request){
        try{
            if ($request->cookie('admin') != null) {
                //Existe la cookie, solo falta averiguar que rol es
                $cookie = Cookie::get('admin');
                return view('administrador.clases');
            } else {
                //no existe una session de administrador y lo manda al login
                return view('login');
            }
        }catch (Exception $e){
            return $e;
        }
    }

    public function getPagosForm(Request $request){
        try{
            if ($request->cookie('admin') != null) {
                //Existe la cookie, solo falta averiguar que rol es
                $cookie = Cookie::get('admin');

                $maestros = Maestro::all();
                return view('administrador.pagos',['maestros'=>$maestros]);
            } else {
                //no existe una session de administrador y lo manda al login
                return view('login');
            }
        }catch (Exception $e){
            return $e;
        }
    }

    public function getPromoForm(Request $request){
        try{
            if ($request->cookie('admin') != null) {
                //Existe la cookie, solo falta averiguar que rol es
                $cookie = Cookie::get('admin');

                return view('administrador.promocion');
            } else {
                //no existe una session de administrador y lo manda al login
                return view('login');
            }
        }catch (Exception $e){
            return $e;
        }
    }

    public function getReporteSemanalForm(Request $request){
        try{
            if ($request->cookie('admin') != null) {
                //Existe la cookie, solo falta averiguar que rol es
                $cookie = Cookie::get('admin');
                $maestros = Maestro::all();
                $horarios = Horarios::orderBy('Hora')->get();
                return view('administrador.semanal',["maestros" => $maestros,"horarios"=>$horarios]);
            } else {
                //no existe una session de administrador y lo manda al login
                return view('login');
            }
        }catch (Exception $e){
            return $e;
        }
    }

    public function getHorario(Request $request){
        try{
            if ($request->cookie('admin') != null) {
                //Existe la cookie, solo falta averiguar que rol es
                $cookie = Cookie::get('admin');
                $maestros = Maestro::all();
                $horarios = Horarios::orderBy('Hora')->get();
                return view('administrador.horarios',["maestros" => $maestros,"horarios"=>$horarios]);
            } else {
                //no existe una session de administrador y lo manda al login
                return view('login');
            }
        }catch (Exception $e){
            return $e;
        }
    }
}
