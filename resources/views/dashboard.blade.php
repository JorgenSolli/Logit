@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
      <ul class="nav nav-sidebar">
        <li class="active"><a><span class="fa fa-tachometer fa-lg"></span>&nbsp;&nbsp;Dashboard</a></li>
        <li><a href="/dashboard/my_routines"><span class="fa fa-tasks fa-lg"></span>&nbsp;&nbsp;My Routines</a></li>
        <li><a href="/dashboard/start"><span class="fa fa-play fa-lg"></span>&nbsp;&nbsp;Start Workout</a></li>
        <li><a href="/dashboard/workouts"><span class="fa fa-table fa-lg"></span>&nbsp;&nbsp;My Workouts</a></li>
      </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <h1 class="page-header">Welcome {{ $brukerinfo->name }}</h1>
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
    </div>
  </div>
</div>
@endsection
@section('script')
  <script src="/js/Chart.bundle.min.js"></script>
  <script>
    var graph = document.getElementById("dashboardActivityGraph");
    var myLineChart = new Chart(graph, {
      type: 'line',
      data: {
        datasets: [{
          label: 'Preview Test',
          data: [{
              x: 0,
              y: 5
          }, {
              x: 5,
              y: 13
          }, {
              x: 10,
              y: 41
          }]
        }]
      },
      options: {
        scales: {
            xAxes: [{
                type: 'linear',
                position: 'bottom'
            }]
        },
        responsive: true,
        maintainAspectRatio: false
      },
    });

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