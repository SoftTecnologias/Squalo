$(function () {

    $('#asisMaestro').on('change',function () {
        ($('#asisMaestro').is(':checked') == false) ? $('#divremplazo').attr('hidden',false):$('#divremplazo').attr('hidden',true);
    });
    $("#ruteasistencia").addClass('active');
    $("#rutehome").removeClass('active');
    $('#tablaAsistencias').DataTable({
        'scrollX':true,
        'scrollY':'600px',
        "processing": true,
        "serverSide": true,
        "ajax": document.location.protocol+'//'+document.location.host+"/Squalo/public"  +'/resource/asistencias',
        "columnDefs":[
            {targets:[0,1,2,3,4],width:'25%'}
        ],
        columns: [
            {data: 'descripcion'},
            {data: 'fecha'},
            {data: 'Hora'},
            {data: 'nombre'},
            {data: function (row) {
                str = "<div align='center'>";
                str += "<input type='hidden' value='"+row['id']+"'>";
                str += "<button id='btnInfo"+row['id']+"' onclick='info("+row['id']+")' class='btn btn-primary btn-xs col-md-12'>info  <i class='fa fa-info'></i></button>";
                str += "</div>";
                return str;
            }}
        ],
        'language': {
            url:'https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json',
            sLoadingRecords : '<span style="width:100%;"><img src="http://www.snacklocal.com/images/ajaxload.gif"></span>'
        }
    });


});
function info(id) {
    $.ajax({
        type: "get",
        url: document.location.protocol+'//'+document.location.host+"/Squalo/public"  +'/resource/asistenciaalumnos/'+id,
        success: function (data) {
            $('#ab tr').remove();
            data['data'].forEach(function (item) {
                var asistencia = (item['asal'] == 0) ? '<input type="checkbox"> Asistencia': '<input type="checkbox" checked> Asistencia';
                $('#asistid').val(item['id']);
                (item['asma'] == 0) ? $('#asisMaestro').attr('checked',false):$('#asisMaestro').attr('checked',true);
                (item['asma'] == 0) ? $('#divremplazo').attr('hidden',false):$('#divremplazo').attr('hidden',true);
                $('#name').val(item['nm']+' '+item['apm']+' '+item['amm']);
                $('#asistenciasAlumnos').append('<tr class="table" id ="'+item['id']+'">' +
                    '<td>'+item['na']+'</td>' +
                    '<td>'+item['apa']+'</td>' +
                    '<td>'+item['ama']+'</td>' +
                    '<td>'+asistencia+'</td>' +
                    '</tr>');
            })
            $('#modalAsistencia').modal('show');
        }

    });


}
