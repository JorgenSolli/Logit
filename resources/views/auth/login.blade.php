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
                        <div class="col-md-4 ml-auto mr-auto">
                            <div class="card card-login card-hidden">
                                <div class="card-header card-header-info text-center">
                                    <h4 class="card-title">Login</h4>
                                    <div class="social-line">
                                        <a href="{{ url('/auth/google') }}" class="btn btn-just-icon btn-link btn-white">
                                            <i class="fab fa-google"></i>
                                        </a>

                                        <button class="btn btn-just-icon btn-link btn-white disabled">
                                            <i class="fab fa-facebook-square"></i>
                                        </button>

                                        <button class="btn btn-just-icon btn-link btn-white disabled">
                                            <i class="fab fa-twitter"></i>
                                        </button>
                                    </div>
                                </div>
                                <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                                    {{ csrf_field() }}
                                    <div class="card-body">
                                        <p class="card-description text-center">Or Be Classical</p>
                                        <span class="bmd-form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="material-icons">face</i>
                                                    </span>
                                                </div>
                                                <input id="email" type="email" class="form-control" name="email" value="@if(session('email')){{ session('email')}}@endif{{ old('email') }}" required autofocus placeholder="Email Address">
                                                @if ($errors->has('email'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('email') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </span>

                                        <span class="bmd-form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="material-icons">lock_outline</i>
                                                    </span>
                                                </div>
                                                <input id="password" type="password" class="form-control" name="password" required placeholder="Password">
                                                @if ($errors->has('password'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('password') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </span>

                                        <div class="form-check text-center">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" checked="checked" name="remember" id="remember">
                                                Remember me
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-footer justify-content-center text-center">
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-rose btn-link btn-lg">Let's go</button>
                                            </div>
                                            <div class="col-12">
                                                <a href="{{ url("/password/reset") }}" class="btn btn-sm btn-link btn-primary">Forgot your password?</a>
                                            </div>
                                        </div>
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