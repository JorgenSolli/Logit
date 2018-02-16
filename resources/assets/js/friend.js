$(document).ready(function() {
	var APP_CREATED_AT = 2017 - 1; // Minus one in case people would like to import old data
    var yearDiv = $("#statistics-year");
    var monthDiv = $("#statistics-month");
    var thisYear = new Date().getFullYear();
    var monthsShort = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var monthsLong = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var currMonth = moment().format('MMM');

    var friend_id = $("#user_id").val();
	var friend_picker = "#friend_exercises";
	var your_picker = "#your_exercises";
	var pickers = "#your_exercises, #friend_exercises";

	var your_chart, friend_chart;

	FriendFunctions = {
		init: function() {
			FriendFunctions.populateExercises();
		},

		initSelects: function() {
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

            // Waits for information to be appended before invoking the selectpicker
            $('.selectpickerAjax').selectpicker({});

            type  = $("#statistics-type").val();
            year  = $("#statistics-year").val();
            month = $("#statistics-month").val();
        },

		populateExercises: function(user_id, picker) {
            $.ajax({
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/api/friends/friend/populateExercises',
                data: {
                	friend_id: friend_id
                },
                success: function(data) {
                    var refresh = false;
                    if ($(friend_picker + " option").length > 0 || $(your_picker + " option").length > 0) {
                        $(pickers).empty();
                        refresh = true;
                    }

                    var friend_exercises = data.friend[0];
                    var your_exercises = data.you[0];

                    $.each(friend_exercises, function(key, value) {
                        $(friend_picker).append('<option value="' + value.exercise_name + '">' + value.exercise_name + '</option>');
                    });

                    $.each(your_exercises, function(key, value) {
                        $(your_picker).append('<option value="' + value.exercise_name + '">' + value.exercise_name + '</option>');
                    });

                    if (refresh) {
                        $(pickers).selectpicker('refresh');
                    }
                    else {
                        $(pickers).selectpicker({});
                    }
                }
            });
        },

        compareExercise: function(user, exercise, chartId) {
        	var exerciseChart = $(chartId);
        	var chartName;
        	$.ajax({
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/api/friends/friend/getExerciseData',
                data: {
                	user_id: user,
                	exercise: exercise,
                	type:  $("#statistics-type").val(),
			        year:  $("#statistics-year").val(),
			        month: $("#statistics-month").val()
                },
                success: function(data) {
                	console.log(data.success);

                	if (user == "auth") {
	                    if ($("#your_exercise").html().length == 0) {
	                        $("#your_exercise").parent().height(300);
	                    }
                        if (your_chart) {
                            your_chart.destroy();
                        }
                        chartName = your_chart;

                	} else {
	                    if ($("#friend_exercise").html().length == 0) {
	                        $("#friend_exercise").parent().height(300);
	                    }
	                    if (friend_chart) {
                            friend_chart.destroy();
                        }
                        chartName = friend_chart;
                	}

                    if (data.success) {
                        FriendFunctions.compareExerciseChart(data.labels, data.series, data.exercise, data.max, chartId, chartName);
                    }
                    else {
                        $(chartId).html("<h3 style='margin: 0 0 10px 20px;'>No data for this exercise!</h3>");
                    }
                }
            });
        },

        compareExerciseChart: function(labels, series, exercise, max, canvas, chartName) {
            var ctx = $(canvas);
            chartName = new Chart(ctx, {
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
                        fontColor: '#3C4858'
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
        },
	}

	FriendFunctions.initSelects();
    FriendFunctions.init();

    $(document).on('change', your_picker, function() {
    	var exercise = $(your_picker).val();
        FriendFunctions.compareExercise('auth', exercise, '#your_exercise');
    });

    $(document).on('change', friend_picker, function() {
    	var exercise = $(friend_picker).val();
        FriendFunctions.compareExercise(friend_id, exercise, '#friend_exercise');
    });

	$(document).on('click', '#removeFriend', function() {
		var obj = $(this);
		var id = obj.attr('id');
		var name = $("#name").text();

		swal({
			title: 'Are you sure?',
			text: name + " will be removed from your friendslist!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, remove him/her!'
		}).then(function () {
			swal(
				'Removed!',
				'You just lost a friend ;(',
				'success'
			)
			removeFriend(id, obj);
		})
	})

	var removeFriend = function(id, obj) {
		obj.addClass('disabled');
		$.ajax({
			headers: {
	          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
			method: 'GET',
			url: '/api/friends/removeFriend',
			data: {
				id: id
			},
			success: function(data){
				if (data.error) {
					$.notify({
        				icon: "add_alert",
				        message: data.error

				    },{
				        type: 'danger',
				        timer: 4000,
				        placement: {
				            from: 'top',
				            align: 'right'
				        }
				    });
				    // If the function throws an error, enable the button again
				    obj.removeClass('disabled');
				} else {
					swal(
						'Done!',
						data.success,
						'success'
					)
					// Removes the row (the friend)
					obj.closest('.col-md-4').fadeOut();
					
				}
			}
		})
	}
});