@extends('layouts.administracion')
@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link media="all" type="text/css" rel="stylesheet"
          href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{asset('/css/index.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap-datepicker.css')}}"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/fileinput.min.js')}}"></script>
    <script src="{{asset('/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{ asset('/js/index.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/admin/asistencias.js') }}" type="text/javascript"></script>

@endsection
@section('content')
    <div class="col-md-10">
        <hr>
        <div class="row"><h3>Grupos y Asistencias</h3>
            <hr style="border-color:lightgray; width: 90%"></div>
        <div align="right" class="">
            <button class="btn btn-success" id="agregarClaseGrupal">Agregar clase grupal</button>
        </div>
        <br>
        <div class="box-body">
            <div id="user_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table id="tablaAsistencias" class="table table-bordered table-hover dataTable table-responsive"
                               role="grid" aria-describedby="User_info">
                            <thead>
                            <tr role="row" class="active">
                                <th class="sorting_asc" tabindex="0" aria-controls="userTable" rowspan="1"
                                    colspan="1" aria-label="Nombre: Nombre del usuario">
                                    Clase
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="userTable" rowspan="1"
                                    colspan="1" aria-label="Apellido Paterno: apellido paterno del usuario">
                                    Fecha
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="userTable" rowspan="1"
                                    colspan="1" aria-label="Apellido Materno: apellido materno del usuario">
                                    Horario
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="userTable" rowspan="1"
                                    colspan="1" aria-label="Email: Correo del usuario">
                                    Maestro
                                </th>
                                <th class="sorting col-sm-3" tabindex="0" aria-controls="userTable"
                                    rowspan="1" colspan="1" aria-sort="ascending"
                                    aria-label="Acciones">
                                    Acciones
                                </th>
                            </tr >
                            </thead>
                            <tbody id="bodyasis">

                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="text-right"><h6>This Bootstrap 3 dashboard layout is compliments of <a href="http://www.bootply.com/85850"><strong>Bootply.com</strong></a></h6></footer>

    <div class="modal" id="modalAsistencia">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick=""><i class="fa fa-times"></i></button>
                    <h3 id="titulo-modal">Alumnos</h3>
                </div>
                <div class="model-body">
                    <form class="form-horizontal" enctype="multipart/form-data" id="alumnosForm">
                        <fieldset>
                            <br>
                            {{csrf_field()}}
                            <input type="hidden" name="asistid" id="asistid">
                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="name">Maestro:</label>
                                <div class="col-md-5">
                                    <label for="name">Nombre</label>
                                    <input type="text" id="name" name="name" class="form-control" readonly>
                                    <input type="checkbox" id="asisMaestro"> Asistencia
                                    <div id="divremplazo" hidden>
                                        <select name="remplazo" id="remplazo" class="selectpicker">
                                            <option value="00">Selecciona a un Reemplazo</option>
                                            @foreach($maestros as $maestro)
                                                <option value="{{$maestro->id}}">{{$maestro->nombre.' '.$maestro->ape_paterno.' '.$maestro->ape_materno}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-md-12">
                                    <table id="asistenciasAlumnos" name="asistenciasAlumnos" class="table">
                                        <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Apellido Paterno</th>
                                            <th>Apellido Paterno</th>
                                            <th>Asistencia</th>
                                        </tr>
                                        </thead>
                                        <tbody id="ab">

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btnAlumno" class="btn btn-primary">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modalGrupal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick=""><i class="fa fa-times"></i></button>
                    <h3 id="titulo-modal">Nuevo Grupo</h3>
                </div>
                <div class="model-body">
                    <form class="form-horizontal" enctype="multipart/form-data" id="grupoForm">
                        <fieldset>
                            <br>
                            {{csrf_field()}}
                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="ape_pat">Tipo de Clase:</label>
                                <div class="col-md-5">
                                    <select name="tipoc" id="tipoc" class="selectpicker">
                                        <option value="00">Seleccione una Clase</option>
                                        @foreach($tipos as $tipo)
                                            <option value="{{$tipo->id}}" name="{{$tipo->numero_clases}}">{{$tipo->descripcion.' - '.$tipo->tipo_clase.' - $'.$tipo->costo.'. '.$tipo->numero_clases.' clases'}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="maestroc">Maestros:</label>
                                <div class="col-md-5">
                                    <select name="maestroc" id="maestroc" class="selectpicker">
                                        <option value="00">Seleccione un Maestro</option>
                                        @foreach($maestros as $maestro)
                                            <option value="{{$maestro->id}}">{{$maestro->nombre.' '.$maestro->ape_paterno.' '.$maestro->ape_materno}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="fecha_nac">Horario:</label>
                                <div class="col-md-5">
                                    <select name="horario" id="horario" class="selectpicker">
                                        <option value="00">Seleccione un Horario</option>
                                        @foreach($horarios as $horario)
                                            <option value="{{$horario->id}}">{{$horario->Hora}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Text input password-->

                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btnAgregarGrupo" class="btn btn-primary">Agregar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
