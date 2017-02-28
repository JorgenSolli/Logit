$(document).ready(function(){
	$("#exercises a").on('click', function() {
		var exerciseId = $(this).attr('id');
		$.ajax({
			url: '/api/exercise/' + exerciseId,
			method: 'GET',
			success: function(data) {
				$("#data").html(data['data'])
			},
		})
	});

	$(".viewWorkout").on('click', function() {
	  var routineId = $(this).children('input').val();

	  $.ajax({
	    url: '/api/get_workout/view/' + routineId,
	    method: 'GET',
	    success: function(data) {
	      $("#modalData").html(data['data']);
	    },
	  })
	});
});