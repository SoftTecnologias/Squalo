@extends('layouts.administracion')
@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link media="all" type="text/css" rel="stylesheet"
          href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{asset('/css/index.css')}}">
@endsection
@section('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/fileinput.min.js')}}"></script>
    <script src="{{ asset('/js/index.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/admin/padres.js') }}" type="text/javascript"></script>

@endsection
@section('content')
    <div class="col-md-10">
        <hr>
        <div class="row"><h3>Padres</h3>
            <hr style="border-color:lightgray; width: 90%"></div>
        <div align="right" class="">
            <button class="btn btn-success" id="agregarpadre">Agregar <i class="fa fa-user-plus"></i></button>
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
                        <table id="tablaPadres" class="table table-bordered table-hover dataTable table-responsive"
                               role="grid" aria-describedby="User_info">
                            <thead>
                            <tr role="row">
                                <th class="sorting_asc" tabindex="0" aria-controls="userTable" rowspan="1"
                                    colspan="1" aria-label="Nombre: Nombre del usuario">
                                    Nombre
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="userTable" rowspan="1"
                                    colspan="1" aria-label="Apellido Paterno: apellido paterno del usuario">
                                    Apellidos
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="userTable" rowspan="1"
                                    colspan="1" aria-label="Apellido Materno: apellido materno del usuario">
                                    Telefono
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="userTable" rowspan="1"
                                    colspan="1" aria-label="Email: Correo del usuario">
                                    Correo
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="userTable" rowspan="1"
                                    colspan="1" aria-label="rol de permisos: permisos del usuario">
                                    Direccion
                                </th>
                                <th class="sorting col-sm-3" tabindex="0" aria-controls="userTable"
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
    <div class="modal" id="modalPadres">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="reset()"><i class="fa fa-times"></i></button>
                    <h3 id="titulo-modal">Padres</h3>
                </div>
                <div class="model-body">
                    <form class="form-horizontal" enctype="multipart/form-data" id="padresForm">
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
                                <label class="col-md-4 control-label" for="colonia">Colonia:</label>
                                <div class="col-md-5">
                                    <input id="colonia" name="colonia" placeholder="" class="form-control input-md" type="text">
                                </div>
                            </div>

                            <!-- Text input password-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="calle" >Calle:</label>
                                <div class="col-md-5">
                                    <input id="calle" name="calle" placeholder="" class="form-control input-md"  type="text">
                                </div>
                            </div>
                            <!-- Text input Nueva password-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="numero" >Numero:</label>
                                <div class="col-md-5">
                                    <input id="numero" name="numero" placeholder="" class="form-control input-md"  type="text">
                                </div>
                            </div>
                            <!-- Text input confirmar password-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="tel">Telefono Fijo:</label>
                                <div class="col-md-5">
                                    <input id="tel" name="tel" placeholder="" class="form-control input-md" type="text">
                                </div>
                            </div>

                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="phone">Telefono Celular:</label>
                                <div class="col-md-5">
                                    <input id="phone" name="phone" placeholder=""
                                           class="form-control input-md" type="text">
                                </div>
                            </div>
                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="fecha">Fecha de Nacimiento:</label>
                                <div class="col-md-5">
                                    <input id="fecha" name="fecha" placeholder="dd/mm/aaaa" class="form-control input-md"
                                           type="text">
                                </div>
                            </div>
                            <!-- Select Basic -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="email">Email:</label>
                                <div class="col-md-5">
                                    <input id="email" name="email" placeholder="" class="form-control input-md"
                                           type="text">
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btnPadre" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <footer class="text-right"><h6>This Bootstrap 3 dashboard layout is compliments of <a href="http://www.bootply.com/85850"><strong>Bootply.com</strong></a></h6></footer>
@endsection