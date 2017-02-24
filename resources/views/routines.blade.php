@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
      <ul class="nav nav-sidebar">
        <li><a href="/dashboard"><span class="fa fa-tachometer fa-lg"></span>&nbsp;&nbsp;Dashboard</a></li>
        <li class="active"><a><span class="fa fa-tasks fa-lg"></span>&nbsp;&nbsp;My Routines</a></li>
        <li><a href="#"><span class="fa fa-play fa-lg"></span>&nbsp;&nbsp;Start Workout</a></li>
        <li><a href="#"><span class="fa fa-pencil fa-lg"></span>&nbsp;&nbsp;Edit Workouts</a></li>
      </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <h1 class="page-header">Your Routines</h1>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Routine Name</th>
            <th>Last used</th>
            <th>Times used</th> 
            <th>Created at</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($routines as $value)
            <tr>
              <th>{{ $value->routine_name }}</th> 
              <td>N/A</td>
              <td>N/A</td>
              <td>{{ $value->created_at }}</td>
              <td class="text-center">
                <span class="fa fa-pencil fa-lg"></span>
                &nbsp;&nbsp;&nbsp;
                <span class="fa fa-trash-o fa-lg"></span>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <a class="btn btn-primary" href="my_routines/add_routine" role="button"><span class="fa fa-plus"></span> Add a routine</a>
    </div>
  </div>
</div>
@endsection
