var graph = function(labels, data) {
    
    var graph = document.getElementById("dashboardActivityGraph");
    var graphData = {
      labels : labels,
      datasets: [{
        label: 'Sessions completed',
        data: data,
        backgroundColor: "rgba(66,139,201,0.6)"
      }]
    };

    var myLineChart = new Chart(graph, {
      type: 'line',
      data: graphData,
      options: {
        responsive: true,
        maintainAspectRatio: false
      },
    });
}

$(document).ready(function() {
    var APP_CREATED_AT = 2017 - 1; // Minus one in case people would like to import old data
    var yearDiv = $("#statistics-year");
    var monthDiv = $("#statistics-month");
    var thisYear = new Date().getFullYear();

    for (var i = thisYear; i >= APP_CREATED_AT; i--) {
        yearDiv.append('<option value="' + i + '">' + i + '</option>');
    }

    monthDiv.append('<option value="Jan">January</option>' + 
                    '<option value="Feb">February</option>' + 
                    '<option value="Mar">March</option>' + 
                    '<option value="Apr">April</option>' + 
                    '<option value="May">May</option>' + 
                    '<option value="Jun">June</option>' + 
                    '<option value="Jul">July</option>' + 
                    '<option value="Aug">August</option>' + 
                    '<option value="Sep">September</option>' + 
                    '<option value="Oct">October</option>' + 
                    '<option value="Nov">November</option>' + 
                    '<option value="Dec">December</option>');
    $("#statistics-type").on('change', function() {
        var type = $(this).val();
        if (type == "months") {
            $("#statistics-month").parent().show();
        } else {
            $("#statistics-month").parent().hide();
        }
    });

    $("#statistics-set").on('click', function() {
        getGraphData();
    });

    var getGraphData = function() {
        var type  = $("#statistics-type").val();
        var year  = $("#statistics-year").val();
        var month = $("#statistics-month").val();

        var labels = [];
        var graphData = [];

        $.ajax({
            method: 'GET',
            url: '/api/getSessions/' + type + '/' + year + '/' + month,
            success: function(data) {

                if (type == 'year') {
                    for (i = 0; i < data['data'].length; i++) {
                        labels.push(data['data'][i]['month']);
                        graphData.push(data['data'][i]['total']);
                    }
                }
                else if (type == 'months') {
                    for (i = 0; i < data['data'].length; i++) {
                        labels.push(data['data'][i]['day']);
                        graphData.push(data['data'][i]['total']);
                        
                    }   
                }
                graph(labels, graphData);
            }
        })
    }

    // Loads the graph
    getGraphData();
});