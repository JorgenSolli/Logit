@extends('layouts.app')
@section('content')
    @include('notifications')
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="rose">
                    <i class="material-icons">perm_identity</i>
                </div>
                <div class="card-body">
                    <h4 class="card-title">Edit Profile -
                        <small class="category">Complete your profile</small>
                    </h4>
                    <form action="/user/edit" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group label-floating">
                                    <label class="control-label bmd-label-floating">Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group label-floating">
                                    <label class="control-label bmd-label-floating">Year of birth</label>
                                    <input type="number" name="yob" class="form-control" value="{{ $user->yob }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group label-floating">
                                    <label class="control-label bmd-label-floating">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <select id="country" class="selectpicker" data-live-search="true" data-style="btn btn-primary" name="country" title="Where do you live?">
                                    @if ($user->country)
                                        <option value="{{ $user->country }}" selected> {{ $user->country }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="selectpicker" name="gender" data-style="btn btn-primary" title="Gender">
                                    @if ($user->gender)
                                        <option disabled selected> Gender</option>
                                        <option value="{{ $user->gender }}" selected> {{ $user->gender }}</option>
                                    @else
                                        <option disabled selected> Gender</option>
                                    @endif

                                    @unless ($user->gender == 'Male') 
                                        <option value="Male"> Male</option> 
                                    @endunless

                                    @unless ($user->gender == 'Female') 
                                        <option value="Female"> Female</option> 
                                    @endunless

                                    @unless ($user->gender == 'Other') 
                                        <option value="Other"> Other</option> 
                                    @endunless

                                    @unless ($user->gender == 'secret') 
                                        <option value="secret"> I'd rather not say</option> 
                                    @endunless
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="selectpicker" name="goal" data-style="btn btn-primary" title="Goal">
                                    @if ($user->goal)
                                        <option disabled selected> Goal</option>
                                        <option value="{{ $user->goal }}" selected> {{ $user->goal }}</option>
                                    @else
                                        <option disabled selected> Goal</option>
                                    @endif
                                    @unless ($user->goal == 'Stay in shape') 
                                        <option value="Stay in shape"> Stay in shape</option>  
                                    @endunless
                                    
                                    @unless ($user->goal == 'Get lean') 
                                        <option value="Get lean"> Get in shape</option>  
                                    @endunless
                                    
                                    @unless ($user->goal == 'Loose weight') 
                                        <option value="Loose weight"> Loose weight</option>  
                                    @endunless

                                    @unless ($user->goal == 'Loose weight') 
                                        <option value="Loose weight"> Improve cardio</option>  
                                    @endunless
                                    
                                    @unless ($user->goal == 'Increase self esteem') 
                                        <option value="Increase self esteem"> Increase self esteem</option>  
                                    @endunless
                                </select>
                            </div>    
                        </div>

                        <button type="submit" class="btn btn-rose pull-right">Update Profile</button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card card-profile">
                <div class="card-avatar">
                    <img class="img" src="/img/avatar.png" />
                </div>
                <div class="card-body">
                    <h6 class="category text-gray">Master of the gym</h6>
                    <h4 class="card-title">{{ $user->name }}</h4>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('/js/myProfile.min.js') }}"></script>
@endsection