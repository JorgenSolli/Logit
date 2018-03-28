@extends('layouts.app')

@section('content')
    <div class="card m-t-0 m-b-10">
        <div class="card-body">
        	<div class="row">
        		<div class="col-md-3 col-sm-6 col-xs-12 m-b-15-xs">
        			<a href="/start-workout" class="z-fix btn-fullwidth btn btn-lg btn-primary">
                        <span class="btn-label">
                            <i class="material-icons">keyboard_arrow_left</i>
                        </span>
                        Back to routines
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 m-b-15-xs">
                    <a href="{{ url("/start-workout/session/clear") }}" id="clearSession" class="z-fix btn-fullwidth btn btn-lg btn-danger">
                        <span class="btn-label">
                            <i class="material-icons">close</i>
                            Cancel Workout
                        </span>
                    </a>
                </div>
        		<div class="col-md-6 col-sm-12 m-t-15-sm clear-sm">
        			<a id="finishWorkout" href="{{ url("/start-workout/{$routine_id}/finish") }}" class="btn-fullwidth btn btn-lg btn-success">
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
  
    <div id="exercises" class="card">
        <div class="card-header">
            <h4 class="card-title">Let's go!</h4>
            <p class="category mb-0">Select an exercise</p>
        </div>
        @php 
          $printed_supersets = [];
        @endphp

        <div class="card-body pt-0">
            @foreach($exercises as $exercise)
                @if ($exercise->type == 'superset' && !in_array($exercise->superset_name, $printed_supersets))
                    @if (session($exercise->superset_name))
                        <button id="{{ $exercise->id }}" class="exercise w-100 text-left btn btn-primary" data-status="incomplete">
                            <i data-icon="status"></i> {{ $exercise->superset_name }}
                        </button>
                    @else
                        <button id="{{ $exercise->id }}" class="exercise w-100 text-left btn btn-success disabled" data-status="completed">
                            <i data-icon="status"></i> {{ $exercise->superset_name }}
                        </button>
                    @endif
                    @php
                        array_push($printed_supersets, $exercise->superset_name); 
                    @endphp
                @elseif (!in_array($exercise->superset_name, $printed_supersets))
                    @if (session($exercise->exercise_name))
                        <button id="{{ $exercise->id }}" class="exercise w-100 text-left btn btn-primary" data-status="incomplete">
                            <i data-icon="status"></i> {{ $exercise->exercise_name }}
                        </button>
                    @else
                        <button id="{{ $exercise->id }}" class="exercise w-100 text-left btn btn-success disabled" data-status="completed">
                            <i data-icon="status"></i> {{ $exercise->exercise_name }}
                        </button>
                    @endif
                @endif
            @endforeach
        </div>
    </div>
    <div id="data"></div>
@endsection

@section('script')
  <script src="{{ mix('/js/startWorkout.min.js') }}"></script>

  @if ($settings->use_timer === 1)
    <script>
      var timerSettings = {
        direction: '{{ $timerSettings['direction']}}',
        play_sound: {{ $timerSettings['play_sound']}},
        seconds: {{ $timerSettings['seconds']}},
        minutes: {{ $timerSettings['minutes']}},
      }
    </script>
    <script src="{{ mix('/js/timer.min.js') }}"></script>
  @endif
@endsection