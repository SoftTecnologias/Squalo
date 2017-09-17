$(function() {
    limpiarSeleccion();

    $('#chactivos').on('change',function () {
       if($(this).is(':checked')){
           var filtro;
           if($('#chinactivos').is(':checked')){
               filtro = '/all'
           }else{
               filtro = '/activos';
           }
           $('#tablaAlumnos').dataTable().fnDestroy();
            rellenarTabla(filtro);
           $('#tablaAlumnos').dataTable().api().ajax.reload(null,false);
       }else{
           filtro = '/inactivos';
           $('#tablaAlumnos').dataTable().fnDestroy();
           rellenarTabla(filtro);
           $('#tablaAlumnos').dataTable().api().ajax.reload(null,false);
           $('#chinactivos').prop('checked',true)
       }
    });
    $('#chinactivos').on('change',function () {
        if($(this).is(':checked')){
            var filtro;
            if($('#chactivos').is(':checked')){
                filtro = '/all'
            }else{
                filtro = '/inactivos';
            }
            $('#tablaAlumnos').dataTable().fnDestroy();
            rellenarTabla(filtro);
            $('#tablaAlumnos').dataTable().api().ajax.reload(null,false);
        }else{
            $('#chactivos').prop('checked',true);
            filtro = '/activos';
            $('#tablaAlumnos').dataTable().fnDestroy();
            rellenarTabla(filtro);
            $('#tablaAlumnos').dataTable().api().ajax.reload(null,false);
        }
    });

    $.validator.addMethod("selected", function(value, element, arg){
        return arg !== value;
    }, "Seleccion no valida");
    $('#alumnosForm').validate({
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
            'fecha_nac':{
                required:true
            },
            'padre':{
                selected:'00'
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
            'fecha_nac':{
                required:'Este campo es requerido'
            },
            'padre':{
                required: 'Seleccione un padre'
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
            newAlumno();
            return false;
        }
    });
    $('#asignarForm').validate({
        rules: {
            'tipoc': {
               selected:'00'
            },
            'maestroc':{
                selected:'00'
            },
            'horario':{
               selected:'00'
            },
            'alldates':{
                required:true
            }
        },
        messages: {
            'alldates':{
                required: 'Seleccione las fechas'
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
            var tipo = $('#tipoc').find('option:selected').attr('name');
            var tc = tipo.split('-');
            if(tc[1] == 'G'){
                asignarAlumnoGrupal();
            }else {
                asignarAlumno();
            }
            return false;
        }
    });
    $('#tipoc').on('change',function () {
        tipoclase = $(this).find('option:selected').attr('name');
        tc = tipoclase.split('-');
        var id = $(this).find('option:selected').val();
        console.log(id);
        if(tc[1] == 'G'){
            $.ajax({
                type: "get",
                url: document.location.protocol+'//'+document.location.host+""  +'/resource/alumnos/grupo/'+id,
                success: function (data) {
                    $('#gruposdisp option').remove();
                    $('#gruposdisp').append('<option value="00">Seleccione un Grupo</option>').selectpicker('refresh');
                    data['msg'].forEach(function (item) {
                            $('#gruposdisp').append('<option value="'+item['idclase']+'">'+'Inicia: '+item['feini']+'</option>').selectpicker('refresh');
                    });
                }
            });
            $('#grupos').show();
        }else{
            $('#maestroc').val('00').datepicker('refresh');
            $('#horario').val('00').datepicker('refresh');
            $('#alldates').val('');
            $('#grupos').hide();
        }
    });

    $('#gruposdisp').on('change',function () {
        var id = $(this).find('option:selected').val();
        console.log(id);
        if(tc[1] != '00'){
            $.ajax({
                type: "get",
                url: document.location.protocol+'//'+document.location.host+""  +'/resource/alumnos/clase/sel/'+id,
                success: function (data) {
                    var fechas = '';
                    data['msg'].forEach(function (item) {
                        $('#maestroc').val(item['maestro']).datepicker('refresh');
                        $('#horario').val(item['hora']).datepicker('refresh');
                        fechas += item['fechas']+',';
                    });
                    $('#alldates').val(fechas);
                }
            });

        }else{

        }
    });

    $('#horario').on('change',function () {
        $.ajax({
            url:document.location.protocol+'//'+document.location.host+""  +"/resource/alumnos/horarios",
            type:"POST",
            data: {'maestro':$('#maestroc').val(),'horario':$('#horario').val()},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(json){
            if(json.code == 200) {
                var arreglo = [];
                var fechas = json.msg;
                fechas.forEach(function (item) {
                    arreglo.push(item['fecha'])
                });
                console.log(arreglo);
                $('#datepicker').datepicker('destroy');
                $('#datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    startDate: '0d',
                    autoclose: false,
                    multidate:true,
                    datesDisabled:arreglo
                });
            }else{
                swal("Error",json.msg,json.detail);
            }
        }).fail(function(){
            swal("Error","Tuvimos un problema de conexion","error");
        });

    });

    $('#abonar').on('click',function () {
        swal({
            title: 'Ingrese el monto a abonar',
            input: 'number',
            showCancelButton: true,
            confirmButtonText: 'Abonar',
            showLoaderOnConfirm: true,
            preConfirm: function (abono) {
                return new Promise(function (resolve, reject) {
                    setTimeout(function() {
                        if (isNaN(abono)) {
                            reject('No es un numero')
                        } else {
                            resolve()
                        }
                    }, 2000)
                })
            },
            allowOutsideClick: false
        }).then(function (abono) {
            var id = $('#idabono').val();
            $.ajax({
                url:document.location.protocol+'//'+document.location.host+""  +"/resource/alumnos/abono/"+id,
                type:"POST",
                data: {'abonar':abono},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function(json){
                if(json.code == 200) {
                    swal({
                        type: 'success',
                        title: 'Pago Registrado!',
                        html: 'Se abonaron: ' + abono+' a la cuenta'
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


    $('#btnAlumnoAsignar').on('click',function () {
       $('#asignarForm').submit();
    });
    $('#agregarAlumno').on('click',function () {
        $('#titulo-modal').text('Nuevo Alumno');
        $('#modalAlumno').modal('show');
    });
    $('#btnAlumno').on('click',function () {
       $('#alumnosForm').submit();
    });
    $('#tablaAlumnos').DataTable({
        'scrollX':true,
        'scrollY':'600px',
        "processing": true,
        "serverSide": true,
        "ajax": document.location.protocol+'//'+document.location.host+""  +'/resource/alumnos',
        "columnDefs":[
            {targets:[0,1,3],width:'20%'},
            {targets:[2,4,5],width:'13%'},
            {searchable:true, targets:'_all'},
        ],
        columns: [
            {data: 'nombre'},
            {data: function (row) {
                str = row['ape_paterno']+' '+row['ape_materno'];
                return str;
            }},
            {data: 'fecha_nac'},
            {data: function (row) {
                str = row['npadre']+' '+row['appadre']+' '+row['ampadre'];
                return str;
            }},
            {data: function (row) {
                str = (row['asignado']==0) ? row['adeudo'] : row["adeudo"]+'<a id="adeudo'+row['id']+'" class="btn btn-warning btn-xs" onclick="pago('+row['id']+')">pago</a>';
                return str;
            }},
            {data: function (row) {
                str = "<div align='center'>";
                str += (row['asignado']==1) ? " <button id='btninfo"+row['id']+"' onclick='infoAlumno(\""+row['id']+"\")' class='btn btn-info btn-xs col-md-4'>Info</button>":
                    " <button id='btnasignar"+row['id']+"' onclick='asigna(\""+row['id']+"\",\""+row['nombre']+"\")' class='btn btn-success btn-xs col-md-4'>Asignar</button>";
                str += "<button id='btnEliminar"+row['id']+"' onclick='baja(\""+row['id']+"\")' class='btn btn-danger btn-xs col-md-4'>Baja</button>";
                str += "</div>";

                (row['activo'] == 0) ?  str = "<div align='center'><button id='btnAlta"+row['id']+"' onclick='alta(\""+row['id']+"\")' class='btn btn-info btn-xs col-md-4'>Alta</button></div>":'';
                return str;
            }}
        ],
        buttons: [
            'print'
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
        url:document.location.protocol+'//'+document.location.host+""  +"/resource/alumnos",
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
function asignarAlumno(){
  var data = new FormData(document.getElementById("asignarForm"));

  var id = $('#idasignar').val();
  $.ajax({
      url:document.location.protocol+'//'+document.location.host+""  +"/alumnos/"+id+'/asignar',
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
          $('#modalAsignar').modal("hide");
          $('#tablaAlumnos').dataTable().api().ajax.reload(null,false);
      }else{
          swal("Error",json.msg,json.detail);
      }
  }).fail(function(){
      swal("Error","Tuvimos un problema de conexion","error");
  });
}
function asignarAlumnoGrupal(){
    var data = new FormData(document.getElementById("asignarForm"));

    var id = $('#idasignar').val();
    $.ajax({
        url:document.location.protocol+'//'+document.location.host+""  +"/alumnos/"+id+'/asignargrupo',
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
        }else{
            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}

function pagso(id) {
    $('#modalInfoAbono').modal('show');
}

function pago(id) {

    $.ajax({
        type: "get",
        url: document.location.protocol+'//'+document.location.host+""  +'/resource/pagos/'+id,
        success: function (data) {
            $('#abonobody tr').remove();
            $('#idabono').val(id);
            data['data'].forEach(function (item) {
                var acciones = (item['cancel'] == 0) ? '<button type="button" id="cancel'+item['id']+'" onclick="cancelpago('+item['id']+')" class="btn btn-danger">Cancelar</button>': '<label>Cancelado</label>';
                var col = (item['cancel'] == 0) ? 'success': 'danger';
                $('#nameabono').val(item['nombre']+' '+item['ape_paterno']+' '+item['ape_materno']);
                $('#tablainfoabono').append('<tr class="table '+col+'" id ="'+item['id']+'">' +
                    '<td>'+item['abono']+'</td>' +
                    '<td>'+item['fecha']+'</td>' +
                    '<td align="center">'+acciones+'</td>' +
                    '</tr>');
            })
            $('#modalInfoAbono').modal('show');
        }

    });
}

function cancelpago(id) {
    swal({
        title: 'Este pago se cancelara',
        showCancelButton: true,
        confirmButtonText: 'continuar',
        showLoaderOnConfirm: true,
        preConfirm: function (abono) {
            return new Promise(function (resolve, reject) {
                setTimeout(function() {
                    resolve();
                }, 2000)
            })
        },
        allowOutsideClick: false
    }).then(function (abono) {

        $.ajax({
            url:document.location.protocol+'//'+document.location.host+""  +"/resource/alumnos/abono/cancel/"+id,
            type:"POST",
            data: {'id':id},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(json){
            if(json.code == 200) {
                swal({
                    type: 'success',
                    title: 'Ajax request finished!',
                    html: 'Se abonaron: ' + abono+' a la cuenta'
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
}

function rellenarTabla(filtro) {
    $('#tablaAlumnos').DataTable({
        'scrollX':true,
        'scrollY':'600px',
        "processing": true,
        "serverSide": true,
        "ajax":{
            "url":  document.location.protocol+'//'+document.location.host+""  +'/alumnos'+filtro,
            "type": "get"
        },
        "columnDefs":[
            {targets:[0,1,3],width:'20%'},
            {targets:[2,4,5],width:'13%'},
            {searchable:true, targets:'_all'},
        ],
        columns: [
            {data: 'nombre'},
            {data: function (row) {
                str = row['ape_paterno']+' '+row['ape_materno'];
                return str;
            }},
            {data: 'fecha_nac'},
            {data: function (row) {
                str = row['npadre']+' '+row['appadre']+' '+row['ampadre'];
                return str;
            }},
            {data: function (row) {
                str = (row['asignado']==0) ? row['adeudo'] : row["adeudo"]+'<a id="adeudo'+row['id']+'" class="btn btn-warning btn-xs" onclick="pago('+row['id']+')">pago</a>';
                return str;
            }},
            {data: function (row) {
                str = "<div align='center'>";
                str += (row['asignado']==1) ? " <button id='btninfo"+row['id']+"' class='btn btn-info btn-xs col-md-4'>Info</button>":
                    " <button id='btnasignar"+row['id']+"' onclick='asigna(\""+row['id']+"\",\""+row['nombre']+"\")' class='btn btn-success btn-xs col-md-4'>Asignar</button>";
                str += "<button id='btnEliminar"+row['id']+"' class='btn btn-danger btn-xs col-md-4'>Baja</button>";
                str += "</div>";

                (row['activo'] == 0) ?  str = "<div align='center' class='col-md-12'><button id='btnAlta"+row['id']+"' onclick='alta(\""+row['id']+"\")' class='btn btn-primary btn-xs col-md-8'>Alta</button></div>":'';
                return str;
            }}
        ],
        buttons: [
            'print'
        ],
        'language': {
            url:'https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json',
            sLoadingRecords : '<span style="width:100%;"><img src="http://www.snacklocal.com/images/ajaxload.gif"></span>'
        }
    });
}

function infoAlumno(id){
    $('#infoFechas label').remove();
    $('#infoFechas input').remove();
    $('#infoFechas div').remove();
    $.ajax({
        type: "get",
        url: document.location.protocol+'//'+document.location.host+""  +'/alumnos/'+id+'/getfechas',
        success: function (data) {
            var fechas = '';
            data['msg'].forEach(function (item) {
                $('#ainame').val(item['nombre']);
                $('#aiape_pat').val(item['ape_paterno']);
                $('#aiape_mat').val(item['ape_materno']);
                $('#aifecha_nac').val(item['fecha_nac']);
                $('#aipadre').val(item['npadre']+' '+item['appadre']+' '+item['ampadre']);
                (comparafecha(item['fecha']) == false) ? item['asistencia']='pendiente' : '';
                $('#infoFechas').append('<div class="form-group">' +
                    '<label class="col-md-4 control-label" for="'+item['fecha']+'" >Fecha Clase:</label>'+
                    ' <div class="col-md-4">' +
                    '<input type="text" id="'+item['fecha']+'" name="'+item['fecha']+'"  class="form-control input-md" value="'+item['fecha']+'">' +
                    '</div>' +
                    '<label class="col-md-1">'+item['asistencia']+'</label>'+
                    '</div>');
            });
        }
    });

    $('#modalIfoAlumno').modal('show');
}
function comparafecha(fecha) {
    var fa = new Date();
    fecha = new Date(fecha);
    fecha.setDate(fecha.getDate() + 1);
    if(fecha>fa){
        return false;
    }
    return true;
}

function baja(id){
    swal({
        title: 'Estas seguro?',
        text: "El Alumno se dara de baja!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si deseo continuar!'
    }).then(function () {
         $.ajax({
         type: "get",
         url: document.location.protocol+'//'+document.location.host+""  +'/alumno/baja/'+id,
           success: function (data) {
             swal(
             'Success!',
            'El Alumno se dio de baja Correctamente!',
             'success'
             ).then(function () {
                 $('#tablaAlumnos').dataTable().api().ajax.reload(null,false);
             });
          }
         });
    });
}

function alta(id){
    swal({
        title: 'Estas seguro?',
        text: "El Alumno se dara de Alta!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si deseo continuar'
    }).then(function () {
        $.ajax({
            type: "get",
            url: document.location.protocol+'//'+document.location.host+""  +'/alumno/alta/'+id,
            success: function (data) {
                swal(
                    'Success!',
                    'El Alumno se dio de alta Correctamente!',
                    'success'
                ).then(function () {
                    $('#tablaAlumnos').dataTable().api().ajax.reload(null,false);
                });
            }
        });
    });
}
