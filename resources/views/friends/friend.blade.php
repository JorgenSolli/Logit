@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="blue">
                    <i class="material-icons">person</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title">Friendship with {{ $friend->name }}</h4>

                    <a id="share-exercises" class="btn btn-danger">
                        <i class="material-icons">close</i> Remove friend
                    </a>

                </div>
                <div id="workoutActivityChart" class="ct-chart"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="blue">
                    <i class="material-icons">share</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title">Share routines</h4>

                    <form action="/dashboard/friends/shareRoutine" method="POST">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-9">
                                <select id="statistics-type" name="routine" class="selectpicker" data-style="btn btn-primary" title="Select a routine" data-size="7">
                                    @foreach ($routines as $routine)
                                        <option value="{{ $routine->id }}">{{ $routine->routine_name }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="friend" value="{{ $friend->id }}" />
                            </div>
                            <div class="col-sm-3">
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

    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="blue">
                    <i class="material-icons">compare_arrows</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title">Compare exercises</h4>
                </div>
                <div id="workoutActivityChart" class="ct-chart"></div>
            </div>
        </div>
    </div>


@endsection

@section('script')
  <script src="{{ mix('/js/friends.min.js') }}"></script>
@endsection