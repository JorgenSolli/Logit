@extends('layouts.app')

@section('content')
  <h2>Glad you decided to hit the gym today!<br><small>Please select a routine</small></h2>
  <div id="routines" class="list-group">
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
    
    <br>
    
    @if ($nrInactive > 0)
      <div class="alert alert-info">
        You have {{ $nrInactive }} inactive 
        @if ($nrInactive == 1)
          routine. 
        @else 
          routines 
        @endif
      </div>
    @else
      <div class="alert alert-info">
        <strong>Quick tip!</strong> Any routines no longer in use? You can set them to inactive. Head over to <a href="/dashboard/my_routines">My Routines</a> and view/edit the ones you'd like to hide from this list.
      </div>
    @endif
  </div>
@endsection