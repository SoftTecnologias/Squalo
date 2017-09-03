$(function() {
    limpiarSeleccion();
    $('#tablatipos').DataTable({
        'scrollX':true,
        'scrollY':'400px',
        "processing": true,
        "serverSide": true,
        "ajax": document.location.protocol+'//'+document.location.host+"/Squalo/public"  +'/resource/clases',
        "columnDefs":[
            {"targets":[1,2,3],"width":"25%"},
            {"targets":[0],"width":"20%"},
            {"targets":[4],"width":"10%"},
            {"className": "dt-center", "targets": "_all"}
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
            {data: function (row) {
                str = (row['asisalumno'] == 1) ? '<label><i class="glyphicon glyphicon-check text-success"></i></label>': '<label><i class="glyphicon glyphicon-remove text-danger"></i></label>';
                if(comparafecha(row['fecha']) == false){
                    return 'Pendiente';
                }
                return str;

            }}
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
function comparafecha(fecha) {
    var fa = new Date();
    fecha = new Date(fecha);

    if(fecha>=fa){
        return false;
    }

    console.log(fecha)
    return true;
}
