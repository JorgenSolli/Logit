
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
				            <h2 class="title">Whops!</h2>
		            		<h5 class="description">You account has not been activated yet. If you haven't recieved any email from us, type in you email and click send to try again.</h5>

		            		<form role="form" action="/register/resend" method="post">
		            			{{ csrf_field() }}
				            	<div class="input-group">
									<input type="text" class="form-control input-fill" name="email" placeholder="Your email" value="{{ $email }}">
									<span class="input-group-btn">
										<input type="submit" class="btn btn-primary" type="button" value="Send" />
									</span>
								</div>
							</form>
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