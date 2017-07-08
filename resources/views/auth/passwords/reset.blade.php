<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
    <body>
        <nav class="navbar navbar-primary navbar-transparent navbar-absolute">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse">
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
                            <a href="/login">
                                <i class="material-icons">fingerprint</i> Login
                            </a>
                        </li>
                        <li>
                            <a href="/register">
                                <i class="material-icons">person_add</i> Register
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="wrapper wrapper-full-page">
            <div class="full-page full-page-fixed login-page home-bg-full">
                <div class="content">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
                                <form class="form-horizontal" role="form" method="POST" action="{{ route('password.request') }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="card card-login card-hidden">
                                        <div class="text-center">
                                            <h3 class="card-title">Reset Password</h3>
                                        </div>
                                        <div class="card-content">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">email</i>
                                                </span>

                                                <div class="form-group label-floating {{ $errors->has('email') ? ' has-error' : '' }}">
                                                    <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus placeholder="Email Address">
                                                    @if ($errors->has('email'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">lock</i>
                                                </span>

                                                <div class="form-group label-floating {{ $errors->has('password') ? ' has-error' : '' }}">
                                                    <input id="password" type="password" class="form-control" name="password" required autofocus placeholder="New Password">

                                                    @if ($errors->has('password'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">lock_outline</i>
                                                </span>
                                                <div class="form-group label-floating{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm Password">

                                                    @if ($errors->has('password_confirmation'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                        <div class="footer text-center">
                                            <button type="submit" class="btn btn-rose btn-simple btn-wd btn-lg">Reset Password</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
            </div>
        </div>
        <script type="text/javascript">
            $().ready(function() {
                logit.checkFullPageBackgroundImage();

                setTimeout(function() {
                    // after 1000 ms we add the class animated to the login/register card
                    $('.card').removeClass('card-hidden');
                }, 700)
            });
        </script>
    </body>
</html>                      