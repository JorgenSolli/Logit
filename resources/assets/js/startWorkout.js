var setIconStatus = function() {
	$('a[data-status="incomplete"').each(function(index) {
		$(this).find('span[data-icon="status"]').attr('class', '').addClass('fal fa-clock');
	});

	$('a[data-status="completed"').each(function(index) {
		$(this).find('span[data-icon="status"]').attr('class', '').addClass('fal fa-check');
	});

	checkComplete();
}

var checkComplete = function() {
	var allComplete = true;
	$('#exercises .list-group-item').each(function(index) {
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
	$("#exercises a").on('click', function() {
		var obj = $(this);
		var exerciseId = obj.attr('id');

		var exercise = obj.html();
		$(this).addClass('disabled');
        $(this).html('<span class="fal fa-spin fa-circle-notch"></span> Getting exercise ...');

		$.ajax({
			url: '/exercises/' + exerciseId,
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
		window.location=target;
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
			window.location.href = "/start_workout/session/clear";
		}, function (dismiss) {
			if (dismiss === 'cancel') {
				return false;
			}
		}).done();
	});

	// Only call this function if we're actually in /start/routine
	if ($("#finishWorkout").length) {
		setIconStatus();
	}
});