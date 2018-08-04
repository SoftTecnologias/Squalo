<div class="col-md-2">
    <!-- Left column -->

    <hr>

    <ul class="nav nav-pills nav-stacked">
        <li class="active" id="rutehome"><a href="{{route('index')}}"><i class="fa fa-home"></i> Home</a></li>
        <li>
            <hr style="border-color:gray;">
        </li>
        <li class="" id="rutemaestro"> <a href="{{route('index.maestros')}}"><i class="glyphicon glyphicon-user"></i> Maestros</a></li>
        <li  id="rutepadres"><a href="{{route('index.padres')}}"><i class="glyphicon glyphicon-user"></i> Padres</a></li>
        <li id="rutealumnos"><a href="{{route('index.alumnos')}}"><i class="fa fa-users"></i> Alumnos </a></li>
        <li>
            <hr style="border-color:gray;">
        </li>
        <li id="rutetipos"><a href="{{route('index.tipos')}}">Tipos de Clases</a></li>
        <li id="ruteasistencia"><a href="{{route('index.asistencias')}}">Asistencias</a></li>
        <li>
            <br>
            <b>Reportes</b>
            <hr style="border-color:gray;" >

        </li>
        <li id="ruteremplazo"><a href="{{route('index.reemplazo')}}">Reemplazos</a></li>
        <li id="ruteclases"><a href="{{route('index.clases')}}">Clases</a></li>
        <li id="rutepagos"><a href="{{route('index.pagos')}}">Pagos</a></li>
        <li id="rutesemanal"><a href="{{route('index.semanal')}}">Semanal</a></li>
        <li>
            <br>
            <b>Promociones</b>
            <hr style="border-color:gray;" >

        </li>
        <li id="rutepromocion"><a href="{{route('index.promo')}}">Promociones</a></li>
    </ul>

    <br>

    <IMG SRC="{{asset('img.jpg')}}" WIDTH=160 HEIGHT=140 class="">

    <hr>
</div><!-- /col-3 -->