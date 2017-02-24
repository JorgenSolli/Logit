@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
          <li><a href="/dashboard"><span class="fa fa-tachometer fa-lg"></span>&nbsp;&nbsp;Dashboard</a></li>
          <li class="active"><a><span class="fa fa-tasks fa-lg"></span>&nbsp;&nbsp;My Routines</a></li>
          <li><a href="/dashboard/start"><span class="fa fa-play fa-lg"></span>&nbsp;&nbsp;Start Workout</a></li>
          <li><a href="/dashboard/workouts"><span class="fa fa-table fa-lg"></span>&nbsp;&nbsp;My Workouts</a></li>
        </ul>
      </div>
      <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1 class="page-header">Your Routines</h1>
        @include('notifications')

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
                  <a class="viewRoutine pointer" data-toggle="modal" data-target="#viewRoutineModal">
                    <input type="hidden" value="{{ $value->id }}">
                    <span class="fa fa-pencil fa-lg"></span>
                  </a>
                  &nbsp;&nbsp;&nbsp;
                  <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('delete-routine{{ $value->id }}').submit();">
                    <span class="fa fa-trash-o fa-lg"></span>
                  </a>
                  <form id="delete-routine{{ $value->id }}" action="/dashboard/my_routines/{{ $value->id }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <a class="btn btn-primary" href="my_routines/add_routine" role="button"><span class="fa fa-plus"></span> Add a routine</a>
      </div>
    </div>
  </div>

  <div id="viewRoutineModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div id="modalData" class="modal-content">
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script src="/js/routines.js"></script>
@endsection

