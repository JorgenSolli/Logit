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

	var your_chart, friend_chart, type, year, month, sessionsChart;

	FriendFunctions = {
		init: function() {
			FriendFunctions.populateExercises();
			FriendFunctions.populateCharts();
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
            type  = $("#statistics-type").val();
            year  = $("#statistics-year").val();
            month = $("#statistics-month").val();

            $.ajax({
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/friends/' + friend_id + '/populateExercises',
                data: {
                	friend_id: friend_id,
                    year: year,
                    month: month,
                    type: type
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

        populateCharts: function() {
            var type  = $("#statistics-type").val();
            var year  = $("#statistics-year").val();
            var month = $("#statistics-month").val();

            /* Data for session chart */
            $.ajax({
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/friends/' + friend_id + '/getSessionData',
                data: {
                	type: type,
                	year: year,
                	month: month,
                	user_id: friend_id
                },
                success: function(data) {
                    if (sessionsChart) {
                        sessionsChart.destroy();
                    }

                    FriendFunctions.sessionsChart(data.labels, data.series, data.meta, data.max, data.stepSize);
                }
            });
        },

        sessionsChart: function(labels, series, meta, max, stepSize) {
            var ctx = $("#workoutActivityChart");
            sessionsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
	                    label: 'Your sessions',
                        data: series.yours,
                        lineTension: 0,
                        borderWidth: 2,
                        borderColor: 'rgba(45, 204, 112, 1)',
                        backgroundColor: 'rgba(45, 204, 112, 0.15)',
                        fill: true,
	                }, {
	                    label: 'Friends sessions',
                        data: series.friends,
                        lineTension: 0,
                        borderWidth: 2,
                        borderColor: 'rgba(48, 151, 209, 1)',
                        backgroundColor: 'rgba(48, 151, 209, 0.15)',
                        fill: true,
	                }]
                },
                options: {
                    title: {
                        display: false,
                        lineHeight: 1,
                    },
                    layout: {
                        padding: {
                            top: 1,
                            right: 10,
                            bottom: 5,
                            left: 5
                        }
                    },
                    legend: {
                        display: true,
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                stepSize: stepSize,
                                min: 0,
                            },
                        }]
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            title: function(tooltipItem, data) {
                                var item = null;
                                if (meta) {
                                    var label = tooltipItem[0].xLabel;
                                    item = meta[label-1]
                                }
                                return item;
                            }
                        },
                    },
                    maintainAspectRatio: false,
                    responsive: true,
                    responsiveAnimationDuration: 100
                }
            });
        },

        compareExercise: function(user, exercise, chartId) {
        	var exerciseChart = $(chartId);
        	var chartOwner;
        	$.ajax({
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/friends/' + friend_id + '/getExerciseData',
                data: {
                	user_id: user,
                	exercise: exercise,
                	type:  $("#statistics-type").val(),
			        year:  $("#statistics-year").val(),
			        month: $("#statistics-month").val()
                },
                success: function(data) {

                	if (user == "auth") {
	                    if ($("#your_exercise").html().length == 0) {
	                        $("#your_exercise").parent().height(300);
	                    }
                        if (your_chart) {
                            your_chart.destroy();
                        }
                        chartOwner = "user";

                	} else {
	                    if ($("#friend_exercise").html().length == 0) {
	                        $("#friend_exercise").parent().height(300);
	                    }
	                    if (friend_chart) {
                            friend_chart.destroy();
                        }
                        chartOwner = "friend";
                	}

                    if (data.success) {
                        FriendFunctions.compareExerciseChart(data.labels, data.series, data.exercise, data.max, chartId, chartOwner);
                    }
                    else {
                        $(chartId).html("<h3 style='margin: 0 0 10px 20px;'>No data for this exercise!</h3>");
                    }
                }
            });
        },

        compareExerciseChart: function(labels, series, exercise, max, canvas, chartName) {
            var ctx = $(canvas);
            var data = {
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
            }

            if (chartName == "user"){
                your_chart = new Chart(ctx, data);
            } else {
                friend_chart = new Chart(ctx, data);
            }
        },

        removeFriend: function(id) {
            $.ajax({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'GET',
                url: '/friends/' + friend_id + '/remove',
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
                    } else {
                        window.location.href = "/friends";
                    }
                }
            });
        }
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

    $("#statistics-type").on('change', function() {
        type = $(this).val();
        console.log(type);
        if (type == "months") {
            $("#statistics-month").parent().show();
        } else {
            $("#statistics-month").parent().hide();
        }
    });

    $("#statistics-type, #statistics-year, #statistics-month").on('change', function() {
        type  = $("#statistics-type").val();
        year  = $("#statistics-year").val();
        month = $("#statistics-month").val();

        FriendFunctions.init();
    });

	$(document).on('click', '#removeFriend', function() {
		var id = $("#user_id").val();
		var name = $("#name").text();

		swal({
			title: 'Are you sure?',
			text: name + " will be removed from your friendslist!",
			type: 'warning',
			showCancelButton: true,
            confirmButtonClass: 'btn btn-danger',
            cancelButtonClass: 'btn btn-primary',
			confirmButtonText: 'Yes, remove him/her!',
            buttonsStyling: false
		}).then(function () {
			FriendFunctions.removeFriend(id);
		}).done();
	});

    $(document).on('change', '#routine', function() {
        var routineId = $(this).val();

        $.ajax({
            url: '/routines/preview',
            data: {
                routine: routineId,
                user_id: friend_id
            },
            success: function(response) {
                $("#previewModal").html(response.data);
                $("#routinePreview").modal('show');
            }
        });
    });

});