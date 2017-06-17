$(document).ready(function(){
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
			},
		})
	});

	$(".viewWorkout").on('click', function() {
	  var routineId = $(this).children('input').val();

	  $.ajax({
	    url: '/api/get_workout/view/' + routineId,
	    headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
	    method: 'GET',
	    success: function(data) {
	      $("#modalData").html(data['data']);
	    },
	  })
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

  });

  var table = $('#datatables').DataTable();

});