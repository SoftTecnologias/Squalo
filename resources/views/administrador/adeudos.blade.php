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
    <script type="text/javascript" src="{{asset('js/FileSaver.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/xlsx.core.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/tableExport.min.js')}}"></script>
    <script src="{{ asset('/js/index.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/admin/adeudo.js') }}" type="text/javascript"></script>

@endsection
@section('content')
    <div class="col-md-10">
        <hr>
        <div class="row"><h3>Adeudos</h3>
            <hr style="border-color:lightgray; width: 90%"></div>
        <br>
        <hr>

        <form id="formPago">
            <!--div class="col-md-12" align="right"><a href="" id="exportall" class="btn btn-primary">Exportar Todos</a></div-->

            <hr style="border-color:lightgray; width: 90%">

            <table class="table table-striped" id="tableadeudos">
                <thead class="bg-primary">
                <tr>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Adeudo</th>
                    <th>Fecha Limite</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($alumnos as $alumno)
                        <tr class="{{$alumno->color}}">
                            <td>{{$alumno->nombre}}</td>
                            <td>{{$alumno->ape_paterno}}</td>
                            <td>{{$alumno->ape_materno}}</td>
                            <td>{{$alumno->adeudo}}</td>
                            <td>{{$alumno->limite}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <input type="hidden" id="datos">

            <!--div class="col-md-10" align="right"><a id="export" class="btn btn-primary">exportar</a></div-->


            <br>

        </form>

    </div>
    <div>
        <button class="btn btn-warning" id="export">exportar a Excel</button>
    </div>

@endsection