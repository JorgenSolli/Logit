@extends('layouts.app')
@section('content')
    @include('notifications')
    
    <div class="row">
        <div class="col-md-7">
            <form action="/user/settings/edit" method="post">
                {{ csrf_field() }}
                <div class="card">
                    <div class="card-header card-header-icon" data-background-color="rose">
                        <i class="material-icons">settings</i>
                    </div>
                    <div class="card-content">
                        <h4 class="card-title">Your personal settings</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group label-floating">
                                    <select id="country" class="form-control" name="timezone" data-style="btn btn-primary" title="Your Timezone">
                                        @if ($settings && $settings->timezone)
                                            <option disabled selected> Your timezone</option>
                                            <option value="{{ $settings->timezone }}" selected> {{ $settings->timezone }}</option>
                                            @include('user.timezoneOptions')
                                        @else
                                            <option disabled selected> Your timezone</option>
                                            @include('user.timezoneOptions')
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group label-floating">
                                    <select id="country" class="selectpicker" name="unit" data-style="btn btn-primary" title="Prefered Unit">
                                        <option disabled selected> Prefered units</option>
                                        @if ($settings && $settings->unit)
                                            @if ($settings->unit == 'Imperial')
                                                <option value="Imperial" selected> Imperial (pounds, inches)</option>
                                                <option value="Metric"> Metric (kilograms)</option>
                                            @else
                                                <option value="Metric" selected> Metric (kilograms, centimeters)</option>
                                                <option value="Imperial"> Imperial (pounds)</option>
                                            @endif
                                        @else
                                            <option value="Imperial" selected> Imperial (pounds, inches)</option>
                                            <option value="Metric"> Metric (kilograms, centimeters)</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group label-floating">
                                    <div class="pull-left togglebutton">
                                        <label>
                                            @if ($settings)
                                                @if ($settings->recap === 1)
                                                    <input name="recap" type="checkbox" checked="">
                                                @else
                                                    <input name="recap" type="checkbox">
                                                @endif
                                            @else
                                                {{-- Becayse the std value is 1 --}}
                                                <input name="recap" type="checkbox" checked="">
                                            @endif
                                            Show recap after workout
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group label-floating">
                                    <div class="pull-left togglebutton">
                                        <label>
                                            @if ($settings)
                                                @if ($settings->share_workouts === 1)
                                                    <input name="share_workouts" type="checkbox" checked="">
                                                @else
                                                    <input name="share_workouts" type="checkbox">
                                                @endif
                                            @else
                                                <input name="share_workouts" type="checkbox">
                                            @endif
                                            Let friends see your workout activity
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group label-floating">
                                    <div class="pull-left togglebutton">
                                        <label>
                                            @if ($settings)
                                                @if ($settings->accept_friends === 1)
                                                    <input name="accept_friends" type="checkbox" checked="">
                                                @else
                                                    <input name="accept_friends" type="checkbox">
                                                @endif
                                            @else
                                                <input name="accept_friends" type="checkbox">
                                            @endif
                                            Let others send you friend requests
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group label-floating">
                                    <div class="pull-left togglebutton">
                                        <label>
                                            @if ($settings)
                                                @if ($settings->strict_previous_exercise === 1)
                                                    <input name="strict_previous_exercise" type="checkbox" checked="">
                                                @else
                                                    <input name="strict_previous_exercise" type="checkbox">
                                                @endif
                                            @else
                                                <input name="strict_previous_exercise" type="checkbox">
                                            @endif
                                            Strict "Previously lifted" data 
                                        </label>
                                    </div>
                                    <i class="pull-left m-l-10 material-icons material-icons-sm pointer" 
                                        rel="tooltip" 
                                        data-placement="top" 
                                        title="When doing an exercies, we look for previously completed exercies and let you know how much you lifted. Would you like us to look for the exercies strictly in your current routine, or look in all routines for the same exercise?">
                                        help
                                    </i>
                                </div>

                                <div class="form-group label-floating">
                                    <div class="pull-left togglebutton">
                                        <label>
                                            @if ($settings)
                                                @if ($settings->count_warmup_in_stats === 1)
                                                    <input name="count_warmup_in_stats" type="checkbox" checked="">
                                                @else
                                                    <input name="count_warmup_in_stats" type="checkbox">
                                                @endif
                                            @else
                                                <input name="count_warmup_in_stats" type="checkbox">
                                            @endif
                                            Let warmup sets influence your statistics
                                        </label>
                                    </div>
                                    <i class="pull-left m-l-10 material-icons material-icons-sm pointer" 
                                        rel="tooltip" 
                                        data-placement="top" 
                                        title="If you have dedicates sets to warmin up, allowing this will influence especially your 'Musclegroups worked out' stats">
                                        help
                                    </i>
                                </div>

                                <div class="form-group label-floating">
                                    <div class="pull-left togglebutton">
                                        <label>
                                            @if ($settings)
                                                @if ($settings->use_timer === 1)
                                                    <input name="use_timer" type="checkbox" checked="">
                                                @else
                                                    <input name="use_timer" type="checkbox">
                                                @endif
                                            @else
                                                <input name="use_timer" type="checkbox">
                                            @endif
                                            Show timer when exercising
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-rose pull-right">Update Settings</button>
                        <div class="clearfix"></div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-5">
            <form class="form-horizontal" role="form" method="POST" action="{{ route('password.request') }}">
                {{ csrf_field() }}

                <div class="card">
                    <div class="card-header card-header-icon" data-background-color="rose">
                        <i class="material-icons">lock</i>
                    </div>
                    <div class="card-content">
                        <h4 class="card-title">Change Password <small>Not yet implemented</small></h4>
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
                        <button type="submit" class="btn btn-rose pull-right disabled">Change Password</button>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="rose">
                    <i class="material-icons">functions</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title">Awesome Logit functions</h4>
                    
                    <h5 class="card-title">Rename an exercise</h5>
                    <form action="/user/settings/renameExercise" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-5">
                                <select class="selectpicker sp-added-height" name="old_name" data-style="btn btn-primary" data-live-search="true" title="Select exercise" data-size="7">
                                    @foreach ($exercises as $exercise)
                                        <option value="{{ $exercise->exercise_name }}">{{ $exercise->exercise_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-sm-5">
                                <input type="text" name="new_name" placeholder="New name" class="form-control" />
                            </div>
                            
                            <div class="col-sm-2">
                                <input type="submit" class="btn btn-rose" value="Do it!" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(".bs-searchbox input.form-control").attr('placeholder', 'Search for an exercise');
        });
    </script>
@endsection