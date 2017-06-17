<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Update your workout</h4>
</div>
<div class="modal-body">
	@for ($i = 0; $i < count($workout); $i++)
	    @if ($i == 0 || $workout[$i]['exercise_name'] != $workout[$i - 1]['exercise_name'])
	    	<div class="card m-t-10 m-b-10">
		      <div class="card-content">
		        <h4>{{ $workout[$i]['exercise_name'] }}</h4>
		     	<div class="row">
			     	<div class="col-sm-3">
			     		Set nr
			     	</div>
			     	<div class="col-sm-3">
			     		Reps
			     	</div>
			     	<div class="col-sm-3">
			     		Weight
			     	</div>
			     	<div class="col-sm-3">
			     		<span class="pull-right">
			     			Save
			     		</span>
			     	</div>
	    		</div>
	  	@endif
		<div class="row">
	     	<div class="col-sm-3">
	     		{{ $workout[$i]['set_nr'] }}
     		</div>

	     	<div class="col-sm-3">
	     		<div class="form-group">
	     			<input class="form-control" type="number" name="reps" value="{{ $workout[$i]['reps'] }}">
	     		</div>
	     	</div>
	     	
	     	<div class="col-sm-3">
	     		<div class="form-group">
	     			<input class="form-control" type="number" step="any" name="weight" value="{{ $workout[$i]['weight'] }}">
     			</div>
	     	</div>

	     	<div class="col-sm-3">
	     		<span class="pull-right">
	     			<a href="/api/update/workout_set/{{ $workout[$i]['id'] }}" class="pointer"><span class="fa fa-floppy-o fa-lg success-color"></span></a>
	     		</span>
	     	</div>
		</div>
		<hr>

  		@if ($i + 1 == count($workout) || ($i + 1 < count($workout) && $workout[$i]['exercise_name'] != $workout[$i + 1]['exercise_name']))
  				</div> <!-- .card-content -->
		    </div> <!-- .card -->
		@endif
	@endfor
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>