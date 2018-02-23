	<nav class="navbar navbar-primary navbar-transparent navbar-absolute">
	    <div class="container">
	        <div class="navbar-header">
	            <button type="button" class="navbar-toggle" data-toggle="collapse">
	                <span class="sr-only">Toggle navigation</span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	            </button>
	            <a class="navbar-brand" href="/">Logit</a>
	        </div>
	        <div class="collapse navbar-collapse">
	            <ul class="nav navbar-nav navbar-right">
	            	@guest
		                <li class="{{ (Request::is('login') ? 'active' : '') }}">
		                    <a href="{{ route('login') }}">
		                        <i class="material-icons">fingerprint</i> Login
		                    </a>
		                </li>
		                <li class="{{ (Request::is('register') ? 'active' : '') }}">
		                    <a href="{{ route('register') }}">
		                        <i class="material-icons">person_add</i> Register
		                    </a>
		                </li>
	            	@else
		                <li>
		                    <a href="{{ route('dashboard') }}">
		                        <i class="material-icons">home</i> Launch Logit
		                    </a>
		                </li>
		                <li>
	                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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