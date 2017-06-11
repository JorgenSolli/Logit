@extends('layouts.app')

@section('content')
  <h1 class="page-header">Welcome {{ $brukerinfo->name }}</h1>
  <div class="row">
    <h4 class="p-l-md">Show statistics for</h4>
    <div class="col-md-2 sm-6 col-xs-6">
      <select id="statistics-type" class="form-control">
        <option value="year">Year</option>
        <option value="months" selected>Month</option>
      </select>
    </div>
    <div class="col-md-2 col-sm-6 col-xs-6">
      <select id="statistics-year" class="form-control">
      </select>
    </div>
    <div class="col-md-2 col-sm-12 col-xs-12">
      <select id="statistics-month" class="form-control">
      </select>
    </div>
  </div>
    
  <div class="row">
    <div class="col-sm-12" style="height: 250px;">
      <h3>Workout Activity</h3>
      <canvas style="margin-bottom: 50px; padding-bottom: 50px;" id="dashboardActivityGraph" width="400" height="100"></canvas>
    </div>
  </div>
  
  <div class="row">
    <div class="col-sm-4">
      <h3 class="text-center">Number of workouts</h3>
      <canvas id="dashboardActivityBar" width="400" height="300"></canvas>
    </div>

    <div class="col-sm-4">
      <h3 class="text-center">Muscle groups trained</h3>
      <canvas id="dashboardActivityPie" width="400" height="300"></canvas>
    </div>

    <div class="col-sm-4">
      <h3 class="text-center">Weight progress</h3>
      <canvas id="dashboardActivityBar2" width="400" height="300"></canvas>
    </div>
  </div>
@endsection
@section('script')
  <script src="/js/moment.js"></script>
  <script src="/js/Chart.bundle.min.js"></script>
  <script src="/js/dashboard.js"></script>
  <script>
    var pie = document.getElementById("dashboardActivityPie");
    var myPieChart = new Chart(pie,{
      type: 'pie',
      data: {
        labels: [
          "Red",
          "Blue",
          "Yellow"
        ],
        datasets: [{
          data: [300, 50, 100],
          backgroundColor: [
            "#FF6384",
            "#36A2EB",
            "#FFCE56"
          ],
          hoverBackgroundColor: [
            "#FF6384",
            "#36A2EB",
            "#FFCE56"
          ]
        }]
      }
    });

    var bar = document.getElementById("dashboardActivityBar");
    var myBarChart = new Chart(bar, {
      type: 'bar',
      data: {
        labels: ["January", "February", "March", "April", "May", "June", "July"],
        datasets: [{
          label: "My First dataset",
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)'
          ],
          borderColor: [
            'rgba(255,99,132,1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
          ],
          borderWidth: 1,
          data: [65, 59, 80, 81, 56, 55, 40],
        }]
      }
    });

    var bar2 = document.getElementById("dashboardActivityBar2");
    var myBarChart = new Chart(bar2, {
      type: 'bar',
      data: {
        labels: ["January", "February", "March", "April", "May", "June", "July"],
        datasets: [{
          label: "My First dataset",
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)'
          ],
          borderColor: [
            'rgba(255,99,132,1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
          ],
          borderWidth: 1,
          data: [65, 59, 80, 81, 56, 55, 40],
        }]
      }
    });
  </script>
@endsection