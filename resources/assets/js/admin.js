$(document).ready(function() {
	$("#preview-message").on('click', function() {
		swal({
            title: $("#message-title").val(),
            type: $("#message-type").val(),
            html: $("#message-body").val(),
            showCloseButton: true,
            showCancelButton: false,
            confirmButtonText: $("#message-button").val()
        });
	});
});