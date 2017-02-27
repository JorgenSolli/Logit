<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Loggit') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
  <div id="app">
    <div class="row">
      <div class="col-xs-2 col-sm-2 sidebar">
        <ul class="nav nav-sidebar">
          <li class="hidden-xs nav-logo"><a><span class="fa fa-sticky-note-o fa-lg"></span>
            <span class="hidden-xs">&nbsp;&nbsp;Loggit</a></span>
          </li>
          @if (Auth::guest())
            <li class="{{ (Request::is('login') ? 'active' : '') }}">
              <a href="{{ route('login') }}">
                <span class="fa fa-sign-in fa-lg"></span>&nbsp;&nbsp;Login</a>
            </li>
            <li class="{{ (Request::is('register') ? 'active' : '') }}">
              <a href="{{ route('register') }}">
                <span class="fa fa-user-plus fa-lg"></span>&nbsp;&nbsp;Register</a>
            </li>
          @else
            <li class="{{ (Request::is('dashboard') ? 'active' : '') }}"><a href="/dashboard"><span class="fa fa-tachometer fa-lg"></span>
              <span class="hidden-xs">&nbsp;&nbsp;Dashboard</span></a>
            </li>
            <li class="{{ (Request::is('dashboard/my_routines') ? 'active' : '') }}"><a href="/dashboard/my_routines"><span class="fa fa-tasks fa-lg"></span>
              <span class="hidden-xs">&nbsp;&nbsp;My Routines</a></span>
            </li>
            <li class="{{ (Request::is('dashboard/start') ? 'active' : '') }}"><a href="/dashboard/start"><span class="fa fa-play fa-lg"></span>
              <span class="hidden-xs">&nbsp;&nbsp;Start Workout</span></a>
            </li>
            <li class="{{ (Request::is('dashboard/workouts') ? 'active' : '') }}"><a href="/dashboard/workouts"><span class="fa fa-table fa-lg"></span>
              <span class="hidden-xs">&nbsp;&nbsp;My Workouts</span></a>
            </li>
            <li>
              <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="fa fa-sign-out fa-lg"></span><span class="hidden-xs">&nbsp;&nbsp;Logout</span>
              </a>
            </li>
          @endif
        </ul>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          {{ csrf_field() }}
        </form>
      </div>
      <div id="app-data" class="col-xs-10 col-sm-10 col-sm-offset-2 main">
        @yield('content')
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="/js/app.js"></script>
  @yield('script')
</body>
</html>




        
