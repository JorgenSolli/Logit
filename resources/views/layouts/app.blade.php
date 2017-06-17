<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>
    <div id="app" class="wrapper">
        <div class="sidebar" data-active-color="green" data-background-color="black" data-image="/img/sidebar-1.jpg">
            <div class="logo">
                <a href="/" class="simple-text">
                    Loggit
                </a>
            </div>
            <div class="logo logo-mini">
                <a href="/" class="simple-text">
                  Loggit
                </a>
            </div>
            <div class="sidebar-wrapper">
                <div class="user">
                    <div class="photo">
                        <img src="/img/avatar.png" />
                    </div>
                    <div class="info">
                        <a data-toggle="collapse" href="#collapseExample" class="collapsed">
                            {{ $brukerinfo->name }}
                            <b class="caret"></b>
                        </a>
                        <div class="collapse" id="collapseExample">
                            <ul class="nav">
                                <li>
                                    <a href="#">My Profile</a>
                                </li>
                                <li>
                                    <a href="#">Edit Profile</a>
                                </li>
                                <li>
                                    <a href="#">Settings</a>
                                </li>
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                      {{ csrf_field() }}
                                  </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <ul class="nav">
                    <li class="{{ (Request::is('dashboard') ? 'active' : '') }}">
                        <a href="/dashboard">
                            <i class="material-icons">dashboard</i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="{{ (Request::is('dashboard/my_routines') ? 'active' : '') }}">
                        <a href="/dashboard/my_routines">
                            <i class="material-icons">accessibility</i>
                            <p>My Routines</p>
                        </a>
                    </li>
                    <li class="{{ (Request::is('dashboard/start') ? 'active' : '') }}">
                        <a href="/dashboard/start">
                            <i class="material-icons">play_circle_outline</i>
                            <p>Start Workout</p>
                        </a>
                    </li>
                    <li class="{{ (Request::is('dashboard/workouts') ? 'active' : '') }}">
                        <a href="/dashboard/workouts">
                            <i class="material-icons">view_list</i>
                            <p>My Workouts</p>
                        </a>
                    </li>
                    <li class="{{ (Request::is('dashboard/measurements') ? 'active' : '') }}">
                        <a href="/dashboard/measurements">
                            <i class="material-icons">pregnant_woman</i>
                            <p>Measurements</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-panel">
            @include('layouts.topnav')
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
  @include('layouts.scripts')
  @yield('script')
</body>
</html>




        
