$(function() {
    limpiarSeleccion();
    $("#export").on("click",function () {
        $('#tableadeudos').tableExport({type: 'excel'});
    });


} );
function limpiarSeleccion() {
    $('#ruteadeudo').addClass('active');
    $('#rutehome').removeClass('active');
}