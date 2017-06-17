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
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" rel="stylesheet" type="text/css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
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

  <!--   Core JS Files   -->
  <script src="/js/jquery-3.1.1.min.js"></script>
  <script src="/js/jquery-ui.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <script src="/js/jquery.ui.touch-punch.min.js"></script>
  <script src="/js/material.min.js" type="text/javascript"></script>
  <script src="/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>

  <!-- Forms Validations Plugin -->
  <script src="/js/jquery.validate.min.js"></script>
  <!--  Plugin for Date Time Picker and Full Calendar Plugin-->
  <script src="/js/moment.min.js"></script>
  <!--  Charts Plugin -->
  <script src="/js/chartist.min.js"></script>
  <!--  Plugin for the Wizard -->
  <script src="/js/jquery.bootstrap-wizard.js"></script>
  <!--  Notifications Plugin    -->
  <script src="/js/bootstrap-notify.js"></script>
  <!-- DateTimePicker Plugin -->
  <script src="/js/bootstrap-datetimepicker.js"></script>
  <!-- Vector Map plugin -->
  <script src="/js/jquery-jvectormap.js"></script>
  <!-- Sliders Plugin -->
  <script src="/js/nouislider.min.js"></script>
  <!-- Select Plugin -->
  <script src="/js/jquery.select-bootstrap.js"></script>
  <!--  DataTables.net Plugin    -->
  <script src="/js/jquery.datatables.js"></script>
  <!-- Sweet Alert 2 plugin -->
  <script src="/js/sweetalert2.js"></script>
  <!--  Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
  <script src="/js/jasny-bootstrap.min.js"></script>
  <!--  Full Calendar Plugin    -->
  <script src="/js/fullcalendar.min.js"></script>
  <!-- TagsInput Plugin -->
  <script src="/js/jquery.tagsinput.js"></script>
  <!-- Material Dashboard javascript methods -->
  <script src="/js/material-dashboard.js"></script>
  <!-- Material Dashboard DEMO methods, don't include it in your project! -->
  <script src="/js/demo.js"></script>
  @yield('script')
</body>
</html>




        
