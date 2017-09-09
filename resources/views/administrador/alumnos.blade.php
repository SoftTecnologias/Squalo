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
    <script src="{{ asset('/js/admin/alumnos.js') }}" type="text/javascript"></script>

@endsection
@section('content')
    <div class="col-md-10">
        <hr>
        <div class="row"><h3>Alumnos</h3>
            <hr style="border-color:lightgray; width: 90%"></div>
        <div align="right" class="">
            <button class="btn btn-success" id="agregarAlumno">Agregar <i class="fa fa-user-plus"></i></button>
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
                        <input type="checkbox" id="chactivos" checked>Activos
                        <input type="checkbox" id="chinactivos" >InActivos
                        <table id="tablaAlumnos" class="table table-bordered table-hover dataTable table-responsive"
                               role="grid" aria-describedby="User_info">
                            <thead>
                            <tr role="row" class="active">
                                <th class="sorting_asc" aria-controls="userTable" rowspan="1"
                                    colspan="1" aria-label="Nombre: Nombre del usuario">
                                    Nombre
                                </th>
                                <th class="sorting" aria-controls="userTable" rowspan="1"
                                    colspan="1" aria-label="Apellido Paterno: apellido paterno del usuario">
                                    Apellidos
                                </th>
                                <th class="sorting"  aria-controls="userTable" rowspan="1"
                                    colspan="1" aria-label="Apellido Materno: apellido materno del usuario">
                                    Fecha de nacimiento
                                </th>
                                <th class="sorting" aria-controls="userTable" rowspan="1"
                                    colspan="1" aria-label="Email: Correo del usuario">
                                    Padre
                                </th>
                                <th class="sorting" aria-controls="userTable" rowspan="1"
                                    colspan="1" aria-label="rol de permisos: permisos del usuario">
                                    Adeudo
                                </th>
                                <th class="sorting col-sm-3" aria-controls="userTable"
                                    rowspan="1" colspan="1" aria-sort="ascending"
                                    aria-label="Acciones">
                                    Acciones
                                </th>
                            </tr >
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="text-center">This Bootstrap 3 dashboard layout is compliments of <a href="http://www.bootply.com/85850"><strong>Bootply.com</strong></a></footer>

    <div class="modal" id="modalAlumno">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="reset()"><i class="fa fa-times"></i></button>
                    <h3 id="titulo-modal">Alumnos</h3>
                </div>
                <div class="model-body">
                    <form class="form-horizontal" enctype="multipart/form-data" id="alumnosForm">
                        <fieldset>
                            <br>
                            {{csrf_field()}}
                            <input type="hidden" name="padreid" id="padreid">
                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="name">Nombre:</label>
                                <div class="col-md-5">
                                    <input id="name" name="name" placeholder="" class="form-control input-md" required=""
                                           type="text">
                                </div>
                            </div>

                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="ape_pat">Apellido Paterno:</label>
                                <div class="col-md-5">
                                    <input id="ape_pat" name="ape_pat" placeholder="" class="form-control input-md"
                                           required="" type="text">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="ape_mat">Apellido Materno:</label>
                                <div class="col-md-5">
                                    <input id="ape_mat" name="ape_mat" placeholder="" class="form-control input-md"
                                           required="" type="text">
                                </div>
                            </div>

                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="fecha_nac">Fecha de Nacimiento:</label>
                                <div class="col-md-5">
                                    <input id="fecha_nac" name="fecha_nac" placeholder="" class="form-control input-md" type="text">
                                </div>
                            </div>

                            <!-- Text input password-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="padre" >Padre:</label>
                                <div class="col-md-5">
                                    <select name="padre" id="padre" class="selectpicker" data-live-search="true">
                                        <option value="00">Selecciona un padre</option>
                                            @foreach($padres as $padre)
                                                <option value="{{$padre->id}}">{{$padre->nombre.' '.$padre->ape_paterno.' '.$padre->ape_materno}}</option>
                                                @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btnAlumno" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modalAsignar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="reset()"><i class="fa fa-times"></i></button>
                    <h3 id="titulo-modal">Asignar</h3>
                </div>
                <div class="model-body">
                    <form class="form-horizontal" enctype="multipart/form-data" id="asignarForm">
                        <fieldset>
                            <br>
                            {{csrf_field()}}
                            <input type="hidden" name="idasignar" id="idasignar">
                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="name">Nombre:</label>
                                <div class="col-md-5">
                                    <input id="nameasignar" name="nameasignar" placeholder="" class="form-control input-md" required=""
                                           type="text" readonly>
                                </div>
                            </div>

                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="ape_pat">Tipo de Clase:</label>
                                <div class="col-md-5">
                                    <select name="tipoc" id="tipoc" class="selectpicker">
                                        <option value="00">Seleccione una Clase</option>
                                        @foreach($tipos as $tipo)
                                            <option value="{{$tipo->id}}" name="{{$tipo->numero_clases.'-'.$tipo->tipo_clase}}">{{$tipo->descripcion.' - '.$tipo->tipo_clase.' - $'.$tipo->costo.'. '.$tipo->numero_clases.' clases'}}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" id="grupos" hidden>
                                <label class="col-md-4 control-label" for="ape_pat">Grupos Disponibles:</label>
                                <div class="col-md-5">
                                    <select name="gruposdisp" id="gruposdisp" class="selectpicker">


                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="ape_mat">Maestros:</label>
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
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="padre" >Fechas:</label>
                                <div class="col-md-5" id="fechas">
                                    <div class='col-sm-6'>
                                        <div class='form-group'>
                                            <div class='input-group date' id='datepicker'>
                                                <input type='text' class='form-control' id='alldates' name='alldates'/>
                                                <span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btnAlumnoAsignar" class="btn btn-primary">Asignar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modalInfoAbono">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick=""><i class="fa fa-times"></i></button>
                    <h3 id="titulo-modal">Informacion de Abonos</h3>
                </div>
                <div class="model-body">
                    <form class="form-horizontal" enctype="multipart/form-data" id="abonarForm">
                        <fieldset>
                            <br>
                            {{csrf_field()}}
                            <input type="hidden" name="idabono" id="idabono">
                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="name">Alumno:</label>
                                <div class="col-md-5">
                                    <input id="nameabono" name="nameabono" placeholder="" class="form-control input-md" required=""
                                           type="text" readonly>
                                </div>
                                <a class="btn btn-success" id="abonar">Abonar</a>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <table id="tablainfoabono" class="table">
                                        <thead >
                                        <tr class="active">
                                            <th>Abono</th>
                                            <th>Fecha</th>
                                            <th>Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody id="abonobody">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btnAlumnoAsignar" class="btn btn-primary">Asignar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
