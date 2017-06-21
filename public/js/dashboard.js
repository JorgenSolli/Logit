var initCharts = function(labels, series, max) {
    /*  **************** Coloured Rounded Line Chart - Line Chart ******************** */
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
          offset: 40
      },
      axisX: {
          showGrid: false,
      },
      low: 0,
      high: max,
      showPoint: true,
      height: '300px'
    };

    var workoutActivityChart = new Chartist.Line('#workoutActivityChart', dataWorkoutActivityChart, optionsWorkoutActivityChart);

    md.startAnimationForLineChart(workoutActivityChart);
}

$(document).ready(function() {
    var APP_CREATED_AT = 2017 - 1; // Minus one in case people would like to import old data
    var yearDiv = $("#statistics-year");
    var monthDiv = $("#statistics-month");
    var thisYear = new Date().getFullYear();
    var monthsShort = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var monthsLong = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var currMonth = moment().format('MMM');

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
    });

    var getGraphData = function() {
        var type  = $("#statistics-type").val();
        var year  = $("#statistics-year").val();
        var month = $("#statistics-month").val();

        $.ajax({
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/api/getSessions/' + type + '/' + year + '/' + month,
            success: function(data) {
                initCharts(data.labels, data.series, data.max);
            }
        })

        $.ajax({
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/api/getAvgGymTime/' + type + '/' + year + '/' + month,
            success: function(data) {
              console.log(data.avg_min);
                $("#avg_hr").text(data.avg_hr)
                $("#avg_min").text(data.avg_min)
            }
        })
    }

    // Waits for information to be appended before invoking the selectpicker
    $('.selectpickerAjax').selectpicker({});
    
    // Loads the graph
    getGraphData();
});