@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
      <ul class="nav nav-sidebar">
        <li class="active"><a><span class="fa fa-tachometer fa-lg"></span>&nbsp;&nbsp;Dashboard</a></li>
        <li><a href="/dashboard/my_routines"><span class="fa fa-tasks fa-lg"></span>&nbsp;&nbsp;My Routines</a></li>
        <li><a href="#"><span class="fa fa-play fa-lg"></span>&nbsp;&nbsp;Start Workout</a></li>
        <li><a href="#"><span class="fa fa-pencil fa-lg"></span>&nbsp;&nbsp;Edit Workouts</a></li>
      </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <h1 class="page-header">Welcome {{ $brukerinfo->name }}</h1>
    </div>
  </div>
</div>
@endsection
