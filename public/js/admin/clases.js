$(function() {
    limpiarSeleccion();
    $('#tablatipos').DataTable({
        'scrollX':true,
        'scrollY':'600px',
        "processing": true,
        "serverSide": true,
        "ajax": document.location.protocol+'//'+document.location.host+"/Squalo/public"  +'/resource/clases',
        "columnDefs":[
            {"targets":[1,2,3],"width":"25%"},
            {"targets":[0],"width":"20%"},
            {"targets":[4],"width":"10%"}
        ],
        columns: [
            {data: function (row) {
                return row['pnombre']+' '+row['pap']+' '+row['pam'];
            }},
            {data: function (row) {
                return row['anombre']+' '+row['aap']+' '+row['aam'];
            }},
            {data: function (row) {
                return row['mnombre']+' '+row['map']+' '+row['mam'];
            }},
            {data: 'fecha'},
            {data: 'asisalumno'}
        ],
        'language': {
            url:'https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json',
            sLoadingRecords : '<span style="width:100%;"><img src="http://www.snacklocal.com/images/ajaxload.gif"></span>'
        }
    });
} );
function limpiarSeleccion() {
    $('#ruteclases').addClass('active');
    $('#rutehome').removeClass('active');
}

