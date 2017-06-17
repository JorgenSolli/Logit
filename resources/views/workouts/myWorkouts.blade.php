@extends('layouts.app')

@section('content')
  @include('notifications')

  <div class="card">
    <div class="card-header card-header-icon" data-background-color="green">
      <i class="material-icons">view_list</i>
    </div>
    <div class="card-content">
      <h4 class="card-title">My Workouts</h4>
      <div class="toolbar"></div>
      <div class="material-datatables">
        <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
          <thead>
            <tr>
              <th>Date</th>
              <th>Routine Name</th>
              <th class="text-center disabled-sorting">Delete</th>
              <th class="text-center disabled-sorting">View/Edit</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($workouts as $workout)
              <tr>
                <td>{{ $workout->created_at }}</td>
                <td>{{ $workout->routine_name }}</td>
                <td class="text-center">
                  <a class="pointer" onclick="event.preventDefault(); document.getElementById('delete-routine{{ $workout->workout_id }}').submit();">
                    <span class="fa fa-trash-o fa-lg danger-color"></span>
                  </a>
                  <form action="/dashboard/my_workouts/{{ $workout->workout_id }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                  </form>
                </td>
                <td class="text-center">
                  <a class="viewWorkout pointer" data-toggle="modal" data-target="#viewWorkoutModal">
                    <input type="hidden" value="{{ $workout->workout_id }}">
                    <span class="fa fa-pencil fa-lg success-color"></span>
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div id="viewWorkoutModal" class="modal modal-large fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div id="modalData" class="modal-content">
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script src="/js/workouts.js"></script>
@endsection
