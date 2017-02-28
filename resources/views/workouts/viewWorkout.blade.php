<form action="/api/workout/{{ $workoutId }}" method="POST">
	<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Update your workout</h4>
  </div>
  <div class="modal-body">
		@foreach($workout as $exercise)
			{{ csrf_field() }}
		  {{ method_field('PUT') }}
			
			<h4>{{ $exercise->exercise_name }} set {{ $exercise->set_nr }}</h4>
			<input class="form-control" type="hidden" name="exercise_name" value="{{ $exercise->exercise_name }}">
			
			<div class="row">
				<div class="col-sm-6">
					<input class="form-control" type="text" name="reps" value="{{ $exercise->reps }}">
				</div>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="weight" value="{{ $exercise->weight }}">
				</div>
			</div>
		@endforeach
	</div>
	<div class="modal-footer">
    <button type="submit" class="btn btn-success disabled" role="button"><span class="fa fa-floppy-o"></span> Update</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  </div>
</form>