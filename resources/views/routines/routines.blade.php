@extends('layouts.app')

@section('content')
  @include('notifications')
  <div id="viewRoutine" class="card-body">
  </div>
  
    <div id="routines">
        <div class="card">
          <div class="card-header card-header-info card-header-icon">
            <div class="card-icon">
                <i class="material-icons">accessibility</i>
            </div>
            <h4 class="card-title">My Routines</h4>
        </div>
        <div class="card-body">
            <div class="toolbar"></div>
            <div class="material-datatables">
              <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                <thead>
                  <tr>
                    <th>Routine Name</th>
                    <th>Status</th>
                    <th>Last used</th>
                    <th>Times used</th> 
                    <th>Created at</th>
                    <th class="text-center disabled-sorting">Delete</th>
                    <th class="text-center disabled-sorting">View/Edit</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Routine Name</th>
                    <th>Status</th>
                    <th>Last used</th>
                    <th>Times used</th> 
                    <th>Created at</th>
                    <th class="text-center">Delete</th>
                    <th class="text-center">View/Edit</th>
                  </tr>
                </tfoot>
                <tbody>
                  @foreach ($routines as $routine)
                    <tr id="routine-{{ $routine['id'] }}">
                      <td class="routine-name">
                        {{ $routine['routine_name'] }}
                      </td>
                      <td>
                        @if ($routine['active'] == 0)
                          <span class="fal fa-lock"></span> Inactive
                        @else
                          <span class="fal fa-unlock"></span> Active
                        @endif
                      </td>
                      <td>
                        <span class="hidden">{{ $routine['last_used_sortdate'] }}</span>
                        {{ $routine['last_used'] }}
                      </td>
                      <td>{{ $routine['times_used'] }}</td>
                      <td>
                        <span class="hidden">{{ Carbon\Carbon::parse($routine['created_at'])->format('Y/m/d H:i') }}</span>
                        {{ Carbon\Carbon::parse($routine['created_at'])->format('d/m/Y H:i') }}
                      </td>

                      <td class="text-center">
                        <a class="pointer deleteRoutine" id="{{ $routine['id'] }}">
                          <span class="fal fa-trash fa-lg danger-color"></span>
                        </a>
                      </td>
                      <td class="text-center">
                        <a class="viewRoutine pointer" data-toggle="modal" data-target="#viewRoutineModal">
                          <input type="hidden" value="{{ $routine['id'] }}">
                          <span class="fal fa-pencil fa-lg success-color"></span>
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <a class="btn btn-primary" href="{{ url("routines/new_routine") }}" role="button"><span class="fal fa-plus"></span> Add a routine</a>
        </div>
    </div>

    @if (!$pending->isEmpty())
      <div class="card">
        <div class="card-header card-header-icon" data-background-color="green">
          <i class="material-icons">accessibility</i>
        </div>
        <div class="card-content">
          <h4 class="card-title">Routines shared with you</h4>
          <div class="toolbar"></div>
          <div class="material-datatables">
            <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
              <thead>
                <tr>
                  <th>Routine Name</th>
                  <th class="text-center disabled-sorting">View</th>
                  <th class="text-center disabled-sorting">Add to routines</th>
                  <th class="text-center disabled-sorting">Delete</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Routine Name</th>
                  <th class="text-center disabled-sorting">View</th>
                  <th class="text-center disabled-sorting">Add to routines</th>
                  <th class="text-center disabled-sorting">Delete</th>
                </tr>
              </tfoot>
              <tbody>
                @foreach ($pending as $routine)
                  <tr id="routine-{{ $routine['id'] }}">
                    <td class="routine-name">
                      {{ $routine['routine_name'] }}
                    </td>
                    <td class="text-center">
                      <a class="viewRoutine pointer" data-toggle="modal" data-target="#viewRoutineModal">
                        <input type="hidden" value="{{ $routine['id'] }}">
                        <span class="fal fa-pencil fa-lg success-color"></span>
                      </a>
                    </td>
                    <td class="text-center">
                      <a href="{{ url("/dashboard/my_routines/accept_routine/{$routine['id']}") }}" class="pointer">
                        <span class="fal fa-check fa-lg success-color"></span>
                      </a>
                    </td>
                    <td class="text-center">
                      <a class="pointer deleteRoutine" id="{{ $routine['id'] }}">
                        <span class="fal fa-trash fa-lg danger-color"></span>
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  @endif
@endsection

@section('script')
  <script src="{{ mix('/js/routines.min.js') }}"></script>
@endsection

