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
      	<div class="row">
      		<div class="col-xs-4">
      			<a href="/dashboard/start" class="btn-fullwidth btn btn-primary"><span class="fa fa-angle-left fa-lg"></span>&nbsp;&nbsp;Go back</a>
      		</div>
      		<div class="col-xs-8">
      			<a href="/dashboard/workout/finish" class="btn-fullwidth btn btn-success"><span class="fa fa-trophy fa-lg"></span>&nbsp;&nbsp;Finish and save session</a>
      		</div>
      	</div>
        <h2>Let's go! <small>Select an exercise</small></h2>
        @include('notifications')
        <div id="exercises">
          @foreach($exercises as $exercise)
          	<a id="{{ $exercise->id }}" class="pointer list-group-item">
          		@if (session($exercise->exercise_name))
			        	<span class="fa fa-clock-o"></span>&nbsp;
		        	@else
		        		<span class="fa fa-check"></span>&nbsp;
			        @endif
              {{ $exercise->exercise_name }}
            </a>
          @endforeach
        </div>
        <div id="data"></div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script src="/js/workouts.js"></script>
@endsection