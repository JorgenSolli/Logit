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
                            <div class="col-md-5 col-xs-12">
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
                                    <select id="units" class="selectpicker" name="unit" data-style="btn btn-primary" title="Prefered Unit">
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
                            <div class="col-md-7 col-xs-12 settings-toggles">
                                <div class="form-group label-floating">
                                    <div class="togglebutton">
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
                                    <div class="togglebutton">
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
                                    <div class="togglebutton">
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
                                    <div class="togglebutton">
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
                                            <i class="m-l-10 material-icons material-icons-sm pointer"
                                                rel="tooltip"
                                                data-placement="top"
                                                title="When doing an exercise, we fetch last completed exercise and let you know how much you lifted last time. Would you like us to look for the exercise strictly in your current routine, or in all routines?">
                                                help
                                            </i>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group label-floating">
                                    <div class="togglebutton">
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
                                            <i class="m-l-10 material-icons material-icons-sm pointer"
                                                rel="tooltip"
                                                data-placement="top"
                                                title="If you have dedicated sets to warming up, allowing this will heavily influence your 'Musclegroups worked out' and other statistics">
                                                help
                                            </i>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group label-floating">
                                    <div class="togglebutton">
                                        <label>
                                            @if ($settings)
                                                @if ($settings->strict_notes === 1)
                                                    <input name="strict_notes" type="checkbox" checked="">
                                                @else
                                                    <input name="strict_notes" type="checkbox">
                                                @endif
                                            @else
                                                <input name="strict_notes" type="checkbox">
                                            @endif
                                            Show notes based on routine
                                            <i class="m-l-10 material-icons material-icons-sm pointer"
                                                rel="tooltip"
                                                data-placement="top"
                                                title="If on, a note will only appear on the same routine as it was set.">
                                                help
                                            </i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-rose pull-right" value="Update Settings" />
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
                                <input id="password" type="password" class="form-control" name="password" required placeholder="New Password">

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
        <div class="col-md-5">
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="rose">
                    <i class="material-icons">timer</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title">Timer settings</h4>
                    <form action="/user/settings/edit/timer" method="post">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group label-floating clearfix">
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
                            <div class="col-md-6">
                                <div class="form-group label-floating clearfix">
                                    <div class="pull-left togglebutton">
                                        <label>
                                            @if ($settings)
                                                @if ($settings->timer_play_sound === 1)
                                                    <input name="timer_play_sound" type="checkbox" checked="">
                                                @else
                                                    <input name="timer_play_sound" type="checkbox">
                                                @endif
                                            @else
                                                <input name="timer_play_sound" type="checkbox">
                                            @endif
                                            Play sound when timer reaches target
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <select class="selectpicker sp-added-height"
                                name="timer_direction"
                                data-style="btn btn-primary"
                                title="Timer type">
                            @if ($settings->timer_direction == "default")
                                <option value="default" selected>Default timer</option>
                            @else
                                <option value="default">Default timer</option>
                            @endif

                            @if ($settings->timer_direction == "countdown")
                                <option value="countdown" selected>Countdown timer</option>
                            @else
                                <option value="countdown">Countdown timer</option>
                            @endif
                        </select>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group label-floating">
                                    <label class="control-label">Target minutes</label>
                                    <input type="number" class="form-control"
                                           name="timer_minutes"
                                           value="@if ($settings && $settings->timer_minutes){{ $settings->timer_minutes }}@endif">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group label-floating">
                                    <label class="control-label">Target seconds</label>
                                    <input type="number" class="form-control"
                                           name="timer_seconds"
                                           value="@if ($settings && $settings->timer_seconds){{ $settings->timer_seconds }}@endif">
                                </div>
                            </div>
                        </div>

                        <input type="submit" class="btn btn-rose pull-right" value="Save"/>
                        <div class="clearfix"></div>
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