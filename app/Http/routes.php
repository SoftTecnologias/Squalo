<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//area de rutas para el administrador

Route::post('/send', [
    'as' => 'index.send',
    'uses' => 'MailController@send'
]);

Route::get('contact', [
    'as' => 'contact',
    'uses' => 'MailController@index'
]);

Route::post('/login',[
   'uses' => 'UserController@doLogin',
    'as' => 'login'
]);

Route::get('/promociones',[
    'uses' => 'UserController@getPromoForm',
    'as' => 'index.promo'
]);

Route::post('/addhorario',[
   'uses'=> 'TiposController@addHorario'
]);

Route::get('/resource/maestros/admpago/{id}',[
   'uses' => 'MaestrosController@admpago'
]);

Route::post('/resource/maestros/admpago',[
    'uses' => 'MaestrosController@actualizarPagos'
]);

Route::get('/logout',[
   'uses' => 'UserController@logout',
    'as' => 'logout'
]);

Route::get('/',[
    'uses' => 'UserController@getIndex',
    'as' => 'index'
]);

Route::get('/horario',[
    'uses' => 'UserController@getHorario',
    'as' => 'index.horario'
]);

Route::get('/maestros',[
    'uses' => 'UserController@getMaestrosForm',
    'as' => 'index.maestros'
]);

Route::get('/padres',[
    'uses' => 'UserController@getPadresForm',
    'as' => 'index.padres'
]);

Route::get('/alumnos',[
    'uses' => 'UserController@getAlumnosForm',
    'as' => 'index.alumnos'
]);

Route::get('/tipoclase',[
    'uses' => 'UserController@getTiposForm',
    'as' => 'index.tipos'
]);
Route::get('/asistencias',[
   'uses' => 'UserController@getAsistenciasForm',
    'as' => 'index.asistencias'
]);

Route::get('/reemplazos',[
    'uses' => 'UserController@getReemplazoForm',
    'as' => 'index.reemplazo'
]);

Route::get('/clases',[
   'uses' => 'UserController@getClasesForm',
    'as' => 'index.clases'
]);


Route::get('/semanal',[
    'uses' => 'UserController@getReporteSemanalForm',
    'as' => 'index.semanal'
]);


Route::post('/alumnos/{id}/asignar',[
    'uses' => 'AlumnosController@asignar'
]);

Route::post('/alumnos/{id}/asignargrupo',[
    'uses' => 'AlumnosController@asignargrupo'
]);

Route::get('/alumnos/{id}/getfechas',[
   'uses' => 'AlumnosController@fechasClases'
]);

Route::get('/pagos',[
   'uses' => 'UserController@getPagosForm',
    'as' => 'index.pagos'
]);


Route::post('/pagos/maestro',[
    'uses' => 'PagosController@getInfo',
]);
//area de resources
Route::resource('/resource/maestros','MaestrosController');
Route::post('/resource/maestros/{id}',[
    'uses' => 'MaestrosController@update'
]);

Route::resource('/resource/padres','PadresController');
Route::post('/resource/padres/{id}',[
    'uses' => 'PadresController@update'
]);

Route::resource('/resource/alumnos','AlumnosController');

Route::get('/alumnos/all',[
    'uses' => 'AlumnosController@todos',
    'as' => 'algo.l'
]);

Route::get('/alumno/baja/{id}',[
   'uses' => 'AlumnosController@bajaAlumno'
]);

Route::get('/alumno/alta/{id}',[
    'uses' => 'AlumnosController@altaAlumno'
]);

Route::get('/alumnos/inactivos',[
        'uses' => 'AlumnosController@inactivos']
);

Route::get('/alumnos/activos',[
        'uses' => 'AlumnosController@activos']
);

Route::post('/resource/alumnos/abono/{id}',[
    'uses' => 'AlumnosController@abonar'
]);

Route::post('/resource/alumnos/abono/cancel/{id}',[
   'uses' =>  'AlumnosController@cancelAbono'
]);

Route::post('/resource/alumnos/horarios',[
    'uses' => 'AlumnosController@getHorarios'
]);

Route::get('/resource/alumnos/grupo/{idtipogrupo}',[
   'uses' => 'AlumnosController@getGruposDisponibles'
]);

Route::get('/resource/alumnos/clase/sel/{idtipoclase}',[
    'uses' => 'AlumnosController@infoGrupo'
]);

Route::resource('/resource/tipoclase','TiposController');

Route::post('/resource/tipoclase/{id}',[
    'uses' => 'TiposController@update'
]);


Route::resource('/resource/asistencias','AsistenciasController');

Route::post('/resource/asistencias/maestro/{id}',[
    'uses' => 'AsistenciasController@asistenciaMaestro'
]);

Route::post('/resource/asistencias/alumno/{id}',[
    'uses' => 'AsistenciasController@asistenciaAlumno'
]);

Route::post('/resource/asistencias/remplazo/{id}',[
    'uses' => 'AsistenciasController@remplazo'
]);

Route::get('/resource/asistenciaalumnos/{id}',[
    'uses' => 'AsistenciasController@AlumnosClase'
]);

Route::get('/resource/pagos/{id}',[
    'uses' => 'AlumnosController@getPagos'
]);

Route::resource('/resource/reemplazo','ReemplazosController');

Route::resource('/resource/clases','ClasesController');

Route::get('exportpdf/{id}', [
    'uses' => 'PagosController@exportpdf',
    'as' => 'pdf.export'
]);

Route::get('exportallpdf/{id}', [
    'uses' => 'PagosController@exportallpdf',
    'as' => 'pdf.exportall'
]);

Route::get('/getrepsemanal/{id}', [
    'uses' => 'semanalController@getRepSemanal'
]);

Route::get('/gethorariosemanal', [
    'uses' => 'semanalController@getCalSemanal'
]);
