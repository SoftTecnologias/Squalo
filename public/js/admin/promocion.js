$(function () {
    $('#rutepromocion').addClass('active');
    $('#rutehome').removeClass('active');

    $('#enviar').on('click',function () {
        $.ajax({
            url:document.location.protocol+'//'+document.location.host+""  +"/send",
            type: "POST",
            data:  new FormData(document.getElementById("formPromocion")),
            contentType: false,
            cache: false,
            processData:false,
            success: function(json){
                swal("success","Mensajes Enviados Con Exito",'success');
            },
            error: function(json){
                swal("error",'No de pudo realizar por un problema en el servidor','error');
            }
        });
    });
});