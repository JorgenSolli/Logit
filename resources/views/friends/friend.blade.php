@extends('layouts.app')
@section('content')
    <input type="hidden" id="user_id" name="user_id" value="{{ $friend->id }}">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="blue">
                    <i class="material-icons">person</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title">Friendship with <span id="name">{{ $friend->name }}</span></h4>
                    <div class="row">
                        <div class="col-md-6">
                            Friends since {{ Carbon\Carbon::parse($friend->created_at)->format('d M Y') }}
                        </div>
                        <div class="col-md-6">
                            @if ($latestActivity)
                                Latest activity: {{ $latestActivity->activity }} ({{ Carbon\Carbon::parse($latestActivity->created_at)->diffForHumans() }})
                            @endif
                        </div>
                    </div>
                    <a id="removeFriend" class="btn btn-danger">
                        <i class="material-icons">close</i> Remove friend
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="blue">
                    <i class="material-icons">share</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title">Share your routines with {{ $friend->name }}</h4>

                    <form action="/dashboard/friends/shareRoutine" method="POST">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6 col-xs-6">
                                <select id="routine" name="routine" class="selectpicker" data-style="btn btn-primary" title="Select a routine" data-size="7">
                                    @foreach ($routines as $routine)
                                        <option value="{{ $routine->id }}">{{ $routine->routine_name }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="friend" value="{{ $friend->id }}" />
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <button type="submit" id="share-routine" class="btn btn-success btn-fullwidth">
                                    <i class="material-icons">share</i> Share 
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <h4 class="p-l-md">Select time period for statistics</h4>
    <div class="row">
        <div class="col-md-2 sm-6 col-xs-6">
            <select id="statistics-type" class="selectpicker" data-style="btn btn-primary" title="Single Select" data-size="7">
                <option disabled> Choose period</option>
                <option value="year">Year</option>
                <option value="months" selected>Month</option>
            </select>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-6">
            <select id="statistics-year" class="selectpickerAjax" data-style="btn btn-primary" title="Single Select" data-size="7"></select>
        </div>
        <div class="col-md-2 col-sm-12 col-xs-12">
            <select id="statistics-month" class="selectpickerAjax" data-style="btn btn-primary" title="Single Select" data-size="7"></select>
        </div>
    </div>

    <div class="card">
        <div class="card-header card-header-icon" data-background-color="blue">
            <i class="material-icons">timeline</i>
        </div>
        <div class="card-content">
            <h4 class="card-title">Workout Activity</h4>
        </div>
        <div style="position: relative; width: 100%; height: 200px">
            <canvas id="workoutActivityChart"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="blue">
                    <i class="material-icons">compare_arrows</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title">Compare exercises</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <select id="your_exercises" data-style="btn btn-primary" title="Your exercises" data-live-search="true"></select>
                            
                            <div style="position: relative; width: 100%; height: 0px">
                                <canvas id="your_exercise"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <select id="friend_exercises" data-style="btn btn-primary" title="{{ $friend->name }}'s exercises" data-live-search="true"></select>
                            
                            <div style="position: relative; width: 100%; height: 0px">
                                <canvas id="friend_exercise"></canvas>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ mix('/js/friend.min.js') }}"></script>
@endsection