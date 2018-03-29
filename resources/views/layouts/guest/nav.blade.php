	<nav class="navbar navbar-expand-lg bg-primary navbar-transparent navbar-absolute" color-on-scroll="500">
	    <div class="container">
			<div class="navbar-wrapper">
	        	<a class="navbar-brand" href="{{ url("/") }}">Logit</a>
	      	</div>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
				<span class="sr-only">Toggle navigation</span>
				<span class="navbar-toggler-icon icon-bar"></span>
				<span class="navbar-toggler-icon icon-bar"></span>
				<span class="navbar-toggler-icon icon-bar"></span>
			</button>
			<div class="collapse navbar-collapse justify-content-end" id="navbar">
				<ul class="navbar-nav">
					@guest
		                <li class="nav-item {{ (Request::is('login') ? 'active' : '') }}">
		                    <a class="nav-link" href="{{ route('login') }}">
		                        <i class="material-icons">fingerprint</i> Login
		                    </a>
		                </li>
		                <li class="nav-item {{ (Request::is('register') ? 'active' : '') }}">
		                    <a class="nav-link" href="{{ route('register') }}">
		                        <i class="material-icons">person_add</i> Register
		                    </a>
		                </li>
	            	@else
		                <li class="nav-item">
		                    <a class="nav-link" href="{{ route('dashboard') }}">
		                        <i class="material-icons">home</i> Launch Logit
		                    </a>
		                </li>
		                <li class="nav-item">
	                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
	                            <i class="material-icons">input</i> Logout
	                        </a>
	                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
	                        	{{ csrf_field() }}
	                      	</form>
	                    </li>
	            	@endguest
				</ul>
			</div>
	    </div>
  	</nav>