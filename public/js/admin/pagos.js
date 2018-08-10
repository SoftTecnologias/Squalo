$(function() {
    limpiarSeleccion();
    $("#export").on("click",function () {
        $('#tablaPagos').tableExport({type: 'excel'});
    });

    $('#datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: false,
        multidate:2,
        todayHighlight:true,
        endDate:'0d'
    });
    $.validator.addMethod("selected", function(value, element, arg){
        return arg !== value;
    }, "Seleccion no valida");
    $('#formPago').validate({
        rules: {
            'maestro': {
                selected:'00'
            },
            'alldates':{
                required:true
            }
        },
        messages: {
            'alldates':{
                required:'Este campo es requerido'
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
            consulta();
            return false;
        }
    });
    $('#consultar').on('click',function () {
        $('#formPago').submit();
    });
} );
function limpiarSeleccion() {
    $('#rutepagos').addClass('active');
    $('#rutehome').removeClass('active');
}
function consulta() {
    var fechas = $('#alldates').val();
    var fecha = fechas.split(',');
    fecha1= new Date(fecha[0]);
    fecha2= new Date(fecha[1]);
    var temp;
    if(fecha1>fecha2){
        temp = fecha[0];
        fecha[0] = fecha[1];
        fecha[1] = temp;
    }
    var id = $('#maestro').val();
    $('#export').removeAttr('href');
    $('#export').attr('href',"exportpdf/"+id+'&'+fecha[0]+'&'+fecha[1]);
    $('#exportall').removeAttr('href');
    $('#exportall').attr('href',"exportallpdf/"+fecha[0]+'&'+fecha[1]);
    $.ajax({
        url:document.location.protocol+'//'+document.location.host+""  +"/pagos/maestro",
        type:"POST",
        data: {'maestro':id,'inicial':fecha[0],'final':fecha[1]},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function(json){

        if(json.code == 200) {
            clases = json.msg.clases;
            datos = json.msg;
            $('#cuerpo').empty();
            $('#mname').empty();
            $('#mname').append('<th colspan="5">'+$('#maestro option:selected').text()+'</th>');
            for(i=0; i<clases.length; i++){
                $('#cuerpo').append('<tr>' +
                    '<td>'+clases[i].fecha+'</td>' +
                    '<td>'+clases[i].grupales+'</td>' +
                    '<td>'+clases[i].individuales+'</td>' +
                    '<td>'+clases[i].reemplazos+'</td>' +
                    '<td>'+clases[i].especiales+'</td>' +
                    '</tr>');
            }
            $('#cuerpo').append('<tr class="warning">' +
                '<td>Total de Clases</td>' +
                '<td>'+datos.tcgru+'</td>' +
                '<td>'+datos.tcin+'</td>' +
                '<td>'+datos.tcrem+'</td>' +
                '<td>'+datos.tcesp+'</td>' +
                '</tr>');
            $('#cuerpo').append('<tr class="success">' +
                '<td>Total a pagar</td>' +
                '<td>$ '+datos.grupal+'</td>' +
                '<td>$ '+datos.individual+'</td>' +
                '<td>$ '+datos.reemplazo+'</td>' +
                '<td>$ '+datos.especial+'</td>' +
                '</tr>');
        }else{
            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });

}
