@extends('layouts.app')
@section('content')
  <div class="row">
    <div class="col-xs-12">
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
                <li class="">
                  <a href="#find" data-toggle="tab" aria-expanded="false">
                    <i class="material-icons">search</i> Find friends
                    <div class="ripple-container"></div>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="card-content table-responsive">
          <div class="tab-content m-t-0">
            <div class="tab-pane active" id="your-friends">
              @if ($friends->count() > 0)
                <div class="row">
                  @foreach ($friends as $friend)
                    <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="card card-pricing card-raised">
                        <div class="card-content">
                          <h6 class="category">{{ $friend->email }}</h6>
                          <div class="icon icon-primary">
                              <i class="material-icons">person</i>
                          </div>
                          <h3 class="card-title name">{{ $friend->name }}</h3>
                          <p class="card-description">
                              Friends since {{ Carbon\Carbon::parse($friend->created_at)->format('d M Y') }}
                          </p>
                          <a id="{{ $friend->id }}" class="compareStats btn btn-sm btn-primary disabled">
                            <i class="material-icons">compare_arrows</i> Compare stats (coming soon)
                          </a>

                          <a id="{{ $friend->id }}" class="removeFriend btn btn-sm btn-danger">
                            <i class="material-icons">close</i> Remove friend
                          </a>
                        </div>
                      </div>
                    </div>

                  @endforeach
                </div>
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
                          <a id="{{ $person->id }}" class="pointer respondRequest declineRequest">
                            <i class="material-icons">close</i>
                          </a>
                          <a id="{{ $person->id }}" class="pointer respondRequest acceptRequest m-l-20">
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
            <div class="tab-pane" id="find">
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
    </div>
  </div>


@endsection

@section('script')
  <script src="{{ mix('/js/friends.min.js') }}"></script>
@endsection