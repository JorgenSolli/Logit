@extends('layouts.app')

@section('content')
  <h2>Glad you decided to hit the gym today!<br><small>Please select a routine</small></h2>
  <div id="routines">
    <div class="list-group m-b-15">
      @foreach($routines as $routine)
        @if ($routine->active == 1)
          <a href="start/{{ $routine->id }}" class="list-group-item">
            {{ $routine->routine_name }}
            @if (session('gymming') == $routine->id)
              &nbsp;&nbsp;·&nbsp;&nbsp;<span class="fa fa-clock-o"></span><small> In progress </small>
            @endif
          </a>
        @endif
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