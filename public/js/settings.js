$(document).ready(function() {
	$.ajax({
		url: 'https://restcountries.eu/rest/v2/all',
		method: 'GET',
		success: function(data) {
			for (var i = 0; i < data.length; i ++) {
				$("#location").append('<option value="' + data[i].name + '">' + data[i].name + '</option>')
			}
		}
	})
})