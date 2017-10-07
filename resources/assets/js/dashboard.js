/*  **************** Workout Activity - Line Chart ******************** */
var initCharts = function(labels, series, max) {
    dataWorkoutActivityChart = {
        labels: labels,
        series: series
    };

    optionsWorkoutActivityChart = {
        lineSmooth: Chartist.Interpolation.cardinal({
            tension: 0
        }),
        axisY: {
            showGrid: true,
            offset: 40,
            onlyInteger: true
        },
        axisX: {
            showGrid: false,
        },
        low: 0,
        high: max,
        showPoint: true,
        height: '200px',
        showArea: true,
        plugins: [
            Chartist.plugins.tooltip({
                tooltipOffset: {
                    y: 55
                }
            })
        ]
    };


    var workoutActivityChart = new Chartist.Line(
        '#workoutActivityChart', 
        dataWorkoutActivityChart, 
        optionsWorkoutActivityChart
    );

    md.startAnimationForLineChart(workoutActivityChart);
}

/*  **************** Musclegroups Worked Out - Pie Chart ******************** */
var musclegroupsPiechart = function(labels, series) {

  var dataPreferences = {
    labels: labels,
    series: series
  };

  var optionsPreferences = {
    height: '250px',
    chartPadding: 20,
    labelOffset: 40,
    labelDirection: 'explode',
    donut: true
  };

  Chartist.Pie('#musclegroupsPiechart', dataPreferences, optionsPreferences);
}

var compareExerciseChart = function(labels, series, low, max) {
  dataCompareExerciseChart = {
    labels: labels,
    series: series
  };

  optionsCompareExerciseChart = {
    lineSmooth: Chartist.Interpolation.cardinal({
        tension: 0
    }),
    axisY: {
        showGrid: true,
        offset: 40
    },
    axisX: {
        showGrid: false,
    },
    low: low,
    high: max,
    showPoint: true,
    height: '200px',
    plugins: [
        Chartist.plugins.tooltip({
            tooltipOffset: {
                y: 120
            }
        })
    ]
  };

  var compareExerciseChart = new Chartist.Line('#compareExerciseChart', dataCompareExerciseChart, optionsCompareExerciseChart);

  md.startAnimationForLineChart(compareExerciseChart); 
}

$(document).ready(function() {
    var APP_CREATED_AT = 2017 - 1; // Minus one in case people would like to import old data
    var yearDiv = $("#statistics-year");
    var monthDiv = $("#statistics-month");
    var thisYear = new Date().getFullYear();
    var monthsShort = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var monthsLong = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var currMonth = moment().format('MMM');

    // Keeps track of select status
    var show_active_exercises = true;
    var show_reps   = true;
    var show_weight = true;
    $(document).on('click', '#show_active_exercises', function() {
        show_active_exercises = show_active_exercises ? false : true;
        populateCompareExercises();
    });
    $(document).on('click', '#show_reps', function() {
        show_reps = show_reps ? false : true;
        compareExercise();
    });
    $(document).on('click', '#show_weight', function() {
        show_weight = show_weight ? false : true;
        compareExercise();
    });

    for (var i = thisYear; i >= APP_CREATED_AT; i--) {
        if (i == thisYear) {
          yearDiv.append('<option value="' + i + '" selected>' + i + '</option>');
        } else {
          yearDiv.append('<option value="' + i + '">' + i + '</option>');
        }
    }

    for (var i = 0; i < monthsShort.length; i++) {
      if (currMonth == monthsShort[i]) {
        monthDiv.append('<option value="' + monthsShort[i] + '" selected>' + monthsLong[i] + '</option>')
      } else {
        monthDiv.append('<option value="' + monthsShort[i] + '">' + monthsLong[i] + '</option>')
      }
    }

    $("#statistics-type").on('change', function() {
        var type = $(this).val();
        if (type == "months") {
            $("#statistics-month").parent().show();
        } else {
            $("#statistics-month").parent().hide();
        }
    });

    $("#statistics-type, statistics-year, #statistics-month").on('change', function() {
        getGraphData();
        compareExercise();
        populateCompareExercises();
    });

    var compareExercise = function() {
        var type        = $("#statistics-type").val();
        var year        = $("#statistics-year").val();
        var month       = $("#statistics-month").val();
        var exercise    = $("#exercise_name").val();

         /* Data for compare exercise chart */
        $.ajax({
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/api/getExerciseProgress/' + type + '/' + year + '/' + month + '/' + exercise,
            data: {
                show_reps: show_reps,
                show_weight: show_weight,
            },
            success: function(data) {
                if (data.success) {
                    $("#compareExerciseChart").empty();
                    compareExerciseChart(data.labels, data.series, data.low, data.max);
                }
                else {
                    $("#compareExerciseChart").html("<h3 style='margin: 0 0 10px 20px;'>No data for this exercise!</h3>");
                }
            }
        });
    }

    var populateCompareExercises = function() {
        var limit  = 99999999;
        var type   = $("#statistics-type").val();
        var year   = $("#statistics-year").val();
        var month  = $("#statistics-month").val();
        var filter = $

        $.ajax({
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/api/getTopExercises/' + type + '/' + year + '/' + month,
            data: {
                limit: limit,
                show_active_exercises: show_active_exercises
            },
            success: function(data) {
                var refresh = false;
                if ($("#exercise_name option").length > 0) {
                    $("#exercise_name").empty();
                    refresh = true;
                }
                $.each(data, function(key) {
                    $("#exercise_name").append('<option value="' + data[key].exercise_name + '">' + data[key].exercise_name + '</option>');
                });

                if (refresh) {
                    $('#exercise_name').selectpicker('refresh');
                }
                else {
                    $('#exercise_name').selectpicker({});
                }
            }
        });
    }

    var getGraphData = function() {
        var type  = $("#statistics-type").val();
        var year  = $("#statistics-year").val();
        var month = $("#statistics-month").val();

        /* Data for session chart */
        $.ajax({
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/api/getSessions/' + type + '/' + year + '/' + month,
            success: function(data) {
                initCharts(data.labels, data.series, data.max);
            }
        });

        /* Data for musclegroup chart */
        $.ajax({
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/api/getMusclegroups/' + type + '/' + year + '/' + month,
            success: function(data) {
                var hasValue = false
                for (var i = 0; i < data.series.length; i++) {
                    if (data.series[i] > 0) {
                        hasValue = true
                    }
                }

                if (hasValue) {
                    musclegroupsPiechart(data.labels, data.series);
                } else {
                    $("#musclegroupsPiechart").html('<div class="m-l-20 m-b-10">You will get access to this chart when you finish at least one routine</div>')
                }

                for (var i = 0; i < data.labels.length; i++) {
                    var percent = Math.trunc(data.series[i]);
                    $("#" + i + "-percent").text("(" + percent + "%)")
                }
            }
        });

        /* Data for workout-time data */
        $.ajax({
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/api/getAvgGymTime/' + type + '/' + year + '/' + month,
            success: function(data) {
                $("#avg_hr").text(data.avg_hr)
                $("#avg_min").text(data.avg_min)
            }
        });

        /* Data for top ten exercises */
        $.ajax({
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/api/getTopExercises/' + type + '/' + year + '/' + month,
            success: function(data) {
                if ($("#topTenExercises tr").length > 0) {
                    $("#topTenExercises").empty();
                }

                $.each(data, function(key) {
                    $("#topTenExercises").append('<tr>' + 
                        '<td>' + data[key].exercise_name + '</td>' + 
                        '<td>' + data[key].count + '</td>' + 
                    '</tr>');
                });
            }
        });
    }

    $(document).on('change', '#exercise_name', function() {
        compareExercise();
    });

    // Loads the graph
    getGraphData();

    // Populate selects
    populateCompareExercises();

    // Waits for information to be appended before invoking the selectpicker
    $('.selectpickerAjax').selectpicker({});

    $(".bs-searchbox input.form-control").attr('placeholder', 'Search for an exercise');
});