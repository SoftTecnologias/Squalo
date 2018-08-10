@extends('layouts.administracion')
@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link media="all" type="text/css" rel="stylesheet"
          href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/>
    <link media="all" type="text/css" rel="stylesheet"
          href="https://cdn.datatables.net/buttons/1.4.1/css/buttons.dataTables.min.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="{{asset('/css/index.css')}}">
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/fileinput.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/FileSaver.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/xlsx.core.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/tableExport.min.js')}}"></script>
    <script src="{{ asset('/js/index.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/admin/horarios.js') }}" type="text/javascript"></script>
@endsection
@section('content')
    <div class="col-md-10">
        <hr>
        <div class="row"><h3>Horario de Semanal</h3>
            <hr style="border-color:lightgray; width: 90%"></div>

        <div align="right" class="">
            <a id="" class="btn btn-success" data-toggle="modal" data-target="#myModal">Agregar Horario</a>
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
                        <table class="table table-striped" id="tablaH">
                            <thead>
                            <th>Horario</th>
                            <th>Lunes</th>
                            <th>Martes</th>
                            <th>Miercoles</th>
                            <th>Jueves</th>
                            <th>Viernes</th>
                            <th>Sabado</th>
                            </thead>
                            <tbody id="cuerpo">
                            @foreach($horarios as $horario)
                                <tr>
                                    <td>{{$horario->Hora}}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Nuevo Horario</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="col-md-6">
                            <label for="de">De:</label>
                            <input type="time" id="de" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="a">A:</label>
                            <input type="time" id="a" class="form-control">
                        </div>
                    </form>
                    <hr>
                    <hr>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" data-dismiss="modal" id="addHorario">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <div>
        <button class="btn btn-warning" id="export">exportar a Excel</button>
    </div>
@endsection