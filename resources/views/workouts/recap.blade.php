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
                            @if ($hasPrevious)
                                <span class="up-or-down">
                                    @if ($timeLess)
                                        <i class="far fa-angle-down danger-color"></i>
                                    @else
                                        <i class="far fa-angle-up success-color"></i>
                                    @endif
                                </span>
                            @endif
                        </h1>
                        @if ($hasPrevious)
                            <p style="margin-bottom: 0">
                                @if ($timeLess)
                                    Down from previous {{ $lastTime }}
                                @else
                                    Up from previous {{ $lastTime }}
                                @endif
                            </p>
                        @endif
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
                        <h1 class="m-b-0">
                            {{ $avgRestTime }}
                            @if ($hasPrevious)
                                <span class="up-or-down">
                                    @if ($timeLess)
                                        <i class="far fa-angle-down danger-color"></i>
                                    @else
                                        <i class="far fa-angle-up success-color"></i>
                                    @endif
                                </span>
                            @endif
                        </h1>
                        @if ($hasPrevious)
                            <p style="margin-bottom: 0">
                                @if ($timeLess)
                                    Down from previous {{ $lastAvgRestTime }}
                                @else
                                    Up from previous {{ $lastAvgRestTime }}
                                @endif
                            </p>
                        @endif
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
                        <h1 class="m-b-0">
                            {{ $totalExercises }}
                            @if ($hasPrevious && $totalExercises !== $lastTotalExercises)
                                <span class="up-or-down">
                                    @if ($timeLess)
                                        <i class="far fa-angle-down danger-color"></i>
                                    @else
                                        <i class="far fa-angle-up success-color"></i>
                                    @endif
                                </span>
                            @endif
                        </h1>
                        <p style="margin-bottom: 0">
                            @if ($hasPrevious && $totalExercises !== $lastTotalExercises)
                                @if ($timeLess)
                                    Down from previous {{ $lastTotalExercises }}
                                @else
                                    Up from previous {{ $lastTotalExercises }}
                                @endif
                            @else
                                Previous {{ $lastTotalExercises }}
                            @endif
                        </p>
                        <h1 class="m-t-0">
                            <small>Exercises</small>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /row -->
    
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="blue">
                    <i class="fal fa-dumbbell fa-2x"></i>
                </div>
                <div class="card-content">
                    <h4 class="card-title">Total weight lifted</h4>
                    <div class="data-text text-center">
                        <h1 class="m-b-0">
                            {{ $totalLifted }} {{ $units }}
                            @if ($hasPrevious)
                                <span class="up-or-down">
                                    @if ($totalLifted < $lastTotalLifted)
                                        <i class="far fa-angle-down danger-color"></i>
                                    @else
                                        <i class="far fa-angle-up success-color"></i>
                                    @endif
                                </span>
                            @endif
                        </h1>
                        @if ($hasPrevious)
                            <p style="margin-bottom: 0">
                                @if ($totalLifted < $lastTotalLifted)
                                    Down from previous {{ $lastTotalLifted }} {{ $units }}
                                @else
                                    Up from previous {{ $lastTotalLifted }} {{ $units }}
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <a href="{{ url("/workouts") }}" class="btn btn-primary btn-fullwidth">Back to your Workouts</a>
        </div>
        <div class="col-sm-6">
            <a href="{{ url("/dashboard") }}" class="btn btn-primary btn-fullwidth">To Dashboard</a>
        </div>
    </div>

@endsection

@section('script')
  <script src="{{ mix('/js/workouts.min.js') }}"></script>
@endsection
