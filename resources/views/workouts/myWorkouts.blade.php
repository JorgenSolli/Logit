@extends('layouts.app')

@section('content')
  @include('notifications')
  <div id="viewWorkout" class="card-content">

  </div>

  <div id="workouts" class="card">
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
              <tr id="workout-{{ $workout->workout_id }}">
                <td>{{ Carbon\Carbon::parse($workout->created_at)->format('d M Y H:i') }}</td>
                <td class="name">{{ $workout->routine_name }}</td>
                <td class="text-center">
                  <a id="{{ $workout->workout_id }}" class="pointer deleteWorkout">
                    <span class="fa fa-trash-o fa-lg danger-color"></span>
                  </a>
                </td>
                <td class="text-center">
                  <a class="viewWorkout pointer">
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

@endsection

@section('script')
  <script src="{{ mix('/js/workouts.min.js') }}"></script>

@endsection
