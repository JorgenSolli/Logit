@extends('layouts.app')
@section('content')
    @include('notifications')
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="rose">
                    <i class="material-icons">perm_identity</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title">Edit Profile -
                        <small class="category">Complete your profile</small>
                    </h4>
                    <form action="/user/edit" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group label-floating">
                                    <label class="control-label">Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ $brukerinfo->name }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group label-floating">
                                    <label class="control-label">Year of birth</label>
                                    <input type="number" name="yob" class="form-control" value="{{ $brukerinfo->yob }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group label-floating">
                                    <label class="control-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $brukerinfo->email }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <select id="country" class="form-control" name="country" title="Single Select">
                                    @if ($brukerinfo->country)
                                        <option disabled selected> Where do you live?</option>
                                        <option value="{{ $brukerinfo->country }}" selected> {{ $brukerinfo->country }}</option>
                                    @else
                                        <option disabled selected> Where do you live?</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="selectpicker" name="gender" data-style="btn btn-primary" title="Single Select">
                                    @if ($brukerinfo->gender)
                                        <option disabled selected> Gender</option>
                                        <option value="{{ $brukerinfo->gender }}" selected> {{ $brukerinfo->gender }}</option>
                                    @else
                                        <option disabled selected> Gender</option>
                                    @endif

                                    @unless ($brukerinfo->gender == 'Male') 
                                        <option value="Male"> Male</option> 
                                    @endunless

                                    @unless ($brukerinfo->gender == 'Female') 
                                        <option value="Female"> Female</option> 
                                    @endunless

                                    @unless ($brukerinfo->gender == 'Other') 
                                        <option value="Other"> Other</option> 
                                    @endunless

                                    @unless ($brukerinfo->gender == 'secret') 
                                        <option value="secret"> I'd rather not say</option> 
                                    @endunless
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="selectpicker" name="goal" data-style="btn btn-primary" title="Single Select">
                                    @if ($brukerinfo->goal)
                                        <option disabled selected> Goal</option>
                                        <option value="{{ $brukerinfo->goal }}" selected> {{ $brukerinfo->goal }}</option>
                                    @else
                                        <option disabled selected> Goal</option>
                                    @endif
                                    @unless ($brukerinfo->goal == 'Stay in shape') 
                                        <option value="Stay in shape"> Stay in shape</option>  
                                    @endunless
                                    
                                    @unless ($brukerinfo->goal == 'Get lean') 
                                        <option value="Get lean"> Get in shape</option>  
                                    @endunless
                                    
                                    @unless ($brukerinfo->goal == 'Loose weight') 
                                        <option value="Loose weight"> Loose weight</option>  
                                    @endunless

                                    @unless ($brukerinfo->goal == 'Loose weight') 
                                        <option value="Loose weight"> Improve cardio</option>  
                                    @endunless
                                    
                                    @unless ($brukerinfo->goal == 'Increase self esteem') 
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
                <div class="card-content">
                    <h6 class="category text-gray">{{-- $brukerifo->title --}}</h6>
                    <h4 class="card-title">{{ $brukerinfo->name }}</h4>

                    <h7>Your random quote:</h7>
                    <p class="description">
                        <span id="quoteText"></span>
                        <br>
                        - <span id="quoteAuthor"></span>
                    </p>
                    <a id="newQuote" class="btn btn-rose btn-round">New Quote</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('/js/myProfile.min.js') }}"></script>
@endsection