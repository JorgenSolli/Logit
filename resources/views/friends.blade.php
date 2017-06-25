@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-md-7">
      <div class="card">
          <div class="card-header card-header-icon" data-background-color="rose">
              <i class="material-icons">favorite</i>
          </div>
          <div class="card-content">
              <h4 class="card-title">Friends</h4>
              @if ($friends->count() > 0)
                @foreach ($friends as $friend)

                @endforeach
              @else
                <h6>You currently dont have any friends :(</h6>
              @endif
          </div>
      </div>
    </div>

    <div class="col-md-5">
      <div class="card">
          <div class="card-header card-header-icon" data-background-color="blue">
              <i class="material-icons">search</i>
          </div>
          <div class="card-content">
              <h4 class="card-title">Find people</h4>

              <div class="form-group">
                <div class="row">
                  <div class="col-md-8">
                    <input id="searchString" type="text" class="form-control" placeholder="Search by email or name"> 
                  </div>

                  <div class="col-md-4">
                    <button id="findPeople" class="btn btn-sm btn-fullwidth btn-primary">Find friend</button>
                  </div>

                </div>
              </div>

              <div id="people-result" class="table-responsive">
                
              </div>
          </div>
      </div>
    </div>
  </div>


@endsection

@section('script')
  <script src="/js/moment.js"></script>
  <script src="/js/friends.js"></script>
@endsection