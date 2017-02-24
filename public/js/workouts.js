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
});