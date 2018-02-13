@extends('layouts.app')

@section('content')
  <h2>Glad you decided to hit the gym today!<br><small>Please select a routine</small></h2>
  <div id="routines">
    <div class="row">
      @foreach($routines as $routine)
        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="card card-pricing card-raised">
            <div class="content">
              <div class="icon icon-rose">
                @foreach ($topMuscleGroup[$routine['id']] as $key => $value)
                  @if ($loop->first)
                    @php $muscleImage = $key; break; @endphp
                  @endif
                @endforeach
                <img class="muscle-icon" src="/images/icons/muscle_groups/white/{{ $muscleImage }}.svg">
              </div>
              <h3 class="card-title">
                {{ $routine['routine_name'] }}
                @if (session('gymming') == $routine['id'])
                  ·&nbsp;<span class="fal fa-clock"></span><small> In progress </small>
                @endif
              </h3>
              <p class="card-description">
                  Last used: {{ $routine['last_used'] }}
              </p>
              @if (session('gymming') == $routine['id'])
                <button data-href="start/{{ $routine['id'] }}" class="startRoutine btn btn-success">Continue</button>
              @else
                <button data-href="start/{{ $routine['id'] }}" class="startRoutine btn btn-success">Start</button>
              @endif
              <button data-routine-preview="{{ $routine['id'] }}" class="btn btn-primary">Preview</button>
            </div>
          </div>
        </div>
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
        @endif</span>
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
  <script src="{{ mix('/js/workouts.min.js') }}"></script>
@endsection