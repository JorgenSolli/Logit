@extends('layouts.app')

@section('content')
  <h2>Glad you decided to hit the gym today!<br><small>Please select a routine</small></h2>
  <div id="routines" class="list-group">
    @foreach($routines as $routine)
      <a href="start/{{ $routine->id }}" class="list-group-item">
        {{ $routine->routine_name }}
        @if (session('gymming') == $routine->id)
          &nbsp;&nbsp;·&nbsp;&nbsp;<span class="fa fa-clock-o"></span><small> In progress </small>
        @endif
      </a>
    @endforeach
  </div>
@endsection