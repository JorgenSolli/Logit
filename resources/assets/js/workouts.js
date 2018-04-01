var deleteWorkout = function(id) {
	$.ajax({
		method: 'POST',
		url: '/workouts/' + id + '/delete',
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		data: {
			'_method': 'DELETE',
		},
		success: function(data) {
			if (data.success) {
				$("#workout-" + id).fadeOut();
				$("tr.child").fadeOut();
			}
		},
  	});
}

$(document).ready(function() {
	$('.selectpicker').selectpicker({});
	$('.datetimepicker').datetimepicker({
		format : 'YYYY-MM-DD HH:mm', // Proper ISO 8601 date!
		maxDate: moment(),
		useCurrent: false,
	    icons: {
	        time: "fal fa-clock",
	        date: "fal fa-calendar-alt",
	        up: "fal fa-chevron-up",
	        down: "fal fa-chevron-down",
	        previous: 'fal fa-chevron-left',
	        next: 'fal fa-chevron-right',
	        today: 'fal fa-desktop',
	        clear: 'fal fa-trash',
	        close: 'fal fa-times'
	    }
	});

	$(document).on('click', '.deleteWorkout', function() {
		var workoutId = $(this).attr('id');
		var name = $("#workout-" + workoutId).find(".name").html().trim();

		swal({
			title: 'Are you sure?',
			text: name + " will be gone forever!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#bf5329',
			cancelButtonColor: '#3097D1',
			confirmButtonText: 'Yes, delete it!'
		}).then(function () {
			swal({
				title: 'Deleted!',
				text: 'The workout has been deleted.',
				type: 'success',
				confirmButtonColor: '#3097D1',
				confirmButtonText: 'Sweet'
			}).done();
			deleteWorkout(workoutId);
		}).done();
	});

	$(document).on('click', '.updateWorkoutRow', function() {
		var workoutId = $('#workout_id').val();
		var parent = $(this).closest('.row');
		var reps = parent.find('.reps').val();
		var weight = parent.find('.weight').val();
		var weight_type = parent.find('.selectpicker.weight_type').val();
		var band_type = parent.find('.selectpicker.band_type').val();
		var junction_id = parent.find('input[name="workout_junction_id"]').val();

		$.ajax({
			url: '/workouts/' + workoutId + '/update',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			method: 'POST',
			data: {
				junction_id: junction_id,
				weight: weight,
				weight_type: weight_type,
				band_type: band_type,
				reps: reps,
				_method: 'PATCH'
			},
			success: function(data) {
				if (data.success) {
					$.notify({
        				icon: "add_alert",
				        message: "The exercise was successfully updated."

				    },{
				        type: 'success',
				        timer: 2000,
				        placement: {
				            from: 'top',
				            align: 'right'
				        }
				    });
				}
				else {
					$.notify({
        				icon: "add_alert",
				        message: "Something went wrong. Try again or contact support."

				    },{
				        type: 'danger',
				        timer: 3000,
				        placement: {
				            from: 'top',
				            align: 'right'
				        }
				    });
				}
			},
		})
	});

	$(document).on('click', '.updateTimestamps', function() {
		var workoutId = $('#workout_id').val();
		var timeStarted = $("#timeStarted").data('DateTimePicker').date();
		var timeFinished = $("#timeFinished").data('DateTimePicker').date();

		$.ajax({
			url: '/workouts/' + workoutId + '/update',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			method: 'POST',
			data: {
				date_started: timeStarted.format(),
				created_at: timeFinished.format(),
				setTime: true,
				_method: 'PATCH'
			},
			success: function(data) {
				if (data.success) {
					$.notify({
        				icon: "add_alert",
				        message: "The date was successfully updated."

				    },{
				        type: 'success',
				        timer: 2000,
				        placement: {
				            from: 'top',
				            align: 'right'
				        }
				    });
				}
				else {
					$.notify({
        				icon: "add_alert",
				        message: "Something went wrong. Make sure 'Time started' is BEFORE 'Time Finished'."

				    },{
				        type: 'danger',
				        timer: 3000,
				        placement: {
				            from: 'top',
				            align: 'right'
				        }
				    });
				}
			},
		})
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
	});
});