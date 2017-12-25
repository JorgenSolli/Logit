@extends('layouts.app')

@section('content')
  @include('notifications')
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-header card-header-icon" data-background-color="blue">
          <i class="material-icons">timer</i>
        </div>
        <div class="card-content">
          <h4 class="card-title">Time spendt</h4>

          <div class="data-text text-center">
            <h1 id="avg_hour" class="m-b-0">
              {{ $time }}
            </h1>
            <h1 class="m-t-0">
              <small>Hour/Minute</small>
            </h1>
          </div>

        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header card-header-icon" data-background-color="blue">
          <i class="material-icons">timelapse</i>
        </div>
        <div class="card-content">
          <h4 class="card-title">Average rest time</h4>

          <div class="data-text text-center">
            <h1 class="m-b-0">{{ $avgRestTime }}</h1>
            <h1 class="m-t-0">
              @if ($avgRestTime > 1)
                <small>Minutes/Seconds</small>
              @else
                <small>Minute/Seconds</small>
              @endif
            </h1>
          </div>

        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header card-header-icon" data-background-color="blue">
          @if ($totalExercises > 9)
            <i class="material-icons">filter_9_plus</i>

          @else
            <i class="material-icons">filter_{{ $totalExercises }}</i>
          @endif
        </div>
        <div class="card-content">
          <h4 class="card-title">Exercises Completed</h4>

          <div class="data-text text-center">
            <h1 class="m-b-0">{{ $totalExercises }}</h1>
            <h1 class="m-t-0">
              <small>Exercises</small>
            </h1>
          </div>

        </div>
      </div>
    </div>
  </div> <!-- /row -->
  <div class="row">
    <div class="col-xs-6">
      <a href="/dashboard/workouts" class="btn btn-primary btn-fullwidth">Back to your Workouts</a>
    </div>
    <div class="col-xs-6">
      <a href="/dashboard" class="btn btn-primary btn-fullwidth">To Dashboard</a>
    </div>
  </div>

@endsection

@section('script')
  <script src="{{ mix('/js/workouts.min.js') }}"></script>

@endsection
