<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>
    <div id="app" class="wrapper">
        <div class="sidebar" data-active-color="green" data-background-color="black" data-image="/img/sidebar-1.jpg">
            <div class="logo">
                <a href="{{ url("/") }}" class="simple-text">
                    Logit
                </a>
            </div>
            <div class="logo logo-mini">
                <a href="{{ url("/") }}" class="simple-text">
                  Logit
                </a>
            </div>
            <div class="sidebar-wrapper">
                <div class="user">
                    <div class="photo">
                        <img alt="avatar" src="/img/avatar.png" />
                    </div>
                    <div class="info">
                        <a data-toggle="collapse" href="#collapseExample" class="collapsed">
                            {{ $user->name }}
                            <b class="caret"></b>
                        </a>
                        <div class="collapse" id="collapseExample">
                            <ul class="nav">
                                <li>
                                    <a href="{{ route('user') }}">My Profile</a>
                                </li>
                                <li>
                                    <a href="{{ route('settings') }}">Settings</a>
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
                        <a href="{{ route('dashboard') }}">
                            <i class="material-icons">dashboard</i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="{{ (Request::segment(1) == 'start-workout' ? 'active' : '') }}">
                        <a href="{{ route('startWorkout') }}">
                            <i class="material-icons">play_circle_outline</i>
                            <p>Start Workout</p>
                        </a>
                    </li>
                    <li class="{{ (Request::segment(1) == 'routines' ? 'active' : '') }}">
                        <a href="{{ route('myRoutines') }}">
                            <i class="material-icons">accessibility</i>
                            <p>My Routines</p>
                        </a>
                    </li>
                    <li class="{{ (Request::segment(1) == 'workouts' ? 'active' : '') }}">
                        <a href="{{ route('workouts') }}">
                            <i class="material-icons">view_list</i>
                            <p>My Workouts</p>
                        </a>
                    </li>
                    <li class="{{ (Request::segment(1) == 'measurements' ? 'active' : '') }}">
                        <a href="{{ route('measurements') }}">
                            <i class="material-icons">pregnant_woman</i>
                            <p>Measurements</p>
                        </a>
                    </li>
                    <li class="{{ (Request::segment(1) == 'friends' ? 'active' : '') }}">
                        <a href="{{ route('friends') }}">
                            <i class="material-icons">people</i>
                            <p>Friends</p>
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
            @include('layouts.footer')
        </div>
    </div>
  
  <!--             Core JS             -->
  <script src="{{ mix('/js/logit.min.js') }}"></script>
  <script src="{{ mix('/js/material-dashboard.min.js') }}"></script>
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