var setIconStatus = function() {
	$('.exercise[data-status="incomplete"').each(function(index) {
		$(this).removeClass('btn-success disabled').addClass('btn-primary');
		$(this).find('i[data-icon="status"]').attr('class', '').addClass('fal fa-clock mr-2');
	});

	$('.exercise[data-status="completed"').each(function(index) {
		$(this).removeClass('btn-primary').addClass('btn-success disabled');
		$(this).find('i[data-icon="status"]').attr('class', '').addClass('fal fa-check mr-2');
	});

	checkComplete();
}

var checkComplete = function() {
	var allComplete = true;
	$('#exercises .exercise').each(function(index) {
		if ($(this).attr('data-status') == 'incomplete') {
			allComplete = false;
		}
	});

	if (allComplete) {
		href = $('#finishWorkout').attr('href');
		swal({
			title: "That's it!",
			text: "Looks like you've completed your routine. Would you like to finish and save the session?",
			type: 'success',
			showCancelButton: true,
			confirmButtonText: "Yes please!",
			cancelButtonText: "No, not yet!",
			confirmButtonClass: 'btn btn-success',
			cancelButtonClass: 'btn btn-primary',
			buttonsStyling: false
		}).then(function () {
			return window.location.href = href;
		}, function (dismiss) {
			if (dismiss === 'cancel') {
				return;
			}
		}).done();
	}
}

var saveWorkout = function(form, data, id, submitButton) {
	submitButton.addClass('disabled');
	submitButton.html('<span class="fal fa-spin fa-circle-notch"></span> saving ...');
	
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
			        delay: 200,
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

var updateGoal = function(data, formData) {
	var submit = false;
	var hasOver = false;
	var steps = [];
	var doneLooping = false;
	var length = Object.keys(data).length - 1;

	$.each(data, function(key, val) {
		if (val.overGoal) {
			hasOver = true;

			steps.push({
				type: 'success',
				title: 'Update your goal',
				text: "Awesome! You surpassed your previous goal for " + val.exercise + ". Let's set at new one.",
				input: 'number',
				inputValue: val.currentGoal,
				confirmButtonText: 'Update',
				confirmButtonClass: 'btn btn-success',
				buttonsStyling: false,
				allowOutsideClick: false,
				preConfirm: (number) => {
					return new Promise((resolve) => {
						if (!number) {
							swal.showValidationError(
								'Goal cannot be empty'
							)
						} else {
							$.ajax({
								headers: {
									'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
								},
								url: '/routines/edit/goal',
								method: 'POST',
								data: {
									junction: val.routineJunctionId,
									goal: number
								},
								success: function(data) {
									resolve();
								}
							});
						}
					});
				},
			});
		}
		if (key == length) {
			doneLooping = true;
		}
	});

	if (hasOver) {
		swal.queue(steps).then((result) => {
			swal.resetDefaults()
			swal({
				type: 'success',
				title: 'Goal(s) updated!',
				confirmButtonText: 'Continue',
				confirmButtonClass: 'btn btn-success',
				buttonsStyling: false
			}).then(function () {
				if (doneLooping) {
					saveWorkout(formData.form, formData.data, formData.id, formData.that);
				}
			}).done();
		})
	} else {
		if (doneLooping) {
			submit = true;
		}
	}
	
	if (submit) {
		saveWorkout(formData.form, formData.data, formData.id, formData.that);
	}
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
	$("#exercises .exercise").on('click', function() {
		var obj = $(this);
		var exerciseId = obj.attr('id');

		var exercise = obj.html();

		if (!$(this).hasClass('disabled')) {
			$(this).addClass('disabled');
	        $(this).html('<span class="fal fa-spin fa-circle-notch"></span> Getting exercise ...');

			$.ajax({
				url: '/exercise/' + exerciseId,
				headers: {
		        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
				method: 'GET',
				success: function(data) {
					$("#data").html(data['data'])
					$("#exercises").hide();
					$('.selectpicker').selectpicker({});
					obj.html(exercise).removeClass('disabled');
				},
				complete: function(){
					goToAnchor("#data");
					setMediaLink();
				}
			});
		}
	});

	$(document).on('click', '#finishWorkout', function(e) {
		e.preventDefault();

		var atLeastOne = false;
		var incompleteItems = false
		var numIncomplete = 0;
		var href = $(this).attr('href')
		/* Checks if some exercies aren't completed or if at least one is completed */
		$(".exercise").each(function(index) {
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
				confirmButtonText: "Understood!",
				confirmButtonClass: 'btn btn-primary',
				buttonsStyling: false
			}).done();

			return;
		}

		if (incompleteItems) {
			swal({
				title: "Sure you want to finish?",
				text: "You haven't completed all exercises!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: "Yes, I'm done!",
				cancelButtonText: "No, I'll finish!",
				confirmButtonClass: 'btn btn-danger',
				cancelButtonClass: 'btn btn-success',
				buttonsStyling: false
			}).then(function () {
				return window.location.href = href;
			}, function (dismiss) {
				if (dismiss === 'cancel') {
					swal({
						title: 'Awesome!',
						text: "Let's finish this!",
						type:'success',
						confirmButtonText: "Fuck yeah!",
						confirmButtonClass: 'btn btn-primary',
					}).done();
				}
			}).done();
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

	$(document).on('click', '#saveWorkout', function() {
		var ok = true;

		if (!$(this).hasClass('disabled')) {
			$(".required").each(function(index) {
				if ($(this).val() == "" && $(this).parent().hasClass("ignore") === false ) {
					$(this).closest(".form-group").addClass("has-danger").find(".control-label").removeClass("hidden");
					ok = false
				} else {
					$(this).closest(".form-group").removeClass("has-danger").find(".control-label").addClass("hidden");
				}
			});

			if (ok) {
	            var formData = {
	            	form: $(this).closest('form').attr('action'),
	            	data: $(this).closest('form').serialize(),
	            	id: $('input[name="routine_junction_id"]').val(),
	            	that: $(this)
	            }
	            var type = $('input[name="type"]').val();
            	var routineJunctionId = formData.id;
	            var goal, overGoal, exercise;
	            var data = {};

	            if (type == "regular") {
	            	goal = parseInt($('input[name="exercise-goal"]').val());
	            	exercise = $('input[name="exercise_name"]').val()
	            	$("input[name^='exercise'][name$='[weight]']").each(function() {
	            		if ($(this).val() > goal) {
	            			overGoal = true;
	            		}
	            	});
	            	
	            	data[0] = {
	            		routineJunctionId: routineJunctionId,
	            		currentGoal: goal,
	            		overGoal: overGoal,
	            		exercise: exercise
	            	}

	            } else {
	            	var supersetsCount = parseInt($('input[name="superset_count"]').val());

	            	for (var i = 0; i < supersetsCount; i++) {
		            	goal = $('input[name="superset[' + i + '][goal]"]').val();
		            	routineJunctionId = $('input[name="superset[' + i + '][junction]"]').val();
		            	exercise = $('input[name^="superset[' + i + '][1]"][name$="[exercise_name]"]').val()
		            	overGoal = false
		            	$("input[name^='superset[" + i + "]'][name$='[weight]']").each(function() {
		            		if (parseInt($(this).val()) > goal) {
		            			overGoal = true;
		            		}
		            	});
		            	
		            	data[i] = {
		            		routineJunctionId: routineJunctionId,
		            		currentGoal: goal,
		            		overGoal: overGoal,
		            		exercise: exercise
		            	}
	            	}
	            }
            	
            	/* This function also calls the saveWorkout */
            	updateGoal(data, formData);
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
		}

		return ok
	});

	$(document).on('click', '[data-routine-preview]', function() {
		var routine = $(this).attr('data-routine-preview');

		$.ajax({
			url: '/routines/preview',
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

		// If we're gymming, and trying to start another session
		if ($("#isGymming").length && $(this).text() === "Start") {
			swal({
				title: 'Oy!',
				text: "You already have a session in progress. Starting a new one will remove all current session data!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Yes, start new session',
				confirmButtonClass: 'btn btn-danger',
				cancelButtonText: 'Cancel!',
				cancelButtonClass: 'btn btn-primary',
				buttonsStyling: false
			}).then(function () {
				window.location=target;
			}, function (dismiss) {
				if (dismiss === 'cancel') {
					return false;
				}
			}).done();
		} else {
			window.location=target;
		}
	});

	$(document).on('click', '#clearSession', function(e) {
		e.preventDefault();

		swal({
			title: 'Are you sure?',
			text: "All data connected to this session will be lost.",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes, cancel it!',
			confirmButtonClass: 'btn btn-danger',
			cancelButtonText: 'No, keep going!',
			cancelButtonClass: 'btn btn-success',
			buttonsStyling: false
		}).then(function () {
			window.location.href = "/start-workout/session/clear";
		}, function (dismiss) {
			if (dismiss === 'cancel') {
				return false;
			}
		}).done();
	});

	$(document).on('changed.bs.select', '#weight_type', function() {
		var val = $(this).val();
		var target = $(this).closest(".card-body").find('.weight_type');
		var tag = $(this).closest(".card-body").find('input[name="exercise-tag"]').val();
		var goal = $(this).closest(".card-body").find('input[name="exercise-goal"]').val();
		var pre = $(this).closest(".card-body").find('input[name="exercise-pre"]').val();
		
		if (val === "band") {
			target.html('<select name="' + tag + '[band_type]" class="selectpicker selectpicker_reinit band_type" data-style="select-with-transition" title="Choose weight type" data-size="8">' +
					'<option value="black">Black</option>' +
					'<option value="blue">Blue</option>' +
					'<option value="purple">Purple</option>' +
					'<option value="green">Green</option>' +
					'<option value="red">Red</option>' +
					'<option value="yellow">Yellow</option>' +
				'</select>');
			$('.selectpicker_reinit').selectpicker({});
		} else {
			target.html('<label class="bmd-label-floating" for="weight">Weight - Weight - Your goal is ' + goal + '. ' + pre + '</label><input type="number" step="any" class="required form-control" name="' + tag + '[weight]">');
		}
	});

	// Only call this function if we're actually in /start/routine
	if ($("#finishWorkout").length) {
		setIconStatus();
	}
});