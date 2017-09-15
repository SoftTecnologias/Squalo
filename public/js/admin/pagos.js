$(function() {
    limpiarSeleccion();
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
    $.ajax({
        url:document.location.protocol+'//'+document.location.host+"/Squalo/public"  +"/pagos/maestro",
        type:"POST",
        data: {'maestro':id,'inicial':fecha[0],'final':fecha[1]},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function(json){
        if(json.code == 200) {
            $('#hrsIndividual').val(json.msg['clasesIndividuales']);
            $('#hrsGrupal').val(json.msg['clasesGrupales']);
            $('#hrsEspecial').val(json.msg['clasesEspeciales']);
            $('#pagoIndividual').val(json.msg['individual']);
            $('#pagoGrupal').val(json.msg['grupal']);
            $('#pagoEspecial').val(json.msg['especial']);
            $('#totalIndividual').val(json.msg['totalI']);
            $('#totalGrupal').val(json.msg['totalG']);
            $('#totalEspecial').val(json.msg['totalE']);
            $('#totalPago').val(json.msg['ptotal']);
        }else{
            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });

}
