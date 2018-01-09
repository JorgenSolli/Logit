/*  **************** Workout Activity - Line Chart ******************** */
var sessionsChart;
var initSessionsChart = function(labels, series, meta, max, stepSize) {
    var ctx = $("#workoutActivityChart");
    sessionsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Sessions',
                data: series,
                lineTension: 0,
                borderWidth: 2,
                borderColor: 'rgba(45, 204, 112, 1)',
                backgroundColor: 'rgba(45, 204, 112, 0.15)',
                fill: true,
            }]
        },
        options: {
            title: {
                display: false,
                lineHeight: 1,
            },
            layout: {
                padding: {
                    top: 1,
                    right: 10,
                    bottom: 5,
                    left: 5
                }
            },
            legend: {
                display: false,
            },
            scales: {
                yAxes: [{
                    ticks: {
                        stepSize: stepSize,
                        min: 0,
                    },
                }]
            },
            tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                    title: function(tooltipItem, data) {
                        var item = null;
                        if (meta) {
                            var label = tooltipItem[0].xLabel;
                            item = meta[label-1]
                        }
                        return item;
                    }
                },
            },
            maintainAspectRatio: false,
            responsive: true,
            responsiveAnimationDuration: 100
        }
    });
}

/*  **************** Musclegroups Worked Out - Pie Chart ******************** */
var musclegroupsPiechart;
var initMusclegroupsPiechart = function(labels, series) {
    var ctx = $("#musclegroupsPiechart");
    musclegroupsPiechart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Musclegroup',
                data: series,
                pointStyle: 'rectRot',
                backgroundColor: ['#2c9526','#00562e','#007ccf','#1e3a64','#8737ca','#cb257f','#e83400'],
            }],

        },
        options: {
            responsive: true,
            responsiveAnimationDuration: 100,
            maintainAspectRatio: false,
            legend: {
                display: false,
            }
        }
    });
}

// Define chart here so we can destroy it later
var exerciseChart;
var compareExerciseChart = function(labels, series, exercise, max) {
    var ctx = $("#compareExerciseChart");
    exerciseChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Weight',
                data: series[0],
                borderWidth: 2,
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.15)',
                fill: false,
            },
            {
                label: 'Reps',
                data: series[1],
                borderWidth: 2,
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.15)',
                fill: false,
            }]
        },
        options: {
            title: {
                display: true,
                fontFamily: 'Open Sans',
                fontSize: 25,
                fontColor: '#3C4858',
                text: 'Your progress for ' + exercise
            },
            layout: {
                padding: {
                    top: 0,
                    right: 10,
                    bottom: 5,
                    left: 5
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        stepSize: 10,
                        max: max
                    },
                }]
            },
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            maintainAspectRatio: false,
            responsive: true,
            responsiveAnimationDuration: 100
        }
    });
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

    $("#statistics-type, #statistics-year, #statistics-month").on('change', function() {
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
        if (exercise) {
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
                    if ($("#compareExerciseChart").html().length == 0) {
                        $("#compareExerciseChart").parent().height(300);
                    }

                    if (data.success) {
                        if (exerciseChart) {
                            exerciseChart.destroy();
                        }
                        compareExerciseChart(data.labels, data.series, data.exercise, data.max);
                    }
                    else {
                        $("#compareExerciseChart").html("<h3 style='margin: 0 0 10px 20px;'>No data for this exercise!</h3>");
                    }
                }
            });
        }
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
                if (sessionsChart) {
                    sessionsChart.destroy();
                }
                initSessionsChart(data.labels, data.series, data.meta, data.max, data.stepSize);
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
                    if (musclegroupsPiechart) {
                        musclegroupsPiechart.destroy();
                    }
                    
                    initMusclegroupsPiechart(data.labels, data.series);
                    
                    for (var i = 0; i < data.labels.length; i++) {
                        var percent = Math.trunc(data.series[i]);
                        $("#" + i + "-percent").text("(" + percent + "%)")
                    }
                } else {
                    $("#musclegroupsPiechart").html('<div class="m-l-20 m-b-10">You will get access to this chart when you finish at least one routine</div>')
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