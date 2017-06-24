@extends('layouts.app')
@section('content')
    @include('notifications')
    <div class="card">
        <div class="card-header card-header-icon" data-background-color="rose">
            <i class="material-icons">settings</i>
        </div>
        <div class="card-content">
            <h4 class="card-title">Your personal settings</h4>
            <form action="/user/settings/edit" method="post">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-4">
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
	                            @if ($settings && $settings->unit)
	                                <option disabled selected> Prefered units</option>
	                                @if ($settings->unit == 'Imperial')
	                                	<option value="Imperial" selected> Imperial (pounds)</option>
                            			<option value="Metric"> Metric (kilograms)</option>
	                                @else
		                            	<option value="Metric" selected> Metric (kilograms)</option>
		                            	<option value="Imperial"> Imperial (pounds)</option>
	                            	@endif
	                            @endif
	                        </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group label-floating">
                            <div class="togglebutton">
								<label>
									@if ($settings)
										@if ($settings->recap === 1)
							    			<input name="recap" type="checkbox" checked="">
										@else
							    			<input name="recap" type="checkbox">
										@endif
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
									@endif
									Let friends see your workout activity
								</label>
							</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group label-floating">
                            <div class="togglebutton">
								<label>
									@if ($settings)
										@if ($settings->accept_friends === 1)
							    			<input name="accept_friends" type="checkbox" checked="">
										@else
							    			<input name="accept_friends" type="checkbox">
										@endif
									@endif
									Let others send you friend requests
								</label>
							</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                </div>

                <button type="submit" class="btn btn-rose pull-right">Update Settings</button>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
@endsection
@section('script')

@endsection