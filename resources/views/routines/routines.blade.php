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
        <th class="text-center">Update</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($routines as $value)
        <tr>
          <th>{{ $value->routine_name }}</th> 
          <td>N/A</td>
          <td class="hidden-sm hidden-xs">N/A</td>
          <td class="hidden-sm hidden-xs">{{ $value->created_at }}</td>
          <td class="text-center">
            <a class="viewRoutine pointer" data-toggle="modal" data-target="#viewRoutineModal">
              <input type="hidden" value="{{ $value->id }}">
              <span class="fa fa-pencil fa-lg"></span>
            </a>
          </td>
          <td class="text-center">
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

