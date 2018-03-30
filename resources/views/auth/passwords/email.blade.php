<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
    <body>
        @include('layouts.guest.nav')
        <div class="wrapper wrapper-full-page">
            <div class="full-page full-page-fixed login-page home-bg-full">
                <div class="content">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4 ml-auto mr-auto">

                                <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
                                    {{ csrf_field() }}
                                    
                                    <div class="card card-login card-hidden">
                                        <div class="card-header card-header-info text-center">
                                            Reset Your Password
                                        </div>
                                        <div class="card-body">
                                            <span class="bmd-form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="material-icons">email</i>
                                                        </span>
                                                    </div>
                                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="Email Address">
                                                    <div class="form-group label-floating {{ $errors->has('email') ? ' has-error' : '' }}">
                                                        @if ($errors->has('email'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('email') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </span>
                                        </div>
                                        <div class="card-footer justify-content-center text-center">
                                            <button type="submit" class="btn btn-rose btn-link btn-lg">Send Password Reset Link</button>
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
        <!--             Core JS             -->
        <script src="{{ mix('/js/logit.min.js') }}"></script>
        <script type="text/javascript">
            $().ready(function() {
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