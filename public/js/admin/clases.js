$(function() {
    limpiarSeleccion();
    $('#tablaClases').DataTable({
        'scrollX':true,
        'scrollY':'400px',
        "processing": true,
        "serverSide": true,
        "ajax": document.location.protocol+'//'+document.location.host+""  +'/resource/clases',
        "columnDefs":[
            {"targets":[1,2,3],"width":"25%"},
            {"targets":[0],"width":"20%"},
            {"targets":[4],"width":"10%"},
            {"className": "dt-center", "targets": "_all"},
        ],

        dom: 'Bfrtip',
        "buttons":[
            {
                extend:    'excelHtml5',
                text:      '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel'
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fa fa-file-pdf-o"></i>',
                titleAttr: 'PDF'
            }
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
                str = (row['asisalumno'] == 1) ? '<label>asistio <i class="glyphicon glyphicon-check text-success"></i></label>': '<label>falto <i class="glyphicon glyphicon-remove text-danger"></i></label>';
                if(comparafecha(row['fecha']) == false){
                    return '<label>Pendiente</label>';
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
    return true;
}
