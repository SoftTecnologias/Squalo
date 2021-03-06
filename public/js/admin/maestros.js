$(function() {
    limpiarSeleccion();
    $('#pit').val('100');
    $('#pgt').val('100');
    $('#pet').val('100');

    $('#Individual').keyup(function () {
        item = $('#Individual').val();

        item = item.split('%');
        $('#pi').val('$'+(100*(item[0]/100))+" pesos");
    });
    $('#Grupal').keyup(function () {
        item = $('#Grupal').val();

        item = item.split('%');
        $('#pg').val('$'+(100*(item[0]/100))+" pesos");
    });
    $('#Especial').keyup(function () {
        item = $('#Especial').val();

        item = item.split('%');
        $('#pe').val('$'+(100*(item[0]/100))+" pesos");
    });

    $('#btnMaestro').on('click',function () {
        $('#titulo-modal').text("Nuevo Maestro");
        $('#maestrosForm').submit();
    });

    $('#datepicker').datepicker({
        format: 'yyyy-mm-dd',
        maxDate: '0d',
        autoclose: true
    });

    $('#adminpagos').on('click',function () {
        $('#modalAdmin').modal('show');
    });
    $('#btnAdmpago').on('click',function () {
        var data = new FormData(document.getElementById("adpa"));
        $.ajax({
            url:document.location.protocol+'//'+document.location.host+""  +"/resource/maestros/admpago",
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
                $('#modalAdmin').modal("hide");
                $('#Individual').val('');
                $('#Grupal').val('');
                $('#Especial').val('');
            }else{
                swal("Error",json.msg,json.detail);
            }
        }).fail(function(){
            swal("Error","Tuvimos un problema de conexion","error");
        });
    });
    $('#maes').on('change',function () {
        var id = $(this).find('option:selected').val();
            $.ajax({
                type: "get",
                url: document.location.protocol+'//'+document.location.host+""  +'/resource/maestros/admpago/'+id,
                success: function (data) {
                    data['msg'].forEach(function (item) {
                        $('#Individual').val(item['claseIndividual']+'%');
                        $('#Grupal').val(item['claseGrupal']+'%');
                        $('#Especial').val(item['claseEspecial']+'%');

                        $('#pi').val('$'+(100*(item['claseIndividual']/100))+" pesos");
                        $('#pg').val('$'+(100*(item['claseGrupal']/100))+" pesos");
                        $('#pe').val('$'+(100*(item['claseEspecial']/100))+" pesos");
                    });
                }
            });
    });
    $('#RegMaestro').on('click',function () {
       $('#modalMaestros').modal('show');
    });
    $('#tablaMaestros').DataTable({
        'scrollX':true,
        'scrollY':'600px',
        "processing": true,
        "serverSide": true,
        "ajax": document.location.protocol+'//'+document.location.host+""  +'/resource/maestros',
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
                console.log(row);
                str = "<div align='center'>";
                str +=" <button id='btnEditar"+row['id']+"' onclick='showMaestro(\""+row["id"]+"\"," +
                    "\""+row["nombre"]+"\"," +
                    "\""+row["ape_paterno"]+"\"," +
                    "\""+row["ape_materno"]+"\"," +
                    "\""+row["colonia"]+"\"," +
                    "\""+row["calle"]+"\"," +
                    ""+row["numero"]+"," +
                    "\""+row["RFC"]+"\"," +
                    "\""+row["Trabajo"]+"\"," +
                    "\""+row["tel_fijo"]+"\"," +
                    "\""+row["tel_celular"]+"\"," +
                    "\""+row["fecha_nac"]+"\"," +
                    "\""+row["email"]+"\")" +
                    "' class='btn btn-warning btn-xs col-md-6'><i class='glyphicon glyphicon-edit'></i></button>";
                str += "<button id='btnEliminar"+row['id']+"' onclick='deleteMaestro("+row['id']+")' class='btn btn-danger btn-xs col-md-6'><i class='fa fa-trash-o'></i></button>";
                str += "</div>";
                return str;
            }}
        ],
        'language': {
            url:'https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json',
            sLoadingRecords : '<span style="width:100%;"><img src="http://www.snacklocal.com/images/ajaxload.gif"></span>'
        }
    });

    $('#maestrosForm').validate({
        rules: {
            'name': {
                required: true,
                minlength: 3
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
            accionMaestro();
            return false;
        }
    });

} );
function limpiarSeleccion() {
    $('#rutemaestro').addClass('active');
    $('#rutehome').removeClass('active');
    $('#rutepadres').removeClass('active');
}
function newMaestro(){
    var data = new FormData(document.getElementById("maestrosForm"));
    $.ajax({
        url:document.location.protocol+'//'+document.location.host+""  +"/resource/maestros",
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
            $('#modalMaestros').modal("hide");
            $('#tablaMaestros').dataTable().api().ajax.reload(null,false);
            reset();
        }else{
            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}
function reset(){
    $('#maestroid').val('');
    $('#name').val('');
    $('#ape_pat').val('');
    $('#ape_mat').val('');
    $('#fecha').val('');
    $('#colonia').val('');
    $('#calle').val('');
    $('#numero').val('');
    $('#tel').val('');
    $('#phone').val('');
    $('#email').val('');
    $('#rfc').val('');
    $('#trabajo').val('');
}
function deleteMaestro(id){
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
            url:document.location.protocol+'//'+document.location.host+""  +'/resource/maestros/'+id,
            type:'delete',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(json){
            if(json.code==200) {
                swal("Realizado", json.msg, json.detail);
                $('#tablaMaestros').dataTable().api().ajax.reload(null,false);
            }else{
                swal("Error", json.msg, json.detail);
            }
        }).fail(function(response){
            swal("Error", "tuvimos un problema", "warning");
        });
    });
}
function updateMaestro(){
    var id = $("#maestroid").val();
    var datos = new FormData(document.getElementById("maestrosForm"));
    $.ajax({
        url:document.location.protocol+'//'+document.location.host+""  +'/resource/maestros/'+id,
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
            $('#modalMaestros').modal("hide");
            $('#tablaMaestros').dataTable().api().ajax.reload(null,false);
            reset();
        }else{

            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}
function showMaestro(id,nombre, ape_pat, ape_mat, col, calle, num,rfc,trabajo, telfij, telcel, fechanac, email){
    $('#titulo-modal').text("Editar Maestro");
    $('#maestroid').val(id);
    $('#name').val(nombre);
    $('#ape_pat').val(ape_pat);
    $('#ape_mat').val(ape_mat);
    $('#fecha').val(fechanac);
    $('#colonia').val(col);
    $('#calle').val(calle);
    $('#numero').val(num);
    $('#tel').val(telfij);
    $('#phone').val(telcel);
    $('#rfc').val(rfc);
    $('#trabajo').val(trabajo);
    $('#email').val(email);
    $('#modalMaestros').modal('show');
}
function accionMaestro() {
    if($("#maestroid").val() == ''){
        newMaestro();
    }else{
        updateMaestro();
    }
}
