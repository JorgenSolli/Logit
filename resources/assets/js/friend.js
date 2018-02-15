$(document).ready(function() {
	$(document).on('click', '.removeFriend', function() {
		var obj = $(this);
		var id = obj.attr('id')
		var name = obj.parent().find('.name').text()

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