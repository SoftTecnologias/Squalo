<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Panel de Administración Squalo</title>
    <!-- JQuery 3.1.1 -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"
            integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
            crossorigin="anonymous"></script>
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css"/>

    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link media="all" type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.4.4/sweetalert2.min.css" />
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{asset('/css/index.css')}}">
    <script src="{{ asset('/js/login.js') }}" type="text/javascript"></script>
</head>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="pr-wrap">

            </div>
            <div class="wrap">

                <p class="form-title">
                    <IMG SRC="{{asset('img.jpg')}}" WIDTH=300 HEIGHT=200 class="">
                    </p>
                <div align="center">
                    <h1 class="xbootstrap"> Bienvenido </h1>
                </div>
                <form class="login">
                    <div class="row">
                    <input type="text" id="username" name="username" placeholder="Username" class="form-control"/>
                        <br>
                    <input type="password" id="password" name="password" placeholder="Password"  class="form-control auto-complete-off" autocomplete="off"/>
                    <div class="remember-forgot">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" />
                                        Recordar
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 forgot-pass-content">
                            </div>
                        </div>
                    </div>
                        <br>
                        <div align="right">
                            <a id="signin" name="signin" class="btn btn-success btn-sm">Entrar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>