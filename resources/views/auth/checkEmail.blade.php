
<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>
	@include('layouts.guest.nav')
	<div class="wrapper wrapper-full-page">
	    <div class="full-page full-page-fixed pricing-page home-bg-full" data-image="/img/home-bg-full.jpg">
	        <div class="content">
				<div class="container">
				    <div class="row">
				        <div class="col-md-6 col-md-offset-3 text-center">
				            <h2 class="title">Welcome to Logit!</h2>
				            @if (session('email') && session('name'))
				            	<h5 class="description">Thank you for registering, {{ session('name') }}! An email has been sent to <span class="label label-success">{{ session('email') }}</span> with further instructions.</h5>
			            	@else
			            		<h5 class="description">Nothing more to see here... Login or register</h5>
			            	@endif
				        </div>
				    </div>
					
				</div>
			</div>
		</div>
	</div>
	<!--             Core JS             -->
	<script src="{{ mix('/js/logit.min.js') }}"></script>
	<script src="{{ mix('/js/material-dashboard.min.js') }}"></script>
	
	@section('script')
	@endsection
</body>
</html>