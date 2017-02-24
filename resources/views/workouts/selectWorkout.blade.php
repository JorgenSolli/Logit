@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
          <li><a href="/dashboard"><span class="fa fa-tachometer fa-lg"></span>&nbsp;&nbsp;Dashboard</a></li>
          <li><a href="/dashboard/my_routines"><span class="fa fa-tasks fa-lg"></span>&nbsp;&nbsp;My Routines</a></li>
          <li class="active"><a href="/dashboard/start"><span class="fa fa-play fa-lg"></span>&nbsp;&nbsp;Start Workout</a></li>
          <li><a href="/dashboard/workouts"><span class="fa fa-table fa-lg"></span>&nbsp;&nbsp;My Workouts</a></li>
        </ul>
      </div>
      <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h2>Glad you decided to hit the gym today!<br><small>Please select a routine</small></h2>
        <div id="routines" class="list-group">
          @foreach($routines as $routine)
            <a href="start/{{ $routine->id }}" class="list-group-item">
              {{ $routine->routine_name }}
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
@endsection