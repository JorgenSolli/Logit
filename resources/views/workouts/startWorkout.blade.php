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
    			<a id="finishWorkout" href="/dashboard/workout/finish/{{ $routine_id }}" class="btn-fullwidth btn btn-lg btn-success">
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
    @php 
      $printed_supersets = [];
    @endphp

    @foreach($exercises as $exercise)
      @if ($exercise->type == 'superset' && !in_array($exercise->superset_name, $printed_supersets))
        
        @if (session($exercise->superset_name))
          <a id="{{ $exercise->id }}" class="pointer list-group-item" data-status="incomplete">
            <span class="fa fa-clock-o"></span>&nbsp;
            {{ $exercise->superset_name }}
          </a>
        @else
          <a id="{{ $exercise->id }}" class="pointer list-group-item" data-status="completed">
            <span class="fa fa-check"></span>&nbsp;
            {{ $exercise->superset_name }}
          </a>
        @endif

        @php
          array_push($printed_supersets, $exercise->superset_name); 
        @endphp

      @elseif (!in_array($exercise->superset_name, $printed_supersets))
        
        @if (session($exercise->exercise_name))
          <a id="{{ $exercise->id }}" class="pointer list-group-item" data-status="incomplete">
            <span class="fa fa-clock-o"></span>&nbsp;
            {{ $exercise->exercise_name }}
          </a>
        @else
          <a id="{{ $exercise->id }}" class="pointer list-group-item" data-status="completed">
            <span class="fa fa-check"></span>&nbsp;
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
    <script>
      var timerHtml = '<div id="timer">' +
          '<div class="row">' +
            '<div class="col-xs-6 text-center">' +
              '<span id="timer-minutes">00</span>:<span id="timer-seconds">00</span>' +
            '</div>' +
            '<div class="col-xs-3">' +
              '<i id="timer-play" class="fa fa-play"></i>' +
            '</div>' +
            '<div class="col-xs-3">' +
              '<i id="timer-reset" class="fa fa-repeat"></i>' +
            '</div>' +
          '</div>' +
        '</div>';

      $("#app").append(timerHtml);
      $("footer.footer").addClass("hasTimer");

      var minutes = 0;
      var seconds = 0;
      var timerMinutes = $("#timer-minutes");
      var timerSeconds = $("#timer-seconds");

      var intervarSettings = function() {
        seconds++;
        
        if (seconds < 60 && minutes < 1) {
          timerMinutes.html('00');
        }
        if (seconds < 10) {
          seconds = ('0' + seconds).slice(-2); 
        }
        if (seconds === 60) {
          seconds = '00';
          minutes++;
        }
        if (minutes < 10) {
          minutes = ('0' + minutes).slice(-2);
        }

        timerSeconds.html(seconds);
        timerMinutes.html(minutes);
      }

      var countSeconds;

      var operators = function(method) {
        if (method === "pause") {
          window.clearInterval(countSeconds);
        }

        else if (method === "play") {
          countSeconds = setInterval(function() {
            intervarSettings();
          }, 1000);
        }

        else if (method === "reset") {
          minutes = 0;
          seconds = 0;
          timerMinutes.html('00');
          timerSeconds.html('00');
        }
      };
      
      $(document).on('click', '#timer-play', function() {
        $(this).removeClass("fa-play").addClass('fa-pause').attr('id', "timer-pause");
        operators("play");
      });

      $(document).on('click', '#timer-pause', function() {
        $(this).removeClass("fa-pause").addClass('fa-play').attr('id', "timer-play");
        operators("pause");
      });

      $(document).on('click', '#timer-reset', function() {
        operators("reset");
      });
    </script>
  @endif
@endsection