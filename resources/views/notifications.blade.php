@if (session('success'))
  <div class="alert alert-success alert-with-icon">
    <i class="material-icons" data-notify="icon" >check</i>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
    <span data-notify="message"> {{ session('success') }} </span>
  </div>
@endif

@if (session('danger'))
  <div class="alert alert-danger alert-with-icon">
    <i class="material-icons" data-notify="icon" >error_outline</i>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
    <span data-notify="message"> {{ session('danger') }} </span>
  </div>
@endif