<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>
    @include('layouts.guest.nav')
    <div class="wrapper wrapper-full-page">
        <div class="full-page full-page-fixed register-page bg-full">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 ml-auto mr-auto">
                        <div class="card card-signup">
                            <h2 class="card-title text-center">Register</h2>
                            <div class="row">
                                <div class="col-md-5 ml-auto">
                                    <div class="card-body">
                                        <div class="info info-horizontal">
                                            <div class="icon icon-rose">
                                                <i class="material-icons">timeline</i>
                                            </div>
                                            <div class="description">
                                                <h4 class="info-title">Stay in shape with Logit</h4>
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
                                                    Logit is available for everyone. Free of charge of course!
                                                </p>
                                            </div>
                                        </div>
                                        <div class="info info-horizontal">
                                            <div class="icon icon-primary">
                                                <i class="material-icons">person_add</i>
                                            </div>
                                            <div class="description">
                                                <h4 class="info-title">Connect with others</h4>
                                                <p class="description">
                                                    Connect with friends! Compare data and set challenges (comming)
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 mr-auto">
                                    <div class="social text-center">
                                        <a href="{{ url('/auth/google') }}" class="btn btn-just-icon btn-round btn-google">
                                            <i class="fab fa-google"></i>
                                        </a>
                                        
                                        <button class="btn btn-just-icon btn-round btn-twitter disabled">
                                            <i class="fab fa-twitter"></i>
                                        </button>
                                        
                                        <button class="btn btn-just-icon btn-round btn-facebook disabled">
                                            <i class="fab fa-facebook-f"> </i>
                                        </button>
                                        
                                        <h4 class="mt-4"> or be classical </h4>
                                    </div>

                                    <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                                        {{ csrf_field() }}
                                        <div class="card-body">
                                            <div class="form-group has-default bmd-form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="material-icons">face</i>
                                                        </span>
                                                    </div>
                                                    <input id="name" type="text" class="form-control" name="name" placeholder="First Name..." value="{{ old('name') }}" required autofocus>
                                                    @if ($errors->has('name'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group has-default bmd-form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="material-icons">email</i>
                                                        </span>
                                                    </div>
                                                    <input id="email" type="email" class="form-control" placeholder="Email..." name="email" value="{{ old('email') }}" required>
                                                    @if ($errors->has('email'))
                                                        <div class="invalid-feedback">
                                                            {{ $errors->first('email') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group has-default bmd-form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="material-icons">lock_outline</i>
                                                        </span>
                                                    </div>
                                                    <input id="password" type="password" class="form-control" placeholder="Password..." name="password" required>
                                                    @if ($errors->has('password'))
                                                        <div class="invalid-feedback">
                                                            {{ $errors->first('password') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group has-default bmd-form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="material-icons">lock_outline</i>
                                                        </span>
                                                    </div>
                                                    <input id="password-confirm" type="password" class="form-control" placeholder="And again..."name="password_confirmation" required>
                                                    @if ($errors->has('password'))
                                                        <div class="invalid-feedback">
                                                            {{ $errors->first('password') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center">
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
</body>
</html>