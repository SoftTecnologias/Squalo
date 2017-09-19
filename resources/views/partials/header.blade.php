<div id="top-nav" class="navbar navbar-inverse navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <IMG SRC="{{asset('img.jpg')}}" WIDTH=100 HEIGHT=60 class="">
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <?php
                $cookie = Cookie::get('admin')
                        ?>
                <li class="dropdown">
                    <a class="" role="button"  href="#"><i class="glyphicon glyphicon-user"></i> {{$cookie['username']}} <!--span class="caret"></span--></a>
                </li>
                <li><a href="{{route('logout')}}"><i class="glyphicon glyphicon-lock"></i> Logout</a></li>
            </ul>
        </div>
    </div><!-- /container -->
</div>