@extends('layouts.app')

@section('content')
    @include('notifications')
    <div id="viewWorkout" class="card-body"></div>

    <div id="workouts" class="card">
        <div class="card-header card-header-icon" data-background-color="green">
            <i class="material-icons">view_list</i>
        </div>
        <div class="card-body">
            <h4 class="card-title">My Workouts</h4>
            <div class="toolbar"></div>
            <div class="material-datatables">
                <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Routine Name</th>
                            <th class="text-center disabled-sorting">Recap</th>
                            <th class="text-center disabled-sorting">View/Edit</th>
                            <th class="text-center disabled-sorting">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workouts as $workout)
                            <tr id="workout-{{ $workout->workout_id }}">
                                <td>
                                    <span class="hidden">{{ Carbon\Carbon::parse($workout->created_at)->format('Y/m/d H:i') }}</span>
                                    {{ Carbon\Carbon::parse($workout->created_at)->format('d/m/Y H:i') }}
                                </td>
                                <td class="name">{{ $workout->routine_name }}</td>
                                <td class="text-center">
                                    <a href="{{ url("/workouts/{$workout->workout_id}/recap") }}" class="pointer">
                                        <span class="fal fa-flag-checkered fa-lg primary-color"></span>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ url("/workouts/{$workout->workout_id}") }}" class="pointer">
                                        <span class="fal fa-pencil fa-lg success-color"></span>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a id="{{ $workout->workout_id }}" class="pointer deleteWorkout">
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
@endsection

@section('script')
    <script src="{{ mix('/js/workouts.min.js') }}"></script>
@endsection
