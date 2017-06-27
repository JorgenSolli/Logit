@extends('layouts.app')
@section('content')
  <div class="row">
    <div class="col-md-7">
      <div class="card">
        <div class="card-header card-header-tabs" data-background-color="rose">
          <div class="nav-tabs-navigation">
            <div class="nav-tabs-wrapper">
              <ul class="nav nav-tabs" data-tabs="tabs">
                <li class="active">
                  <a href="#your-friends" data-toggle="tab" aria-expanded="true">
                    <i class="material-icons">favorite</i> Your Friends ({{ $friends->count() }})
                    <div class="ripple-container"></div>
                  </a>
                </li>
                <li class="">
                  <a href="#pending" data-toggle="tab" aria-expanded="false">
                    <i class="material-icons">loyalty</i> Pending ({{ $pending->count() }})
                    <div class="ripple-container"></div>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="card-content table-responsive">
          <div class="tab-content">
            <div class="tab-pane active" id="your-friends">
              @if ($friends->count() > 0)
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Salary</th>
                      <th>Country</th>
                      <th>City</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($friends as $friend)
                      <tr>
                          <td>{{ $friend->name }}</td>
                          <td>{{ $friend->email }}</td>
                          <td>
                            <a id="{{ $friend->id }}" class="acceptRequest">
                              <i class="material-icons">done</i>
                            </a>
                          </td>
                          <td>Niger</td>
                          <td>Oud-Turnhout</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              @else
                <h6>You currently don't have any friends :(</h6>
              @endif
            </div>
            <div class="tab-pane" id="pending">
              @if ($pending->count() > 0)
                <h4 class="card-title">You have {{ $pending->count() }} pending friend requests</h4>
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Sent</th>
                      <th clasS="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($pending as $person)
                      <tr>
                        <td>{{ $person->name }}</td>
                        <td>{{ $person->email }}</td>
                        <td>{{ $person->created_at }}</td>
                        <td class="text-center">
                          <a id="{{ $person->id }}" class="pointer declineRequest">
                            <i class="material-icons">close</i>
                          </a>
                          <a id="{{ $person->id }}" class="pointer acceptRequest m-l-20">
                            <i class="material-icons">done</i>
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              @else
                <h6>You have no pending requests</h6>
              @endif
            </div>
          </div>
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