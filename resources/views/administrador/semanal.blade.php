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
    <script src="{{ asset('/js/index.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/admin/semanal.js') }}" type="text/javascript"></script>
@endsection
@section('content')
    <div class="col-md-10">
        <hr>
        <div class="row"><h3>Reporte de Semanal</h3>
            <hr style="border-color:lightgray; width: 90%"></div>
        <br>
        <div class="box-body">
            <div id="user_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                </div>

                <select name="maestro" id="maestro" class="selectpicker" data-live-search="true">
                    <option value="00">Seleccione un Maestro</option>
                    @foreach($maestros as $maestro)
                        <option value={{$maestro->id}}>{{$maestro->nombre.' '.$maestro->ape_paterno.' '.$maestro->ape_materno}}</option>
                    @endforeach
                </select>

                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-striped">
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
    <footer class="text-right"><h6>This Bootstrap 3 dashboard layout is compliments of <a href="http://www.bootply.com/85850"><strong>Bootply.com</strong></a></h6></footer>
@endsection