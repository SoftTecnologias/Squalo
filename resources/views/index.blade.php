@extends('layouts.administracion')
@section('content')

        <div class="col-md-10">

            <!-- column 2 -->

            <hr>

            <div class="row">



                <!-- center left-->
                <div class="col-md-6">
                    <div class="btn-group btn-group-justified">
                        <a href="{{route('index.maestros')}}" class="btn btn-primary col-sm-3">
                            <i class="glyphicon glyphicon-user"></i><br>
                            Maestros
                        </a>
                        <a href="{{route('index.padres')}}" class="btn btn-primary col-sm-3">
                            <i class="glyphicon glyphicon-user"></i><br>
                            Padres
                        </a>
                        <a href="{{route('index.alumnos')}}" class="btn btn-primary col-sm-3">
                            <i class="fa fa-users"></i><br>
                            Alumnos
                        </a>
                        <a href="{{route('index.asistencias')}}" class="btn btn-primary col-sm-3">
                            <i class="fa fa-check"></i><br>
                            Asistencias
                        </a>
                    </div>

                    <hr>

                    <div class="panel panel-default">
                        <div class="panel-heading"><h4>Reporte de Asistencias Totales</h4></div>
                        <div class="panel-body">
                        @foreach($maestros as $maestro)
                            <?php
                                    if($maestro->totalClases == 0){
                                        $m = 100;
                                    }else{
                                    $m = ($maestro->totalAsistencias)/($maestro->totalClases)*100;
                                    }
                                    if($m>=80){
                                    $color = "progress-bar-success";
                                    }elseif($m>=50){
                                        $color = "progress-bar-warning";
                                    }else{
                                        $color = "progress-bar-danger";
                                    }
                                    ?>
                            <small>{{$maestro->nombre.' '.$maestro->ape_paterno.' '.$maestro->ape_materno}}</small>
                            <div class="progress">

                                <div class="progress-bar {{$color}}" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: {{$m}}%">
                                    <span class="sr-only"> Complete</span>
                                </div>
                            </div>
                            @endforeach

                        </div><!--/panel-body-->
                    </div><!--/panel-->

                    <hr>


                </div><!--/col-->
                <div class="col-md-6">
                    <table class="table table-striped">
                        <thead>
                        <tr><th>Maestro</th><th>Clases</th><th>Faltas</th><th>Asitencias</th></tr>
                        </thead>
                        <tbody>
                        @foreach($maestros as $maestro)
                        <tr>
                            <td>{{$maestro->nombre.' '.$maestro->ape_paterno.' '.$maestro->ape_materno}}</td>
                            <td>{{$maestro->totalClases}}</td>
                            <td>{{$maestro->totalFaltas}}</td>
                            <td>{{$maestro->totalAsistencias}}</td>
                        </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div><!--/col-span-6-->

            </div><!--/row-->

            <hr>
            <hr>
            <hr>
            <hr>
            <hr>
            <hr>
            <hr>

        </div><!--/col-span-9-->


<footer class="text-right"><h6>This Bootstrap 3 dashboard layout is compliments of <a href="http://www.bootply.com/85850"><strong>Bootply.com</strong></a></h6></footer>

<div class="modal" id="addWidgetModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add Widget</h4>
            </div>
            <div class="modal-body">
                <p>Add a widget stuff here..</p>
            </div>
            <div class="modal-footer">
                <a href="#" data-dismiss="modal" class="btn">Close</a>
                <a href="#" class="btn btn-primary">Save changes</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dalog -->
</div><!-- /.modal -->
@endsection
@section('styles')
    <link rel="stylesheet" href="{{asset('/css/index.css')}}">
@endsection
@section('scripts')
    <script src="{{ asset('/js/index.js') }}" type="text/javascript"></script>
@endsection



