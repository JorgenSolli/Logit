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
            Back to routines
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
    			<a id="finishWorkout" href="/dashboard/workout/finish/{{ $routine_id }}" class="btn-fullwidth btn btn-lg btn-success">
            <span class="btn-label">
              <i class="fal fa-trophy fa-lg"></i>
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
    @php 
      $printed_supersets = [];
    @endphp

    @foreach($exercises as $exercise)
      @if ($exercise->type == 'superset' && !in_array($exercise->superset_name, $printed_supersets))
        
        @if (session($exercise->superset_name))
          <a id="{{ $exercise->id }}" class="pointer list-group-item" data-status="incomplete">
            <span data-icon="status"></span>&nbsp;
            {{ $exercise->superset_name }}
          </a>
        @else
          <a id="{{ $exercise->id }}" class="pointer list-group-item" data-status="completed">
            <span data-icon="status"></span>&nbsp;
            {{ $exercise->superset_name }}
          </a>
        @endif

        @php
          array_push($printed_supersets, $exercise->superset_name); 
        @endphp

      @elseif (!in_array($exercise->superset_name, $printed_supersets))
        
        @if (session($exercise->exercise_name))
          <a id="{{ $exercise->id }}" class="pointer list-group-item" data-status="incomplete">
            <span data-icon="status"></span>&nbsp;
            {{ $exercise->exercise_name }}
          </a>
        @else
          <a id="{{ $exercise->id }}" class="pointer list-group-item" data-status="completed">
            <span data-icon="status"></span>&nbsp;
            {{ $exercise->exercise_name }}
          </a>
        
        @endif
      @endif

    @endforeach
  </div>
  <div id="data"></div>
@endsection

@section('script')
  <script src="{{ mix('/js/workouts.min.js') }}"></script>

  @if ($settings->use_timer === 1)
    <script src="{{ mix('/js/timer.min.js') }}"></script>
  @endif
@endsection