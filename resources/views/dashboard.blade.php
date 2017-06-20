@extends('layouts.app')

@section('content')
    <div class="row">
        <h4 class="p-l-md">Show statistics for</h4>
        <div class="col-md-2 sm-6 col-xs-6">
            <select id="statistics-type" class="selectpicker" data-style="btn btn-primary" title="Single Select" data-size="7">
              <option disabled> Choose period</option>
              <option value="year">Year</option>
              <option value="months" selected>Month</option>
            </select>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-6">
            <select id="statistics-year" class="selectpickerAjax" data-style="btn btn-primary" title="Single Select" data-size="7">
            </select>
        </div>
        <div class="col-md-2 col-sm-12 col-xs-12">
            <select id="statistics-month" class="selectpickerAjax" data-style="btn btn-primary" title="Single Select" data-size="7">
            </select>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header card-header-icon" data-background-color="blue">
            <i class="material-icons">timeline</i>
        </div>
        <div class="card-content">
            <h4 class="card-title">Workout Activity</h4>
        </div>
        <div id="workoutActivityChart" class="ct-chart"></div>
    </div>

    <h6 class="text-center">More lovely charts will come eventually...</h6>

  <div class="row">
    <div class="col-sm-4">
      <div class="card">
        <div class="card-header card-header-icon" data-background-color="blue">
            <i class="material-icons">timer</i>
        </div>
        <div class="card-content">
            <h4 class="card-title">Average workout time</h4>
            <div class="data-text text-center">
              <h1 class="m-b-0">00:54</h1>
              <h1 class="m-t-0"><small>hour/minute</small></h1>
            </div>
        </div>
      </div>
    </div>

  {{--  
    <div class="col-sm-4">
      <h3 class="text-center">Muscle groups trained</h3>
      <canvas id="dashboardActivityPie" width="400" height="300"></canvas>
    </div>

    <div class="col-sm-4">
      <h3 class="text-center">Weight progress</h3>
      <canvas id="dashboardActivityBar2" width="400" height="300"></canvas>
    </div>
  --}}
  </div>
@endsection

@section('script')
  <script src="/js/moment.js"></script>
  <script src="/js/dashboard.js"></script>
  <script>
    
  </script>
@endsection