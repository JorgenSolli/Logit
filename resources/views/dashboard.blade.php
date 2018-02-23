@extends('layouts.app')

@section('content')
@if (!$hasWorkouts)
    <span class="no-data-text">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">This dashboard is awesome!</h4>
            </div>
            <div class="card-content">
                <p>But not without any data. Once you complete one workout, this page will be populated with data in all its glory.</p>

                {{-- Enable this when hints are actually added (merged from feature/intro-guide)  
                <p>Need help getting started? <button id="init-hints" class="btn btn-xs btn-primary">Show hints</button></p>
                --}}
            </div>
        </div>
    </span>
@endif

<div id="dashboard" class="{{ $hasWorkouts ? "" : "dashboard-no-data"}}">
    <div class="row">
        <div class="col-xs-12">
            <h4 class="p-l-md">Show statistics for</h4>
        </div>
    </div>

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
        <div style="position: relative; width: 100%; height: 200px" class="{{ $hasWorkouts ? "" : "dashboard-no-data"}}">
            <canvas id="workoutActivityChart"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <div class="row">
                <div class="col-xs-12">
                    <div class="card">
                        <div class="card-header card-header-icon" data-background-color="blue">
                            <i class="material-icons">timer</i>
                        </div>
                        <div class="card-content">
                            <div class="clearfix">
                                <h4 class="card-title pull-left">Average workout time</h4>
                            </div>
                            <div class="data-text text-center {{ $hasWorkouts ? "" : "dashboard-no-data"}}">
                                <h2 id="avg_hour" class="m-b-0 m-t-0">
                                    <span id="avg_hr"></span><small id="avg_hr_label"></small>
                                    <span id="avg_min"></span><small id="avg_min_label"></small>
                                </h2>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p>Workouts that lasted less then 10 minutes does not affect your average time.</p>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="card m-t-10">
                        <div class="card-header card-header-icon" data-background-color="blue">
                            <i class="material-icons">thumbs_up_down</i>
                        </div>
                        <div class="card-content">
                            <div class="clearfix">
                                <h4 class="card-title pull-left">Session Completion Ratio</h4>
                            </div>
                            <div class="data-text text-center {{ $hasWorkouts ? "" : "dashboard-no-data"}}">
                                <h1 class="m-b-0 m-t-0">
                                    <span id="completion_rate"></span><span id="completion_rate_label">%</span>
                                </h1>

                                <div id="completion_rate_bar" class="progress progress-line-primary">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-4 col-md-5">
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="blue">
                    <i class="material-icons">donut_large</i>
                </div>


                <div class="card-content {{ $hasWorkouts ? "" : "dashboard-no-data"}}">
                    <h4 class="card-title">Musclegroups worked out (in percent)</h4>
                    <div style="position: relative; width: 100%; height: 205px">
                        <canvas id="musclegroupsPiechart"></canvas>
                    </div>
                </div>

                <div class="card-footer {{ $hasWorkouts ? "" : "dashboard-no-data"}}">
                    <h6>Legend</h6>
                    <div class="row">
                        <div class="col-xs-12">
                            <p>
                                <i class="fas fa-circle ct-legend-a"></i>
                                 Back <span id="0-percent"></span>
                            </p>
                            <p>
                                <i class="fas fa-circle ct-legend-b"></i>
                                 Biceps <span id="1-percent"></span>
                            </p>
                            <p>
                                <i class="fas fa-circle ct-legend-c"></i>
                                 Triceps <span id="2-percent"></span>
                            </p>
                            <p>
                                <i class="fas fa-circle ct-legend-d"></i>
                                 Forearms <span id="3-percent"></span>
                            </p>
                            <p>
                                <i class="fas fa-circle ct-legend-e"></i>
                                 Abs <span id="4-percent"></span>
                            </p>
                            <p>
                                <i class="fas fa-circle ct-legend-f"></i>
                                 Shoulders <span id="5-percent"></span>
                            </p>
                            <p>
                                <i class="fas fa-circle ct-legend-g"></i>
                                 Legs <span id="6-percent"></span>
                            </p>
                            <p>
                                <i class="fas fa-circle ct-legend-h"></i>
                                 Chest <span id="7-percent"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-4 col-md-4">
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="blue">
                    <i class="material-icons">view_list</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title">Top 10 exercises!</h4>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="text-primary">
                                <tr>
                                    <th>Exercise name</th>
                                    <th>Sets completed</th>
                                </tr>
                            </thead>
                            <tbody id="topTenExercises">
                                <tr>
                                    <td>
                                        <span class="text-placeholder" style="width: 91%"></span>
                                    </td>
                                    <td>25</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="text-placeholder" style="width: 8%"></span>
                                    </td>
                                    <td>21</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="text-placeholder" style="width: 55%"></span>
                                    </td>
                                    <td>20</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="text-placeholder" style="width: 30%"></span>
                                    </td>
                                    <td>17</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="text-placeholder" style="width: 32%"></span>
                                    </td>
                                    <td>14</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="text-placeholder" style="width: 59%"></span>
                                    </td>
                                    <td>11</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="text-placeholder" style="width: 95%"></span>
                                    </td>
                                    <td>10</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="text-placeholder" style="width: 42%"></span>
                                    </td>
                                    <td>8</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="text-placeholder" style="width: 68%"></span>
                                    </td>
                                    <td>6</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="text-placeholder" style="width: 36%"></span>
                                    </td>
                                    <td>3</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header card-header-icon" data-background-color="blue">
            <i class="material-icons">transfer_within_a_station</i>
        </div>
        <div class="card-content">
            <h4 class="card-title">Exercise progress</h4>
            <div class="row">
                <div class="col-xs-12">
                    <div class="checkbox">
                        <label>
                            <input id="show_active_exercises" type="checkbox" name="optionsCheckboxes" checked="">
                            Only show active exercises
                        </label>
                    </div>
                    <select id="exercise_name" data-style="btn btn-primary" title="Select exercise" data-live-search="true">
                    </select>
                </div>
            </div>
        </div>
        <div style="position: relative; width: 100%; height: 0px">
            <canvas id="compareExerciseChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ mix('/js/dashboard.min.js') }}"></script>

    @if ($firstTime)
        <script>
            $(document).ready(function() {
                swal({
                    title: 'Welcome to Logit!',
                    type: 'info',
                    html:
                        'Since this is the first time loggin in, I suggest you head over to the ' +
                        '<a href="/user">My profile</a> and <a href="/user/settings">Settings</a> page to get you started ' +
                        '(Click on your name on the left side).<br><br> ' +
                        "Once that's done you can head over to <a href='/dashboard/my_routines'>My Routines</a>!",
                    showCloseButton: true,
                    showCancelButton: false,
                    allowOutsideClick: false,
                    confirmButtonText: '<i class="fal fa-thumbs-up"></i> Great. Thank you!'
                });
            });
        </script>
    @endif

    @if ($newMessage)
        <script>
            $(document).ready(function() {
                var messageId = {{ $newMessage->id }};
                swal({
                    title: "{{ $newMessage->title }}",
                    type: "{{ $newMessage->type }}",
                    html: "{!! $newMessage->html !!}",
                    showCloseButton: true,
                    showCancelButton: false,
                    allowOutsideClick: false,
                    confirmButtonText: "{{ $newMessage->confirmButtonText }}"
                }).then(function () {
                    $.ajax({
                        url: '/api/message/clear/',
                        data: {
                            message_id: messageId
                        },
                        success: function() {
                            return true;
                        }
                    });
                });
            });
        </script>
    @endif
@endsection