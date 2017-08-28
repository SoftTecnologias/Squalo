$(function() {
    limpiarSeleccion();
    $('#tipoc').on('change',function () {
        numero = $(this).find('option:selected').attr('name');
    });
    $('#datepicker').datepicker({
        format: 'dd/mm/yyyy',
        startDate: '0d',
        autoclose: false,
        multidate:true
    });
    $('#btnAlumnoAsignar').on('click',function () {
        console.log($('#datepicker :input').val());
    });
    $('#agregarAlumno').on('click',function () {
        $('#titulo-modal').text('Nuevo Alumno');
        $('#modalAlumno').modal('show');
    });
    $('#btnAlumno').on('click',function () {
       newAlumno();
    });
    $('#tablaAlumnos').DataTable({
        'scrollX':true,
        'scrollY':'600px',
        "processing": true,
        "serverSide": true,
        "ajax": document.location.protocol+'//'+document.location.host+"/Squalo/public"  +'/resource/alumnos',
        "columnDefs":[
            {targets:[0,1,2,3],width:'20%'}
        ],
        columns: [
            {data: 'nombre'},
            {data: function (row) {
                str = row['ape_paterno']+' '+row['ape_materno'];
                return str;
            }},
            {data: 'fecha_nac'},
            {data: 'npadre'},
            {data: function (row) {
                str = (row['asignado']==0) ? row['adeudo'] : row["adeudo"]+'<a href="#" id="adeudo'+row['id']+'">  info</a>';
                return str;
            }},
            {data: function (row) {
                str = "<div align='center'>";
                str += (row['asignado']==1) ? " <button id='btninfo"+row['id']+"' class='btn btn-info btn-xs col-md-4'>Info</button>":
                    " <button id='btnasignar"+row['id']+"' onclick='asigna(\""+row['id']+"\",\""+row['nombre']+"\")' class='btn btn-success btn-xs col-md-4'>Asignar</button>";
                str += "<button id='btnEliminar"+row['id']+"' class='btn btn-danger btn-xs col-md-4'><i class='fa fa-trash-o'></i></button>";
                str += "</div>";
                return str;
            }}
        ],
        'language': {
            url:'https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json',
            sLoadingRecords : '<span style="width:100%;"><img src="http://www.snacklocal.com/images/ajaxload.gif"></span>'
        }
    });
} );
function limpiarSeleccion() {
    $('#rutealumnos').addClass('active');
    $('#rutemaestro').removeClass('active');
    $('#rutehome').removeClass('active');
    $('#rutepadres').removeClass('active');
}
function newAlumno(){
    var data = new FormData(document.getElementById("alumnosForm"));
    $.ajax({
        url:document.location.protocol+'//'+document.location.host+"/Squalo/public"  +"/resource/alumnos",
        type:"POST",
        data: data,
        contentType:false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function(json){
        if(json.code == 200) {
            swal("Realizado", json.msg, json.detail);
            $('#modalAlumno').modal("hide");
            $('#tablaAlumnos').dataTable().api().ajax.reload(null,false);
            reset();
        }else{
            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}
function asigna(id, nombre) {
    $('#idasignar').val(id);
    $('#nameasignar').val(nombre);
    $('#modalAsignar').modal('show');
}
