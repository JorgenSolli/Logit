@extends('layouts.app')

@section('content')
<h2>Glad you decided to hit the gym today!<br><small>Select a routine to get started</small></h2>
<div id="routines">
    @if (session('gymming'))
        <input id="isGymming" type="hidden" name="isGymming" value="true">
    @endif

    <div class="row">
        @php $index = 0 @endphp
        @foreach($routines as $routine)
            @if ($index == 4)
                </div><div class="row">
                @php $index = 0 @endphp
            @endif
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card card-pricing card-raised">
                    @if (session('gymming') == $routine['id'])
                        <div class="ui ribbon label">In progress</div>
                    @endif
                    <div class="content">
                        <div class="icon icon-rose icon-left pull-left">
                            @foreach ($topMuscleGroup[$routine['id']] as $key => $value)
                                @if ($loop->first)
                                    @php $muscleImage = $key; break; @endphp
                                @endif
                            @endforeach
                            <img class="muscle-icon" src="/images/icons/muscle_groups/white/{{ $muscleImage }}.svg">
                        </div>
                        <h3 class="card-title m-t-15">
                            {{ $routine['routine_name'] }}
                        </h3>
                        <p class="card-description">
                            Last used: {{ $routine['last_used'] }}
                        </p>
                        <div class="row">
                            <div class="col-xs-6">
                                @if (session('gymming') == $routine['id'])
                                    <button data-href="start_workout/{{ $routine['id'] }}" class="startRoutine btn btn-success btn-fullwidth">Continue</button>
                                @else
                                    <button data-href="start_workout/{{ $routine['id'] }}" class="startRoutine btn btn-success btn-fullwidth">Start</button>
                                @endif
                            </div>
                            <div class="col-xs-6">
                                <button data-routine-preview="{{ $routine['id'] }}" class="btn btn-primary btn-fullwidth">Preview</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php $index++ @endphp
        @endforeach
    </div>
    @if ($nrInactive > 0)
        <div class="alert alert-info alert-with-icon">
            <i class="material-icons" data-notify="icon" >info_outline</i>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
            <span data-notify="message">
                You have {{ $nrInactive }} inactive 
                @if ($nrInactive == 1)
                    routine. 
                @else 
                    routines 
                @endif
            </span>
        </div>
    @else
        <div class="alert alert-info alert-with-icon">
            <i class="material-icons" data-notify="icon" >info_outline</i>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
            <span data-notify="message"> <strong>Quick tip!</strong> Any routines no longer in use? You can set them to inactive. Head over to <a href="/dashboard/my_routines">My Routines</a> and view/edit the ones you'd like to hide from this list. </span>
        </div>
    @endif
</div>
@endsection
<div id="previewModal"></div>
@section('script')
    <script src="{{ mix('/js/startWorkout.min.js') }}"></script>
@endsection