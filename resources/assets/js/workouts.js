var setIconStatus = function() {
	$('a[data-status="incomplete"').each(function(index) {
		$(this).find('span[data-icon="status"]').attr('class', '').addClass('fal fa-clock');
	});

	$('a[data-status="completed"').each(function(index) {
		$(this).find('span[data-icon="status"]').attr('class', '').addClass('fal fa-check');
	});
}

var setMediaLink = function() {
	if ($('input[name*="media"]').val() !== "") {
		// If we're dealing with a superset
		if ($('input[name="superset_name"]').length) {

			$('input[name*="media"]').each(function(index) {
				var data = JSON.parse($(this).val());
				var link = data.media;
				var icon = '<i class="fal fa-external-link"></i> ';

				if (link.search('youtube.com/watch?') !== -1 || link.search('youtu.be/') !== -1) {
					icon = '<i class="fab fa-youtube"></i> ';
				}
				var element = '<a class="label label-primary" target="_blank" href="' + link + '">' + icon + data.name + '</a> ';
				$("#media").append(element);
			});
		} 
		else {
			var link = $('input[name="media"').val();
			var icon = '<i class="fal fa-external-link"></i>';

			if (link.search('youtube.com/watch?') !== -1 || link.search('youtu.be/') !== -1) {
				icon = '<i class="fab fa-youtube"></i>';
			}
			
			var element = '<a target="_blank" href="' + link + '">' + icon + '</a>';
			$("#exercise_name").append(element);
		}
	}
}

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

var saveWorkout = function(form, data, id) {
	$.ajax({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		url: form,
		method: 'POST',
		data: data,
		success: function(data) {
			if (data.success) {
				$.notify({
			        icon: "add_alert",
			        message: data.message,

			    },{
			        type: 'success',
			        timer: 2000,
			        placement: {
			            from: 'top',
			            align: 'right'
			        }
			    });

				// Calls cancelExercise to clear current view and load exercise-list
			    cancelExercise();
			    $("#" + data.id).attr('data-status', 'completed');
			    setIconStatus();
			}
		}
	});
}

var goToAnchor = function(target="#") {
	location.href = target;
}

var cancelExercise = function() {
	$("#data").empty();
	$("#exercises").slideDown();
	// Jumps to anchor
	goToAnchor("#exercises");
}

$(document).ready(function() {
	$("#exercises a").on('click', function() {
		var obj = $(this);
		var exerciseId = obj.attr('id');

		var exercise = obj.html();
		$(this).addClass('disabled');
        $(this).html('<span class="fal fa-spin fa-circle-notch"></span> Getting exercise ...');

		$.ajax({
			url: '/api/exercise/' + exerciseId,
			headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
			method: 'GET',
			success: function(data) {
				$("#data").html(data['data'])
				$("#exercises").slideUp();
				$('.selectpicker').selectpicker({});
				obj.html(exercise).removeClass('disabled');
			},
			complete: function(){
				goToAnchor("#data");
				setMediaLink();
			}
		})
	});

	$(document).on('change', '#weight_type', function() {
		var val = $(this).val();
		var weight_type = $(this).closest(".card-content").find(".weight_type");
		
		if (val == "band") {
			weight_type.find('.band').show().removeClass("ignore");
			weight_type.find('.raw').hide().addClass("ignore");
		}
		else if (val == "assisted") {
			weight_type.find('.band').hide().addClass("ignore");
			weight_type.find('.raw').show().removeClass("ignore");
			weight_type.find('.raw_label').text('Assisted Weight');
		}
		else {
			weight_type.find('.band').hide().addClass("ignore");
			weight_type.find('.raw').show().removeClass("ignore");
			weight_type.find('.raw_label').text('Weight');
		}
	});

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
			$(this).addClass('disabled');
            $(this).html('<span class="fal fa-spin fa-circle-notch"></span> finishing ...');
			return window.location.href = href;
		}
	});

	$(document).on('click', '#cancelExercise', function() {
		cancelExercise();
	});

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
      		$('.selectpicker').selectpicker({});
	    },
	  })
	});

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
	});

	$(document).on('click', '.updateWorkoutRow', function() {
		// var setNr = parent.find('.set_nr').html().trim();
		var workoutId = $('#workout_id').val();
		var parent = $(this).parent().parent();
		var reps = parent.find('.reps').val();
		var weight = parent.find('.weight').val();
		var weight_type = parent.find('.selectpicker.weight_type').val();
		var band_type = parent.find('.selectpicker.band_type').val();
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
				weight_type: weight_type,
				band_type: band_type,
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
	});

	$(document).on('click', '.workout-back', function() {
		$("#viewWorkout").empty().hide();
		$("#workouts").show();

		$(".ps-container").scrollTop(0);
		$(".ps-container").perfectScrollbar('update');
	});

	$(document).on('click', '#saveWorkout', function() {
		var ok = true;

		$(".required").each(function(index) {
			if ($(this).val() == "" && $(this).parent().hasClass("ignore") === false ) {
				$(this).closest(".form-group").addClass("has-error").find(".control-label").removeClass("hidden");
				ok = false
			} else {
				$(this).closest(".form-group").removeClass("has-error").find(".control-label").addClass("hidden");
			}
		});

		if (ok) {
			$(this).addClass('disabled');
            $(this).html('<span class="fal fa-spin fa-circle-notch"></span> saving ...');
            var form = $(this).closest('form').attr('action');
            var data = $(this).closest('form').serialize();
            var id = $(this).closest('input[name="routine_junction_id"]').val();
            saveWorkout(form, data, id);
		} else {
			$.notify({
				icon: "error_outline",
				message: "One or more fields were left blank!"
			},{
				type: 'danger',
				timer: 4000,
				placement: {
					from: 'top',
					align: 'center'
				}
			});
		}

		return ok
	});

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
			cancelButtonText: 'No, keep going!',
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
	});

	$(document).on('click', '[data-routine-preview]', function() {
		var routine = $(this).attr('data-routine-preview');

		$.ajax({
			url: '/api/routines/preview',
			data: {
				routine: routine
			},
			success: function(response) {
				$("#previewModal").html(response.data);
				$("#routinePreview").modal('show');
			}
		});
	});

	$(document).on('click', '.startRoutine', function() {
		var target = $(this).attr('data-href');
		window.location=target;
	})

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

	setIconStatus();
});