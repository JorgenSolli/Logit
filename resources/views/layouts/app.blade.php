<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>
    <div id="app" class="wrapper">
        <div class="sidebar" data-color="green" data-background-color="black" data-image="/img/sidebar-1.jpg">
            <div class="logo">
                <a href="{{ url("/") }}" class="simple-text logo-mini"></a>
                <a href="{{ url("/") }}" class="simple-text logo-normal">
                    Logit
                </a>
            </div>
            <div class="sidebar-wrapper">
                <div class="user">
                    <div class="photo">
                        <img alt="avatar" src="/img/avatar.png" />
                    </div>
                    <div class="user-info">
                        <a data-toggle="collapse" href="#collapseExample" class="username">
                            <span>
                                {{ $user->name }}
                                <b class="caret"></b>
                            </span>
                        </a>
                        <div class="collapse" id="collapseExample">
                            <ul class="nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('user') }}">
                                        <span class="sidebar-mini"> MP </span>
                                        <span class="sidebar-normal"> My Profile </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('settings') }}">
                                        <span class="sidebar-mini"> S </span>
                                        <span class="sidebar-normal"> Settings </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <span class="sidebar-mini"> L </span>
                                        <span class="sidebar-normal"> Logout </span>
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
                    <li class="nav-item {{ (Request::is('dashboard') ? 'active' : '') }}">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="material-icons">dashboard</i>
                            <p> Dashboard </p>
                        </a>
                    </li>
                    <li class="nav-item {{ (Request::segment(1) == 'start-workout' ? 'active' : '') }}">
                        <a class="nav-link" href="{{ route('startWorkout') }}">
                            <i class="material-icons">play_circle_outline</i>
                            <p> Start Workout </p>
                        </a>
                    </li>
                    <li class="nav-item {{ (Request::segment(1) == 'routines' ? 'active' : '') }}">
                        <a class="nav-link" href="{{ route('myRoutines') }}">
                            <i class="material-icons">accessibility</i>
                            <p> My Routines </p>
                        </a>
                    </li>
                    <li class="nav-item {{ (Request::segment(1) == 'workouts' ? 'active' : '') }}">
                        <a class="nav-link" href="{{ route('workouts') }}">
                            <i class="material-icons">view_list</i>
                            <p> My Workouts </p>
                        </a>
                    </li>
                    <li class="nav-item {{ (Request::segment(1) == 'measurements' ? 'active' : '') }}">
                        <a class="nav-link" href="{{ route('measurements') }}">
                            <i class="material-icons">pregnant_woman</i>
                            <p> Measurements </p>
                        </a>
                    </li>
                    <li class="nav-item {{ (Request::segment(1) == 'friends' ? 'active' : '') }}">
                        <a class="nav-link" href="{{ route('friends') }}">
                            <i class="material-icons">people</i>
                            <p> Friends </p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-panel">
            @include('layouts.topnav')
            <div class="content">
                <div id="pageload">
                  <div class="showbox">
                    <div class="loader">
                      <svg class="circular" viewBox="25 25 50 50">
                        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
                      </svg>
                    </div>
                    <p class="loader-text">Loading your precious data...</p>
                  </div>
                </div>
                <div class="container-fluid" style="display: none">
                    @yield('content')
                </div>
            </div>
            @include('layouts.parts.modal')
            @include('layouts.parts.modalLarge')
            @include('layouts.footer')
        </div>
    </div>
  
  <!--             Core JS             -->
  <script src="{{ mix('/js/logit.min.js') }}"></script>
  <script src="{{ mix('/js/logitFuncs.min.js') }}"></script>
  
  @include('layouts.scriptNotifications')
  @yield('script')

  <script>
    $(document).ready(function() {
      $("#pageload").fadeOut();
      $("#app .container-fluid").fadeIn();
    })
  </script>
</body>
</html>