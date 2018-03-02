<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>
    @include('layouts.guest.nav')
    <div class="wrapper wrapper-full-page">
        <div class="full-page full-page-fixed login-page bg-full">
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
                            <div class="card card-login card-hidden">
                                <div class="card-header text-center" data-background-color="blue">
                                    <h4 class="card-title">Login</h4>
                                    <div class="social-line">
                                        <a href="{{ url('/auth/google') }}" class="btn btn-just-icon btn-simple">
                                            <i class="fab fa-google"></i>
                                        </a>

                                        <a class="btn btn-just-icon btn-simple disabled">
                                            <i class="fab fa-facebook-square"></i>
                                        </a>

                                        <a class="btn btn-just-icon btn-simple disabled">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                    </div>
                                </div>
                                <p class="category text-center">
                                    Or Be Classical
                                </p>
                                <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                                    {{ csrf_field() }}
                                    <div class="card-content">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">face</i>
                                            </span>
                                            <div class="form-group label-floating {{ $errors->has('email') ? ' has-error' : '' }}">
                                                <input id="email" type="email" class="form-control" name="email" value="@if(session('email')){{ session('email')}}@endif{{ old('email') }}" required autofocus placeholder="Email Address">
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">lock_outline</i>
                                            </span>
                                            <div class="form-group label-floating {{ $errors->has('password') ? ' has-error' : '' }}">
                                                <input id="password" type="password" class="form-control" name="password" required placeholder="Password">
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="checkbox m-t-0 text-center">
                                            <label>
                                                <input type="checkbox" checked="checked" name="remember" id="remember">
                                                Remember me
                                            </label>
                                        </div>
                                    </div>
                                    <div class="footer text-center">
                                        <button type="submit" class="btn btn-rose btn-simple btn-wd btn-lg">Let's go</button>
                                        <a href="{{ url("/password/reset") }}" class="btn btn-sm btn-simple btn-primary m-t-0 m-b-0">Forgot your password?</a>
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
    <!--             Core JS             -->
    <script src="{{ mix('/js/logit.min.js') }}"></script>
    <script src="{{ mix('/js/material-dashboard.min.js') }}"></script>
    <script type="text/javascript">
        $().ready(function() {

            setTimeout(function() {
                // after 1000 ms we add the class animated to the login/register card
                $('.card').removeClass('card-hidden');
            }, 700)
        });
    </script>
    @include('layouts.scriptNotifications')
</body>
</html>