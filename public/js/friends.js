$(document).ready(function() {

	$("#findPeople").on('click', function() {
		var string = $("#searchString").val()
		var table = $("#people-result")

		$.ajax({
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

                    table.append(data)
				}
			}
		})
	})

	$(document).on('click', '.addfriend', function() {
		var id = $(this).parent().attr('id')
		$.ajax({
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
				}
			}
		})

	})
	
})