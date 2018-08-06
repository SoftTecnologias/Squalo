$(function() {
    limpiarSeleccion();



            $.ajax({
                type: "get",
                url: document.location.protocol+'//'+document.location.host+""  +'/gethorariosemanal',
                success: function (json) {
                    $('#cuerpo').empty();
                    lunes = json.lunes;
                    martes = json.martes;
                    miercoles = json.miercoles;
                    jueves = json.jueves;
                    viernes = json.viernes;
                    sabado = json.sabado;
                    for(i=0; i<lunes.length; i++){
                        $('#cuerpo').append('<tr>' +
                            '<td>'+lunes[i].Hora+'</td>' +
                            '<td>'+lunes[i].nombre+'</td>' +
                            '<td>'+martes[i].nombre+'</td>' +
                            '<td>'+miercoles[i].nombre+'</td>' +
                            '<td>'+jueves[i].nombre+'</td>' +
                            '<td>'+viernes[i].nombre+'</td>' +
                            '<td>'+sabado[i].nombre+'</td>' +
                            '</tr>');
                    };
                }
            });

});
function limpiarSeleccion() {
    $('#rutehorarios').addClass('active');
    $('#rutehome').removeClass('active');
}

