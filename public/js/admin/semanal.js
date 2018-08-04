$(function() {
    limpiarSeleccion();

    $("#maestro").on("change",function () {
       id = $(this).find('option:selected').val();
       if(id != 00){

               $.ajax({
                   type: "get",
                   url: document.location.protocol+'//'+document.location.host+""  +'/getrepsemanal/'+id,
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
                            '<td>'+lunes[i].nombre+'---'+lunes[i].descripcion+'</td>' +
                            '<td>'+martes[i].nombre+'---'+martes[i].descripcion+'</td>' +
                            '<td>'+miercoles[i].nombre+'---'+miercoles[i].descripcion+'</td>' +
                            '<td>'+jueves[i].nombre+'---'+jueves[i].descripcion+'</td>' +
                            '<td>'+viernes[i].nombre+'---'+viernes[i].descripcion+'</td>' +
                            '<td>'+sabado[i].nombre+'---'+sabado[i].descripcion+'</td>' +
                            '</tr>');
                    };
                   }
               });
       }
    });

} );
function limpiarSeleccion() {
    $('#rutesemanal').addClass('active');
    $('#rutehome').removeClass('active');
}

