@extends('layouts.app')

@section('content')
  <h1 class="page-header">Your Workouts</h1>
  @include('notifications')

  <table class="table table-hover">
    <thead>
      <tr>
        <th>Date</th>
        <th>Routine Name</th>
        <th class="text-center">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($workouts as $workout)
        <tr>
          <th>{{ $workout->created_at }}</th>
          <td>{{ $workout->routine_name }}</td>
          <td class="text-center">
            <a class="viewRoutine pointer" data-toggle="modal" data-target="#viewRoutineModal">
              <input type="hidden" value="{{ $workout->workout_id }}">
              <span class="fa fa-pencil fa-lg"></span>
            </a>
            &nbsp;&nbsp;&nbsp;
            <a onclick="event.preventDefault(); document.getElementById('delete-routine{{ $workout->workout_id }}').submit();">
              <span class="fa fa-trash-o fa-lg"></span>
            </a>
            <form id="delete-routine{{ $workout->workout_id }}" action="/dashboard/my_routines/{{ $workout->workout_id }}" method="POST" style="display: none;">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

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
