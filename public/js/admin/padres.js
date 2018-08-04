$(function() {
    $('#agregarpadre').on('click',function () {
        $('#titulo-modal').text("Nuevo Padre");
        $('#modalPadres').modal('show');
    });

    $('#datepicker').datepicker({
        format: 'yyyy-mm-dd',
        maxDate: '0d',
        autoclose: true
    });

    $('#padresForm').validate({
        rules: {
            'name': {
                required: true,
                minlength: 3
            },
            'rfc': {
                required: true,
                minlength: 10
            },
            'trabajo': {
                required: true,
                minlength: 5
            },
            'ape_pat':{
                required:true,
                minlength: 3
            },
            'ape_mat':{
                required:true,
                minlength:3
            },
            'colonia':{
                required:true,
                minlength:3
            },
            'calle':{
                required:true,
                minlength:3
            },
            'numero':{
                required:true,
                number:true
            },
            'tel':{
                minlength: 7,
                maxlength:10
            },
            'phone':{
                minlength: 10,
                maxlength: 12
            },
            'fecha':{
                required:true
            },
            'email':{
                required:true,
                email:true
            }
        },
        messages: {
            'name': {
                required: "Este campo es requerido",
                minlength: "El nombre es muy corto"
            },
            'rfc': {
                required: "Este campo es requerido",
                minlength: "El nombre es muy corto"
            },
            'trabajo': {
                required: "Este campo es requerido",
                minlength: "El nombre es muy corto"
            },
            'ape_pat':{
                required: "Este campo es requerido",
                minlength: "El nombre es muy corto"
            },
            'ape_mat':{
                required: "Este campo es requerido",
                minlength: "El nombre es muy corto"
            },
            'colonia':{
                required: "Este campo es requerido",
                minlength: "El nombre es muy corto"
            },
            'calle':{
                required: "Este campo es requerido",
                minlength: "El nombre es muy corto"
            },
            'numero':{
                required: "Este campo es requerido",
                number: "Solo numeros"
            },
            'tel':{
                minlength: 'El numero es muy corto',
                maxlength:'El numero es muy largo'
            },
            'phone':{
                minlength: 'El numero es muy corto',
                maxlength:'El numero es muy largo'
            },
            'fecha':{
                required:'Este campo es requerido'
            },
            'email':{
                required: 'Este campo es requerido',
                email: 'Correo no valido'
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
    limpiarSeleccion();
    $('#btnPadre').on('click',function () {
       $('#padresForm').submit();
    });

    $('#tablaPadres').DataTable({
        'scrollX':true,
        'scrollY':'600px',
        "processing": true,
        "serverSide": true,
        "ajax": document.location.protocol+'//'+document.location.host+""  +'/resource/padres',
        "columnDefs":[
            {"targets":[0,1,2,3],"width":"20%"}
        ],
        columns: [
            {data: 'nombre'},
            {data: 'ape_paterno'},
            {data: 'tel_celular'},
            {data: 'email'},
            {data: 'colonia'},
            {data: function (row) {
                str = "<div align='center'>";
                str +=" <button id='btnEditar"+row['id']+"' onclick='showPadre(\""+row["id"]+"\"," +
                    "\""+row["nombre"]+"\"," +
                    "\""+row["RFC"]+"\"," +
                    "\""+row["Trabajo"]+"\"," +
                    "\""+row["ape_paterno"]+"\"," +
                    "\""+row["ape_materno"]+"\"," +
                    "\""+row["colonia"]+"\"," +
                    "\""+row["calle"]+"\"," +
                    ""+row["numero"]+"," +
                    "\""+row["tel_fijo"]+"\"," +
                    "\""+row["tel_celular"]+"\"," +
                    "\""+row["fecha_nac"]+"\"," +
                    "\""+row["email"]+"\")" +
                    "' class='btn btn-warning btn-xs col-md-6'><i class='glyphicon glyphicon-edit'></i></button>";
                str += "<button id='btnEliminar"+row['id']+"' onclick='deletePadre("+row['id']+")' class='btn btn-danger btn-xs col-md-6'><i class='fa fa-trash-o'></i></button>";
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
    $('#rutepadres').addClass('active');
    $('#rutemaestro').removeClass('active');
    $('#rutehome').removeClass('active');
}
function newPadre(){
    var data = new FormData(document.getElementById("padresForm"));
    $.ajax({
        url:document.location.protocol+'//'+document.location.host+""  +"/resource/padres",
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
            $('#modalPadres').modal("hide");
            $('#tablaPadres').dataTable().api().ajax.reload(null,false);
            reset();
        }else{
            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}
function reset(){
    $('#padreid').val('');
    $('#name').val('');
    $('#ape_pat').val('');
    $('#ape_mat').val('');
    $('#fecha').val('');
    $('#colonia').val('');
    $('#calle').val('');
    $('#numero').val('');
    $('#tel').val('');
    $('#rfc').val('');
    $('#trabajo').val('');
    $('#phone').val('');
    $('#email').val('');
}
function deletePadre(id){
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
            url:document.location.protocol+'//'+document.location.host+""  +'/resource/padres/'+id,
            type:'delete',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(json){
            if(json.code==200) {
                swal("Realizado", json.msg, json.detail);
                $('#tablaPadres').dataTable().api().ajax.reload(null,false);
            }else{
                swal("Error", json.msg, json.detail);
            }
        }).fail(function(response){
            swal("Error", "tuvimos un problema", "warning");
        });
    });
}
function updatePadre(){
    var id = $("#padreid").val();
    var datos = new FormData(document.getElementById("padresForm"));
    $.ajax({
        url:document.location.protocol+'//'+document.location.host+""  +'/resource/padres/'+id,
        type:"POST",
        data: datos,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function(json){
        if(json.code == 200) {
            swal("Realizado", json.msg, json.detail);
            $('#modalPadres').modal("hide");
            $('#tablaPadres').dataTable().api().ajax.reload(null,false);
            reset();
        }else{

            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}
function showPadre(id,nombre,rfc,trabajo, ape_pat, ape_mat, col, calle, num, telfij, telcel, fechanac, email){
    $('#titulo-modal').text("Editar Padre");
    $('#padreid').val(id);
    $('#name').val(nombre);
    $('#ape_pat').val(ape_pat);
    $('#ape_mat').val(ape_mat);
    $('#fecha').val(fechanac);
    $('#colonia').val(col);
    $('#calle').val(calle);
    $('#numero').val(num);
    $('#tel').val(telfij);
    $('#tel').val(telfij);
    $('#rfc').val(rfc);
    $('#trabajo').val(trabajo);
    $('#phone').val(telcel);
    $('#email').val(email);
    $('#modalPadres').modal('show');
}
function accionTipo() {
    if($("#padreid").val() == ''){
        newPadre();
    }else{
        updatePadre()
    }
}
