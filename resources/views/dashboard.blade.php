@extends('layouts.app')

@section('content')
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

  <div class="row">
    <div class="col-sm-4 col-md-3">
      <div class="card">
        <div class="card-header card-header-icon" data-background-color="blue">
            <i class="material-icons">timer</i>
        </div>
        <div class="card-content">
          <div class="clearfix">
            <h4 class="card-title pull-left">Average workout time</h4>
          </div>
          <div class="data-text text-center">
            <h1 id="avg_hour" class="m-b-0">
              <span id="avg_hr"></span>:<span id="avg_min"></span>
            </h1>
            <h1 class="m-t-0">
              <small>Hour/Minute</small>
            </h1>
          </div>
        </div>
        <div class="card-footer">
          <p>Workouts that lasted less then 10 minutes does not affect your average time.</p>
        </div>
      </div>
    </div>

    <div class="col-sm-4 col-md-4">
      <div class="card">
        <div class="card-header card-header-icon" data-background-color="blue">
            <i class="material-icons">donut_large</i>
        </div>
        <div class="card-content">
          <h4 class="card-title">Musclegroups worked out</h4>
        </div>

        <div id="musclegroupsPiechart" class="ct-chart"></div>
        
        <div class="card-footer">
          <h6>Legend</h6>
          <i class="fa fa-circle ct-legend-a"></i> Back <span id="0-percent"></span>
          <i class="fa fa-circle ct-legend-b"></i> Biceps <span id="1-percent"></span>
          <i class="fa fa-circle ct-legend-c"></i> Triceps <span id="2-percent"></span>
          <i class="fa fa-circle ct-legend-d"></i> Abs <span id="3-percent"></span>
          <i class="fa fa-circle ct-legend-e"></i> Shoulders <span id="4-percent"></span>
          <i class="fa fa-circle ct-legend-f"></i> Legs <span id="5-percent"></span>
          <i class="fa fa-circle ct-legend-g"></i> Chest <span id="6-percent"></span>
        </div>
      </div>
    </div>

    <div class="col-sm-4 col-md-5">
      <div class="card">
        <div class="card-header card-header-icon" data-background-color="blue">
            <i class="material-icons">message</i>
        </div>
        <div class="card-content">
          <h6 class="text-center">More lovely charts will come eventually...
          <br><small>Weight lifted /day maybe?</small></h6>
        </div>
      
      </div>
      
    </div>

  </div>
@endsection

@section('script')
  <script src="/js/moment.js"></script>
  <script src="/js/dashboard.js"></script>
  <script>
    
  </script>
@endsection