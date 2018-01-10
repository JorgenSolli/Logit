$(document).ready(function() {
    $('.datetimepicker').datetimepicker({
        format: 'YYYY-MM-DD H:mm',
        icons: {
            time: "fal fa-clock",
            date: "fal fa-calendar",
            up: "fal fa-chevron-up",
            down: "fal fa-chevron-down",
            previous: 'fal fa-chevron-left',
            next: 'fal fa-chevron-right',
            today: 'fal fa-screenshot',
            clear: 'fal fa-trash danger-color',
            close: 'fal fa-remove'
        },
        showClear: true,
    });

    $('#datatables').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        responsive: true,
        order: [
            [ 0, "desc" ]
        ],
        language: {
        search: "_INPUT_",
        searchPlaceholder: "Search records",
        }
    })

    $(document).on('click', '.deleteMeasurement', function() {
        var obj = $(this);
        var id = obj.attr('id');

        swal({
            title: 'Are you sure?',
            text: "The entry will be gone forever",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, I am sure!'
        }).then(function () {
            deleteMeasurement(id, obj);
        })

    });

    $.ajax({
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/dashboard/measurements/get_measurements',
        success: function(data) {
            viewProgress(data.labels, data.series);
        }
    });
});

var deleteMeasurement = function(id, obj) {
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        url: '/dashboard/measurements/delete',
        data: {
            id: id,
        },
        success: function(data) {
            if (data === "true") {
                swal(
                    'Removed!',
                    'Entry removed',
                    'success'
                );
                obj.closest('tr').remove();
            }
            else {
                swal(
                    'Whops!',
                    'Something went wrong! Try again.',
                    'error'
                );
            }
        }
    });
};

var viewProgress = function(labels, series) {
    console.log(series[0]);
    var ctx = $("#measurementProgress");
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Weight',
                data: series[0],
                borderWidth: 2,
                borderColor: '#16a086',
                backgroundColor: '#16a086',
                fill: false,
            },
            {
                label: 'Arms',
                data: series[1],
                borderWidth: 2,
                borderColor: '#9b58b5',
                backgroundColor: '#9b58b5',
                fill: false,
            },
            {
                label: 'Calves',
                data: series[2],
                borderWidth: 2,
                borderColor: '#f35f5f',
                backgroundColor: '#f35f5f', 
                fill: false,
            },
            {
                label: 'Body Fat',
                data: series[3],
                borderWidth: 2,
                borderColor: '#3598db',
                backgroundColor: '#3598db',
                fill: false,
            },
            {
                label: 'Chest',
                data: series[4],
                borderWidth: 2,
                borderColor: '#2dcc70',
                backgroundColor: '#2dcc70',
                fill: false,
            },
            {
                label: 'Thighs',
                data: series[5],
                borderWidth: 2,
                borderColor: '#34495e',
                backgroundColor: '#34495e',
                fill: false,
            },
            {
                label: 'Neck',
                data: series[6],
                borderWidth: 2,
                borderColor: '#f1c40f',
                backgroundColor: '#f1c40f',
                fill: false,
            },
            {
                label: 'Waist',
                data: series[7],
                borderWidth: 2,
                borderColor: '#e77e23',
                backgroundColor: '#e77e23',
                fill: false,
            },
            {
                label: 'Hips',
                data: series[8],
                borderWidth: 2,
                borderColor: '#95a5a5',
                backgroundColor: '#95a5a5',
                fill: false,
            },
            {
                label: 'Shoulders',
                data: series[9],
                borderWidth: 2,
                borderColor: '#d9c62f',
                backgroundColor: '#d9c62f',
                fill: false,
            },
            {
                label: 'Forearms',
                data: series[10],
                borderWidth: 2,
                borderColor: '#3d065a',
                backgroundColor: '#3d065a',
                fill: false,
            }], 
        },
        options: {
            title: {
                display: false,
                fontFamily: 'Open Sans',
                fontSize: 25,
                fontColor: '#3C4858',
                text: 'Your progress for '
            },
            layout: {
                padding: {
                    top: 0,
                    right: 10,
                    bottom: 5,
                    left: 5
                }
            },
            tooltips: {
                mode: 'nearest',
                intersect: false,
            },
            maintainAspectRatio: false,
            responsive: true,
            responsiveAnimationDuration: 100
        }
    });
}