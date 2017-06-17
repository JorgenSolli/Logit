@extends('layouts.app')

@section('content')
  <div class="card m-t-0 m-b-10">
    <div class="card-content">
    	<div class="row">
    		<div class="col-md-3 col-sm-6 col-xs-12 m-b-15-xs">
    			<a href="/dashboard/start" class="z-fix btn-fullwidth btn btn-lg btn-primary">
            <span class="btn-label">
              <i class="material-icons">keyboard_arrow_left</i>
            </span>
            Go back
          </a>
    		</div>
        <div class="col-md-3 col-sm-6 col-xs-12 m-b-15-xs">
          <a href="/clear" id="clearSession" class="z-fix btn-fullwidth btn btn-lg btn-danger">
            <span class="btn-label">
              <i class="material-icons">close</i>
              Cancel Workout
            </span>
          </a>
        </div>
    		<div class="col-md-6 col-sm-12 m-t-15-sm clear-sm">
    			<a href="/dashboard/workout/finish/{{ $routine_id }}" class="btn-fullwidth btn btn-lg btn-success">
            <span class="btn-label">
              <i class="fa fa-trophy fa-lg"></i>
            </span>
            Finish and save session
          </a>
    		</div>
    	</div>
    </div>
  </div>
  @include('notifications')
  <div id="exercises">
    <h2>Let's go! <small>Select an exercise</small></h2>
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