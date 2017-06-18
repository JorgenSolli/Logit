<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
    <body>
        <nav class="navbar navbar-primary navbar-transparent navbar-absolute">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">Loggit</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="login">
                                <i class="material-icons">fingerprint</i> Login
                            </a>
                        </li>
                        <li class="active">
                            <a href="/register">
                                <i class="material-icons">person_add</i> Register
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="wrapper wrapper-full-page">
            <div class="full-page register-page" filter-color="black" data-image="/img/bg-full.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="card card-signup">
                                <h2 class="card-title text-center">Register</h2>
                                <div class="row">
                                    <div class="col-md-5 col-md-offset-1">
                                        <div class="card-content">
                                            <div class="info info-horizontal">
                                                <div class="icon icon-rose">
                                                    <i class="material-icons">timeline</i>
                                                </div>
                                                <div class="description">
                                                    <h4 class="info-title">Stay in shape with Loggit</h4>
                                                    <p class="description">
                                                        Continue or start logging your workouts digitally! Be able to see progress and trends in your activities. 
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="info info-horizontal">
                                                <div class="icon icon-primary">
                                                    <i class="material-icons">favorite_border</i>
                                                </div>
                                                <div class="description">
                                                    <h4 class="info-title">Free for everyone</h4>
                                                    <p class="description">
                                                        Loggit is available for everyone. Free of charge of course!
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                                            {{ csrf_field() }}
                                            <div class="card-content">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="material-icons">face</i>
                                                    </span>
                                                    <input id="name" type="text" class="form-control" name="name" placeholder="First Name..." value="{{ old('name') }}" required autofocus>
                                                    @if ($errors->has('name'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('name') }}</strong>
                                                        </span>
                                                    @endif

                                                </div>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="material-icons">email</i>
                                                    </span>
                                                    <input id="email" type="email" class="form-control" placeholder="Email..." name="email" value="{{ old('email') }}" required>

                                                    @if ($errors->has('email'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="material-icons">lock_outline</i>
                                                    </span>

                                                    <input id="password" type="password" class="form-control" placeholder="Password..." name="password" required>
                                                    @if ($errors->has('password'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="material-icons">lock_outline</i>
                                                    </span>

                                                    <input id="password-confirm" type="password" class="form-control" placeholder="And again..."name="password_confirmation" required>
                                                    @if ($errors->has('password'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="footer text-center">
                                                <button type="submit" class="btn btn-primary">Get fit</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('layouts.footer')
                </div>
            </div>
        </div>

        @include('layouts.scripts')
        <script type="text/javascript">
            $().ready(function() {
                demo.checkFullPageBackgroundImage();

                setTimeout(function() {
                    // after 1000 ms we add the class animated to the login/register card
                    $('.card').removeClass('card-hidden');
                }, 700)
            });
        </script>
    </body>
</html>