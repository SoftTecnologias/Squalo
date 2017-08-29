$(function () {
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
                str += "<button id='btnEliminar"+row['id']+"' class='btn btn-primary btn-xs col-md-12'>info  <i class='fa fa-info'></i></button>";
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
