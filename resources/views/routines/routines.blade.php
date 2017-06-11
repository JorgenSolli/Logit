@extends('layouts.app')

@section('content')

  <h1 class="page-header">Your Routines</h1>
  @include('notifications')

  <table class="table table-hover">
    <thead>
      <tr>
        <th>Routine Name</th>
        <th>Last used</th>
        <th class="hidden-sm hidden-xs">Times used</th> 
        <th class="hidden-sm hidden-xs">Created at</th>
        <th class="text-center">Delete</th>
        <th class="text-center">View/Edit</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($routines as $routine)
        <tr id="routine-{{ $routine->id }}">
          <th>
            @if ($routine->active == 0)
              <span class="fa fa-lock"></span> {{ $routine->routine_name }} (Inactive)
            @else
              {{ $routine->routine_name }}
            @endif
          </th> 
          <td>N/A</td>
          <td class="hidden-sm hidden-xs">N/A</td>
          <td class="hidden-sm hidden-xs">{{ $routine->created_at }}</td>
          <td class="text-center">
            <a class="pointer deleteRoutine" id="{{ $routine->id }}" data-toggle="modal" data-target="#OkDeleteModal">
              <span class="fa fa-trash-o fa-lg danger-color"></span>
            </a>
          </td>
          <td class="text-center">
            <a class="viewRoutine pointer" data-toggle="modal" data-target="#viewRoutineModal">
              <input type="hidden" value="{{ $routine->id }}">
              <span class="fa fa-pencil fa-lg success-color"></span>
            </a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <a class="btn btn-primary" href="my_routines/add_routine" role="button"><span class="fa fa-plus"></span> Add a routine</a>
  
  <div id="viewRoutineModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div id="modalData" class="modal-content">
      </div>
    </div>
  </div>

  <div id="OkDeleteModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Are you sure you wish to delete this routine?
            <br><small><b>All connected exercises will be deleted!</b></small>
          </h4>
        </div>
        <div class="modal-body">
          <div class="row text-center">
            <div class="col-sm-6">
              <button id="" class="okDelete btn btn-danger btn-large is-fullwidth" data-dismiss="modal">DELETE</button>
            </div>
            <div class="col-sm-6">
              <button class="btn btn-success btn-large is-fullwidth" data-dismiss="modal">CANCEL</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection

@section('script')
  <script src="/js/routines.js"></script>
@endsection

