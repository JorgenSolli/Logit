@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-md-3 col-sm-6 col-xs-6 m-b-15-xs">
			<a href="/dashboard/start" class="btn-fullwidth btn btn-primary"><span class="fa fa-angle-left fa-lg"></span>&nbsp;&nbsp;Go back</a>
		</div>
    <div class="col-md-3 col-sm-6 col-xs-6 m-b-15-xs">
      <a href="/clear" class="btn-fullwidth btn btn-danger"><span class="fa fa-times fa-lg"></span>&nbsp;&nbsp;Cancel Workout</a>
    </div>
		<div class="col-md-6 col-sm-12 m-t-15-sm">
			<a href="/dashboard/workout/finish/{{ $routine_id }}" class="btn-fullwidth btn btn-success"><span class="fa fa-trophy fa-lg"></span>&nbsp;&nbsp;Finish and save session</a>
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
@endsection

@section('script')
  <script src="/js/workouts.js"></script>
@endsection