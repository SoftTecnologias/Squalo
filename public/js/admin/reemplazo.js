$(function() {
    limpiarSeleccion();
    $('#tablatipos').DataTable({
        'scrollX':true,
        'scrollY':'600px',
        "processing": true,
        "serverSide": true,
        "ajax": document.location.protocol+'//'+document.location.host+""  +'/resource/reemplazo',
        "columnDefs":[
            {"targets":[1,2,3],"width":"25%"},
            {"targets":[0],"width":"20%"},
            {"targets":[4],"width":"10%"}
        ],
        columns: [
            {data: 'descripcion'},
            {data: function (row) {
                return row['mnombre']+' '+row['map']+' '+row['mam'];
            }},
            {data: function (row) {
                return row['rnombre']+' '+row['rap']+' '+row['ram'];
            }},
            {data: 'fecha'},
            {data: 'Hora'}
        ],
        'language': {
            url:'https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json',
            sLoadingRecords : '<span style="width:100%;"><img src="http://www.snacklocal.com/images/ajaxload.gif"></span>'
        }
    });
} );
function limpiarSeleccion() {
    $('#ruteremplazo').addClass('active');
    $('#rutehome').removeClass('active');
}

