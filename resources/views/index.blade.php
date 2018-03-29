
<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body class="off-canvas-sidebar pricing-page">
	@include('layouts.guest.nav')
	<div class="wrapper wrapper-full-page">
    	<div class="page-header pricing-page header-filter" 
    		style="background-image: url('/img/home-bg-full.jpg'); 
    		background-size: cover; 
    		background-position: top center;"
		>
	        <div class="container mb-lg-6">
				<div class="row">
					<div class="col-md-6 ml-auto mr-auto text-center">
						<h2 class="title">Welcome to Logit!</h2>
						<h5 class="description">The simple solution to logging your workouts.</h5>
					</div>
				</div>
			    <div class="row">
			        <div class="col-md-3">
			            <div class="card card-pricing card-raised card-hover">
			                <div class="card-body">
			                    <h6 class="card-category">The Lazy</h6>
			                    <div class="card-icon icon-rose">
			                        <i class="material-icons">weekend</i>
			                    </div>
			                    <h3 class="card-title">Easy to use</h3>
			                    <p class="card-description">
			                        Get going in a matter of minutes with intuitive setups.
			                    </p>
			                </div>
			            </div>
			        </div>
			        <div class="col-md-3">
			            <div class="card card-pricing card-raised card-hover">
			                <div class="card-body">
			                    <h6 class="card-category">The Nerd</h6>
			                    <div class="card-icon icon-danger">
			                        <i class="material-icons">show_chart</i>
			                    </div>
			                    <h3 class="card-title">GRAPHS!</h3>
			                    <p class="card-description">
			                        Track your progress with useful graphs and charts.
			                    </p>
			                </div>
			            </div>
			        </div>
			        <div class="col-md-3">
			            <div class="card card-pricing card-raised card-hover">
			                <div class="card-body">
			                    <h6 class="card-category">the modernist</h6>
			                    <div class="card-icon icon-primary">
			                        <i class="material-icons">smartphone</i>
			                    </div>
			                    <h3 class="card-title">Technology!</h3>
			                    <p class="card-description">
			                        Why bother with pen and paper when you got Logit? 
			                    </p>
			                </div>
			            </div>
			        </div>
			        <div class="col-md-3">
			            <div class="card card-pricing card-raised card-hover">
			                <div class="card-body">
			                    <h6 class="card-category">The Cheap ass</h6>
			                    <div class="card-icon icon-success">
			                        <i class="material-icons">money_off</i>
			                    </div>
			                    <h3 class="card-title">It's free!</h3>
			                    <p class="card-description">
			                        This is for everyone to enjoy. No ads will be on here either.
			                    </p>
			                </div>
			            </div>
			        </div>
			    </div>
				<div class="card">
					<div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">build</i>
                        </div>
						<h4 class="card-title">Core features</h4>
                    </div>
					<div class="card-body">
						<section class="row text-center panel-specials">
							<div class="col-sm-3 fadeIn">
								<h4><span class="fal fa-calendar-check"></span> Date specific statistics</h4>
								<p>Watch your progress as time passes. See when you're not as active as you might wish to be.</p>
							</div>

							<div class="col-sm-3 fadeIn">
								<h4><span class="fal fa-puzzle-piece"></span> Add custom Routines</h4>
								<p>Predefining  your routine saves you a lot of typing on your phone when working out.</p>
							</div>

							<div class="col-sm-3 fadeIn">
								<h4><span class="fal fa-heartbeat"></span> Easy workout walkthrough</h4>
								<p>Focus on keeping your heartbeat up instead of spending your time entering redundant data on your phone.</p>
							</div>

							<div class="col-sm-3 fadeIn">
								<h4><span class="fal fa-magic"></span> Good looks</h4>
								<p>I've worked hard to make sure the app both works well on all devices and looks good at the same time.</p>
							</div>
						</section>
					</div>	
				</div>
			</div>
			@include('layouts.footer')
		</div>
	</div>
	<!--             Core JS             -->
	<script src="{{ mix('/js/logit.min.js') }}"></script>
	@include('layouts.scriptNotifications')
	@section('script')
	@endsection
</body>
</html>
