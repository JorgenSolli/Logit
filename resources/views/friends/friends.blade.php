@extends('layouts.app')
@section('content')

    <div class="card">
        <div class="card-header card-header-danger">
            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a href="#your-friends" class="nav-link active" data-toggle="tab">
                                <i class="material-icons">favorite</i> Your Friends ({{ $friends->count() }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#pending" class="nav-link" data-toggle="tab">
                                <i class="material-icons">loyalty</i> Pending ({{ $pending->count() }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#find" class="nav-link" data-toggle="tab">
                                <i class="material-icons">search</i> Find friends
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive">
            <div class="tab-content m-t-0">
                <div class="tab-pane active" id="your-friends">
                    @if ($friends->count() > 0)
                        <div class="row">
                            @php $count = 0; @endphp
                            @foreach ($friends as $friend)
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="card card-pricing card-raised mb-0">
                                        <div class="card-body">
                                            <h6 class="card-category">{{ $friend->email }}</h6>
                                            <div class="card-icon icon-primary">
                                                <i class="material-icons">person</i>
                                            </div>
                                            <h3 class="card-title name">{{ $friend->name }}</h3>
                                            <p class="card-description">
                                                Friends since {{ Carbon\Carbon::parse($friend->created_at)->format('d M Y') }}
                                            </p>
                                            <a href="{{ url("/friends/{$friend->user_id}") }}" class="btn btn-sm btn-primary">
                                                View profile
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
                            <div class="col-8">
                                <input id="searchString" type="text" class="form-control" placeholder="Search by email or name">
                            </div>

                            <div class="col-4">
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
  <script src="{{ mix('/js/friends.min.js') }}"></script>
@endsection