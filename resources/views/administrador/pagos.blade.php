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
    <script src="{{ asset('/js/admin/pagos.js') }}" type="text/javascript"></script>

@endsection
@section('content')
    <div class="col-md-10">
            <hr>
            <div class="row"><h3>Pagos</h3>
                <hr style="border-color:lightgray; width: 90%"></div>
            <br>
            <hr>

        <form id="formPago">
            <div class="col-md-12" align="right"><a href="" id="exportall" class="btn btn-primary">Exportar Todos</a></div>
            <div class="form-group col-md-4">
                <label for="maestro" class="label-control">Maestro</label>
                <select name="maestro" id="maestro" class="selectpicker" data-live-search="true">
                    <option value="00">Seleccione un Maestro</option>
                    @foreach($maestros as $maestro)
                        <option value={{$maestro->id}}>{{$maestro->nombre.' '.$maestro->ape_paterno.' '.$maestro->ape_materno}}</option>
                        @endforeach
                </select>
            </div>
                <div class="col-md-4">
                    <div class="col-md-12" id="fechas">
                        <div class='col-sm-12'>
                            <div class='form-group'>
                                <div class='input-group date' id='datepicker'>
                                    <input type='text' class='form-control' placeholder="Selecciona un rango" id='alldates' name='alldates' />
                                    <span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span>
                                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="col-md-1">
                <div align="" class="">
                    <a class="btn btn-primary" href="#" id="consultar">Consultar <i class="fa fa-search"></i></a>
                </div>
            </div>

                <hr style="border-color:lightgray; width: 90%">
            <div class="col-md-1"></div>
            <div class="col-md-3 form-group">
                  <label for="hrsIndividual" class="label-cotrol">Clases Individuales</label>
                  <input type="text" id="hrsIndividual" name="hrsIndividual" placeholder="Clases Individuales" class="form-control" readonly>
            </div>
                <div class="col-md-3 form-group">
                    <label for="pagoIndividual" class="label-cotrol">Pago por Clase</label>
                    <input type="text" id="pagoIndividual" name="pagoIndividual" placeholder="Pago por Clase" class="form-control" readonly>
                </div>

                <div class="col-md-3 form-group">
                    <label for="totalIndividual" class="label-cotrol">Total Individuales</label>
                    <input type="text" id="totalIndividual" name="totalIndividual" placeholder="Total" class="form-control" readonly>
                </div>

                <div class="col-md-12">
                    <br></div>
            <div class="col-md-1"></div>
                <div class="col-md-3 form-group">
                    <label for="hrsIndividual" class="label-cotrol">Clases Grupales</label>
                    <input type="text" id="hrsGrupal" name="hrsGrupal" placeholder="Clases Individuales" class="form-control" readonly>
                </div>
                <div class="col-md-3 form-group">
                    <label for="pagoIndividual" class="label-cotrol">Pago por Clase</label>
                    <input type="text" id="pagoGrupal" name="pagoGrupal" placeholder="Pago por Clase" class="form-control" readonly>
                </div>
                <div class="col-md-3 form-group">
                    <label for="totalIndividual" class="label-cotrol">Total Grupal</label>
                    <input type="text" id="totalGrupal" name="totalGrupal" placeholder="Total" class="form-control" readonly>
                </div>

                <div class="col-md-12">
                    <br></div>
            <div class="col-md-1"></div>
                <div class="col-md-3 form-group">
                    <label for="hrsIndividual" class="label-cotrol">Clases Especiales</label>
                    <input type="text" id="hrsEspecial" name="totalEspecial" placeholder="Clases Individuales" class="form-control" readonly>
                </div>
                <div class="col-md-3 form-group">
                    <label for="pagoIndividual" class="label-cotrol">Pago por Clase</label>
                    <input type="text" id="pagoEspecial" name="totalEspecial" placeholder="Pago por Clase" class="form-control" readonly>
                </div>
                <div class="col-md-3 form-group">
                    <label for="totalIndividual" class="label-cotrol">Total Especial</label>
                    <input type="text" id="totalEspecial" name="totalEspecial" placeholder="Total" class="form-control" readonly>
                </div>

                <div class="col-md-12">
                    <br></div>
            <div class="col-md-7"></div>
                <div class="col-md-3">
                    <label for="totalPago" class="label-cotrol">Pago Total</label>
                    <input type="text" id="totalPago" name="totalPago" placeholder="Pago Total" class="form-control" readonly>
                </div>
                <div class="col-md-12">
                    <hr></div>
            <input type="hidden" id="datos">

            <div class="col-md-10" align="right"><a id="export" class="btn btn-primary">exportar</a></div>


            <br>

        </form>

    </div>


    <footer class="text-right"><h6>This Bootstrap 3 dashboard layout is compliments of <a href="http://www.bootply.com/85850"><strong>Bootply.com</strong></a></h6></footer>
@endsection