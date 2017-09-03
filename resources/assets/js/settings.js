$(document).ready(function() {
	$.ajax({
		headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
		url: 'https://restcountries.eu/rest/v2/all',
		method: 'GET',
		success: function(data) {
			for (var i = 0; i < data.length; i ++) {
				$("#location").append('<option value="' + data[i].name + '">' + data[i].name + '</option>')
			}
		}
	})
})