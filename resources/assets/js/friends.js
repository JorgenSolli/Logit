$(document).ready(function() {

	$("#findPeople").on('click', function() {
		var string = $("#searchString").val()
		var table = $("#people-result")

		$.ajax({
			headers: {
	          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
			url: '/dashboard/friends/findFriends',
			data: {
				q: string
			},
			success: function(users) {

				if (users.error) {
					data = "!";
					$.notify({
        				icon: "add_alert",
				        message: "Search field cannot be empty"

				    },{
				        type: 'danger',
				        timer: 4000,
				        placement: {
				            from: 'top',
				            align: 'right'
				        }
				    });
				} else {
					var data = '<table class="table">' +
			                    '<thead class="text-primary">' +
				                  	'<tr>' +
					                    '<th>Name</th>' +
					                    '<th>Email</th>' +
					                    '<th>Action</th>' +
				                  	'</tr>' +
			                  	'</thead>' +
		                  	'<tbody>';

					for (var i = 0; i < users.users.length; i++) {
						data += '<tr>' +
			                        '<td>' + users.users[i].name + '</td>' +
			                        '<td>' + users.users[i].email +'</td>' +
			                        '<td id="' + users.users[i].id + '">' +
			                        	'<a class="addfriend pointer btn btn-sm btn-success">send request</a>' +
		                        	'</td>' +
	                      		'</tr>';
					}

					data += '</tbody>' +
	                    '</table>';

                    table.html(data)
				}
			}
		})
	})

	$(document).on('click', '.addfriend', function() {
		var id = $(this).parent().attr('id')
		var obj = $(this);
		obj.addClass('disabled');
        obj.html('<span class="fal fa-spin fa-circle-notch"></span> Sending request ...');
		$.ajax({
			headers: {
	          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
			method: 'GET',
			url: '/dashboard/friends/sendRequest',
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
					swal(
						'Done!',
						data.success,
						'success'
					)

					obj.removeClass('disabled addfriend');
        			obj.html('<span class="fal fa-check"></span> Request sent!');
				}
			}
		})
	})

	$(document).on('click', '.respondRequest', function() {
		var id = $(this).attr('id')
		var obj = $(this);
		obj.addClass('disabled');

		$.ajax({
			headers: {
	          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
			method: 'GET',
			url: '/dashboard/friends/respondRequest',
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
					swal({
						title: 'Done!',
						text: data.success,
						type: 'success',
						confirmButtonText: 'Fuck yeah!'
					}).then(function () {
						return location.reload();
					});
				}
			}
		})
	})
	
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
})