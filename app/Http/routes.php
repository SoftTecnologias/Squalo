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
Route::get('/',[
    'uses' => 'UserController@getIndex',
    'as' => 'index'
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

Route::post('/resource/alumnos/abono/{id}',[
    'uses' => 'AlumnosController@abonar'
]);

Route::post('/resource/alumnos/abono/cancel/{id}',[
   'uses' =>  'AlumnosController@cancelAbono'
]);

Route::post('/resource/alumnos/horarios',[
    'uses' => 'AlumnosController@getHorarios'
]);


Route::resource('/resource/tipoclase','TiposController');

Route::post('/resource/tipoclase/{id}',[
    'uses' => 'TiposController@update'
]);

Route::post('/alumnos/{id}/asignar',[
  'uses' => 'AlumnosController@asignar'
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