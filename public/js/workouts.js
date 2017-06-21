$(document).ready(function(){
	var deleteWorkout = function(id) {
		$.ajax({
			url: '/api/delete_workout/' + id,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			method: 'GET',
			success: function(data) {
				console.log(data);
				if (data.success) {
					$("#workout-" + id).fadeOut();
					$("tr.child").fadeOut();
				}
			},
	  	})
	}

	$("#exercises a").on('click', function() {
		var exerciseId = $(this).attr('id');
		$.ajax({
			url: '/api/exercise/' + exerciseId,
			headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
			method: 'GET',
			success: function(data) {
				$("#data").html(data['data'])
				$("#exercises").slideUp();
			},
		})
	})

	$(document).on('click', '#finishWorkout', function(e) {
		e.preventDefault();

		var atLeastOne = false;
		var incompleteItems = false
		var numIncomplete = 0;
		var href = $(this).attr('href')
		/* Checks if some exercies aren't completed or if at least one is completed */
		$(".list-group-item").each(function(index) {
			if ($(this).attr('data-status') == 'incomplete') {
				incompleteItems = true
				numIncomplete++
			} else {
				atLeastOne = true
			}

		})

		// If not a single exercies is completed
		if (!atLeastOne) {
			swal({
				title: 'Whops!',
				text: 'You need to complete at least ONE exercies before finishing',
				type: 'error',
				showCancelButton: false,
				confirmButtonColor: '#3085d6',
				confirmButtonText: "Understood!",
				confirmButtonClass: 'btn btn-primary',
				buttonsStyling: false
			})

			return;
		}

		if (incompleteItems) {
			swal({
				title: "Sure you want to finish?",
				text: "You haven't completed all exercises!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: "Yes, I'm done!",
				cancelButtonText: "No, I'll finish!",
				confirmButtonClass: 'btn btn-danger',
				cancelButtonClass: 'btn btn-success',
				buttonsStyling: false
			}).then(function () {
				return window.location.href = href;
			}, function (dismiss) {
				if (dismiss === 'cancel') {
					swal(
						'Awesome!',
						"Let's finish this!",
						'success'
					)
					return;
				}
			})
		}

		if (atLeastOne && !incompleteItems) {
			return window.location.href = href;
		}
	})

	$(document).on('click', '#cancelExercise', function() {
		$("#data").empty();
		
		$(".ps-container").scrollTop(0);
		$(".ps-container").perfectScrollbar('update');
		
		$("#exercises").slideDown();
	})

	$(document).on('click', '.viewWorkout', function() {
	  var workoutId = $(this).children('input').val();

	  $.ajax({
	    url: '/api/get_workout/view/' + workoutId,
	    headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
	    method: 'GET',
	    success: function(data) {
      		$("#workouts").hide();
	  		$("#viewWorkout").html(data['data']).show();
	    },
	  })
	})

	$(document).on('click', '.deleteWorkout', function() {
		var workoutId = $(this).attr('id');
		var name = $("#workout-" + workoutId).find(".name").html().trim();

		swal({
			title: 'Are you sure?',
			text: name + " will be gone forever!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then(function () {
			swal(
				'Deleted!',
				'The workout has been deleted.',
				'success'
			)
			deleteWorkout(workoutId);
		})
	})

	$(document).on('click', '.updateWorkoutRow', function() {
		// var setNr = parent.find('.set_nr').html().trim();
		var workoutId = $('#workout_id').val();
		var parent = $(this).parent().parent();
		var reps = parent.find('.reps').val();
		var weight = parent.find('.weight').val();
		var junction_id = parent.find('input[name="workout_junction_id"]').val();

		$.ajax({
			url: '/api/update_workout/' + workoutId,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			method: 'GET',
			data: {
				junction_id: junction_id,
				weight: weight,
				reps: reps,
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
	})

	$(document).on('click', '.workout-back', function() {
		$("#viewWorkout").empty().hide();
		$("#workouts").show();

		$(".ps-container").scrollTop(0);
		$(".ps-container").perfectScrollbar('update');
	});

	$(document).on('click', '#saveWorkout', function() {
		var ok = true
		$(".required").each(function(index) {
			if ($(this).val() == "") {
				$(this).closest(".form-group").addClass("has-error").find(".control-label").removeClass("hidden")
				ok = false
			} else {
				$(this).closest(".form-group").removeClass("has-error").find(".control-label").addClass("hidden")
			}
		})

		return ok
	})

	$(document).on('click', '#clearSession', function(e) {
		e.preventDefault();

		swal({
			title: 'Are you sure?',
			text: "All data connected to this session will be lost.",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, cancel it!',
			cancelButtonText: 'No, cancel!',
			confirmButtonClass: 'btn btn-success',
			cancelButtonClass: 'btn btn-danger',
			buttonsStyling: false
		}).then(function () {
			window.location.href = "/clear";
		}, function (dismiss) {
			if (dismiss === 'cancel') {
				return false;
			}
		})
	})

	$('#datatables').DataTable({
	  "pagingType": "full_numbers",
	  "lengthMenu": [
	      [10, 25, 50, -1],
	      [10, 25, 50, "All"]
	  ],
	  responsive: true,
	  language: {
	      search: "_INPUT_",
	      searchPlaceholder: "Search records",
	  }
	})
	var table = $('#datatables').DataTable();
});