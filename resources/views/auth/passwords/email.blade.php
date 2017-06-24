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

                                <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
                                    {{ csrf_field() }}
                                    
                                    <div class="card card-login card-hidden">
                                        <div class="text-center">
                                            <h3 class="card-title">Reset Your Password</h3>
                                        </div>
                                        <div class="card-content">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">email</i>
                                                </span>

                                                <div class="form-group label-floating {{ $errors->has('email') ? ' has-error' : '' }}">
                                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="Email Address">
                                                    @if ($errors->has('email'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                        <div class="footer text-center">
                                            <button type="submit" class="btn btn-rose btn-simple btn-wd btn-lg">Send Password Reset Link</button>
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
        @include('layouts.scripts')
        <script type="text/javascript">
            $().ready(function() {
                logit.checkFullPageBackgroundImage();

                setTimeout(function() {
                    // after 1000 ms we add the class animated to the login/register card
                    $('.card').removeClass('card-hidden');
                }, 700)
            });
            @if (session('status'))
                swal({ title:"Great!", text: "An email has been sent to you with further details.", type: "success", buttonsStyling: false, confirmButtonClass: "btn btn-success"})
            @endif
        </script>
    </body>
</html>