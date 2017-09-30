@extends('layouts.administracion')
@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link media="all" type="text/css" rel="stylesheet"
          href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('/css/fileinput.css')}}" media="all" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('/css/index.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap-datepicker.css')}}"/>
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="{{asset('/js/fileinput.js')}}" type="text/javascript"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/fileinput.min.js')}}"></script>
    <script src="{{asset('/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{ asset('/js/index.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/admin/promocion.js') }}" type="text/javascript"></script>

@endsection
@section('content')
    <div class="col-md-10 col-lg-10">
        <hr>
        <div class="row"><h3>Promociones</h3>
            <hr style="border-color:lightgray; width: 90%"></div>
        <br>
        <hr>

        <form id="formPromocion" >
            {{csrf_field()}}
            <div class="col-md-1"></div>
            <div class="col-md-11 form-group">
                <label for="totalIndividual" class="label-cotrol">Titulo del Mensaje</label>
                <input type="text" id="titulo" name="titulo" placeholder="Titulo del mensaje" class="form-control">
            </div>

            <div class="col-md-1"></div>
            <div class="col-md-11 form-group">
                <label for="totalIndividual" class="label-cotrol">Cuerpo del Mensaje</label>
                <textarea class="form-control" id="msjcuerpo" name="msjcuerpo"  cols="30" rows="10"></textarea>
            </div>
            <hr>
            <div class="col-md-1"></div>
            <div class="form-group col-md-11">
                <input id="file" name="file" type="file" class="file" multiple=false data-preview-file-type="any">
            </div>
            <div class="col-md-12"></div>
        </form>
        <div class="col-md-12" align="right">
            <hr>
            <hr>
            <a href="#" id="enviar" class="btn btn-primary">Enviar</a>
            <hr>
            <hr>
            <hr>
            <hr>
            <hr>
            <hr>
            <hr>
        </div>
    </div>
    <footer class="text-right"><h6>This Bootstrap 3 dashboard layout is compliments of <a href="http://www.bootply.com/85850"><strong>Bootply.com</strong></a></h6></footer>
@endsection