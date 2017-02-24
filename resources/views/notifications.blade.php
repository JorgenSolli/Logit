@if (session('success'))
  <div class="alert alert-success alert-dismissible show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    {{ session('success') }}
  </div>
@endif

@if (session('danger'))
  <div class="alert alert-danger alert-dismissible show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    {{ session('danger') }}
  </div>
@endif