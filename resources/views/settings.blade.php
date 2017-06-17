@extends('layouts.app')

@section('content')
  <h1 class="page-header">
    Settings<br>
    <small>Get your profile up to speed</small>
  </h1>

  <form method="post" action="">
    <div class="row">

      <div class="col-lg-6 col-md-6 col-sm-12">
        <h3>About you</h3>
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" class="form-control" name="name" placeholder="Name">
        </div>

        <div class="form-group">
          <label for="email">Email address</label>
          <input type="email" class="form-control" name="email" placeholder="Email">
        </div>

        <div class="form-group">
          <label for="age">Age</label>
          <input type="number" class="form-control" name="age" placeholder="Age">
        </div>

        <div class="form-group">
          <label for="gender">Gender</label>
          <select name="gender" class="form-control">
            <option value="" selected disabled>Select one...</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
            <option value="not-say">I'd rather not say</option>
          </select>
        </div>

        <div class="form-group">
          <label for="location">Location</label>
          <select id="location" name="location" class="form-control">
            <option value="" selected disabled>Select one...</option>
          </select>
        </div>
      </div>
      
      <div class="col-lg-6 col-md-6 col-sm-12">
        <h3>Other settings</h3>

        <div class="checkbox">
          <label>
            <input type="checkbox"> Check me out
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox"> Check me out
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox"> Check me out
          </label>
        </div>
      </div>
    
    </div>

    <button type="submit" class="btn btn-success is-fullwidth">SAVE IT! <span class="fa fa-hand-peace-o"></span></button>
  </form>
@endsection
@section('script')
  <script src="/js/settings.js"></script>
@endsection