$(function() {
    limpiarSeleccion();
    $('#agregartipo').on('click',function () {
        $('#titulo-modal').text("Nuevo Tipo");
        $('#modalTipos').modal('show');
    });
    $('#horarioadd').on('click',function () {
            swal({
                title: 'Ingrese el Horario',
                input: 'text',
                showCancelButton: true,
                confirmButtonText: 'Agregar',
                showLoaderOnConfirm: true,
                preConfirm: function (horario) {
                    return new Promise(function (resolve, reject) {
                        setTimeout(function() {
                            if (horario == '') {
                                reject('Ingrese un Horario')
                            } else {
                                resolve()
                            }
                        }, 2000)
                    })
                },
                allowOutsideClick: false
            }).then(function (horario) {
                $.ajax({
                    url:document.location.protocol+'//'+document.location.host+""  +"/addhorario",
                    type:"POST",
                    data: {'horario':horario},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function(json){
                    if(json.code == 200) {
                        swal({
                            type: 'success',
                            title: 'Horario Registrado!',
                            html: 'Se registro: ' + horario+' correctamente'
                        })
                        $('#modalInfoAbono').modal("hide");
                        $('#tablaAlumnos').dataTable().api().ajax.reload(null,false);
                    }else{
                        swal("Error",json.msg,json.detail);
                    }
                }).fail(function(){
                    swal("Error","Tuvimos un problema de conexion","error");
                });
            })
    });
    $.validator.addMethod("selected", function(value, element, arg){
        return arg !== value;
    }, "Seleccion no valida");
    $('#tiposForm').validate({
        rules: {
            'tipo': {
                selected:'00'
            },
            'desc':{
                required:true
            },
            'costo':{
                required:true,
                number:true
            },
            'noclases':{
                required:true,
                number:true
            }
        },
        messages: {
            'tipo':{
                required: 'Seleccione las fechas'
            },
            'desc':{
                required:'Este campo es requerido'
            },
            'costo':{
                required:'Este campo es requerido',
                number:'Solo numeros'
            },
            'noclases':{
                required:'Este campo es requerido',
                number:'Solo numeros'
            }
        },
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function () {
            accionTipo();
            return false;
        }
    });

    $('#btnTipo').on('click',function () {
       $('#tiposForm').submit();
    });
    $('#tablatipos').DataTable({
        'scrollX':true,
        'scrollY':'600px',
        "processing": true,
        "serverSide": true,
        "ajax": document.location.protocol+'//'+document.location.host+""  +'/resource/tipoclase',
        "columnDefs":[
            {"targets":[0,1,3,4],"width":"25%"}
        ],
        columns: [
            {data: 'tipo_clase'},
            {data: 'descripcion'},
            {data: 'costo'},
            {data: 'numero_clases'},
            {data: function (row) {
                str = "<div align='center'>";
                str +=" <button id='btnEditar"+row['id']+"' onclick='showTipo(\""+row["id"]+"\"," +
                    "\""+row["tipo_clase"]+"\"," +
                    "\""+row["descripcion"]+"\"," +
                    "\""+row["costo"]+"\"," +
                    "\""+row["numero_clases"]+"\")" +
                    "' class='btn btn-warning btn-xs col-md-6'><i class='glyphicon glyphicon-edit'></i></button>";
                str += "<button id='btnEliminar"+row['id']+"' onclick='deleteTipo("+row['id']+")' class='btn btn-danger btn-xs col-md-6'><i class='fa fa-trash-o'></i></button>";
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
    $('#rutetipos').addClass('active');
    $('#rutepadres').removeClass('active');
    $('#rutemaestro').removeClass('active');
    $('#rutehome').removeClass('active');
}
function newTipo(){
    var data = new FormData(document.getElementById("tiposForm"));
    $.ajax({
        url:document.location.protocol+'//'+document.location.host+""  +"/resource/tipoclase",
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
            $('#modalTipos').modal("hide");
            $('#tablatipos').dataTable().api().ajax.reload(null,false);
            reset();
        }else{
            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}
function showTipo(id,tipo, desc, costo, noclase){
    $('#titulo-modal').text("Editar Tipo");
    $('#tipoid').val(id);
    $('#tipo').val(tipo);
    $('#desc').val(desc);
    $('#costo').val(costo);
    $('#noclases').val(noclase);
    $('#modalTipos').modal("show");

}
function reset(){
    $('#tipoid').val('');
    $('#tipo').val('');
    $('#desc').val('');
    $('#costo').val('');
    $('#noclases').val('');
}
function deleteTipo(id){
    swal({
        title: '¿Estás seguro?',
        text: "Esto no se puede revertir!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, deseo eliminarlo!',
        cancelButtonText: "Lo pensaré"
    }).then(function () {
        $.ajax({
            url:document.location.protocol+'//'+document.location.host+""  +'/resource/tipoclase/'+id,
            type:'delete',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(json){
            if(json.code==200) {
                swal("Realizado", json.msg, json.detail);
                $('#tablatipos').dataTable().api().ajax.reload(null,false);
            }else{
                swal("Error", json.msg, json.detail);
            }
        }).fail(function(response){
            swal("Error", "tuvimos un problema", "warning");
        });
    });
}
function updateTipo(){
    var id = $("#tipoid").val();
    var datos = new FormData(document.getElementById("tiposForm"));
    $.ajax({
        url:document.location.protocol+'//'+document.location.host+""  +'/resource/tipoclase/'+id,
        type:"POST",
        data: datos,
        contentType:false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function(json){
        if(json.code == 200) {
            swal("Realizado", json.msg, json.detail);
            $('#modalTipos').modal("hide");
            $('#tablatipos').dataTable().api().ajax.reload(null,false);
            reset();
        }else{

            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}
function accionTipo() {
    if($("#tipoid").val() == ''){
        newTipo();
    }else{
        updateTipo()
    }
}
