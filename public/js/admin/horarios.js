$(function() {
    limpiarSeleccion();

    $('#addHorario').on('click',function () {
       horario = $('#de').val()+"-"+$('#a').val();

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
                    $('#myModal').modal("hide");
                    document.location.reload();
                }else{
                    swal("Error",json.msg,json.detail);
                }
            }).fail(function(){
                swal("Error","Tuvimos un problema de conexion","error");
            });
        });

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
    $("#export").on("click",function () {
        $('#tablaH').tableExport({type: 'excel'});
    });
});
function limpiarSeleccion() {
    $('#rutehorarios').addClass('active');
    $('#rutehome').removeClass('active');
}

