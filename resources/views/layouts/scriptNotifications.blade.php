@if (session('script_success'))
	<script>
		$(document).ready(function() {
			
		    $.notify({
		        icon: "add_alert",
		        message: "{!! session('script_success') !!}"

		    },{
		        type: 'success',
		        timer: 2000,
		        placement: {
		            from: 'top',
		            align: 'right'
		        }
		    });
		})
	</script>
@endif

@if (session('script_danger'))
	<script>
		$(document).ready(function() {
			
		    $.notify({
		        icon: "add_alert",
		        message: "{!! session('script_danger') !!}"

		    },{
		        type: 'danger',
		        timer: 2000,
		        placement: {
		            from: 'top',
		            align: 'right'
		        }
		    });
		})
	</script>
@endif