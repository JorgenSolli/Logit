
<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>
	<div class="wrapper wrapper-full-page">
	    <div class="full-page full-page-fixed pricing-page home-bg-full" data-image="/img/home-bg-full.jpg">
	        <div class="content">
				<div class="container">
				    <div class="row">
				        <div class="col-md-6 col-md-offset-3 text-center">
				            <h2 class="title">
				            	<i class="far fa-exclamation-triangle"></i> Whops!
				            </h2>
				            
				            <h5 class="description">
				            	{{ $error }}
				            </h5>
				        </div>
				    </div>
				</div>
			</div>
		</div>
	</div>
	<!--             Core JS             -->
	<script src="{{ mix('/js/logit.min.js') }}"></script>
	<script src="{{ mix('/js/material-dashboard.min.js') }}"></script>
</body>
</html>
