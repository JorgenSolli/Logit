$(document).ready(function() {
    $('.datetimepicker').datetimepicker({
        format: 'YYYY-MM-DD H:mm',
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-chevron-up",
            down: "fa fa-chevron-down",
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-screenshot',
            clear: 'fa fa-trash',
            close: 'fa fa-remove'
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