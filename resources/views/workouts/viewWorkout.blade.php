<div id="viewWorkout">

	<div class="card">
	    <div class="card-content clearfix">
			<h4 class="modal-title pull-left">View/Edit your workout</h4>
			<div class="pull-right">
				<a href="workout/recap/{{ $workoutId }}" type="button" class="btn btn-primary">View Recap</a>
				<button type="button" class="btn btn-danger workout-back">Back</button>
			</div>
	    </div>
	</div>
	<input id="workout_id" type="hidden" value="{{ $workoutId }}">

	@for ($i = 0; $i < count($workout); $i++)
	    @if ($i == 0 || $workout[$i]['exercise_name'] != $workout[$i - 1]['exercise_name'])
	    	<div class="card m-t-10 m-b-10">
		      	<div class="card-content">
			        <h4>{{ $workout[$i]['exercise_name'] }}</h4>
			     	<div class="row">
				     	<div class="col-sm-1 col-xs-3">
				     		<b>Set nr</b>
				     	</div>
				     	<div class="col-sm-5 col-xs-3">
				     		<b>Reps</b>
				     	</div>
				     	<div class="col-sm-5 col-xs-3">
				     		<b>Weight</b>
				     	</div>
				     	<div class="col-sm-1 col-xs-3">
				     		<span class="pull-right">
				     			<b>Save</b>
				     		</span>
			     		</div>
	    			</div>
	  	@endif
		<div class="row">
			<input type="hidden" name="workout_junction_id" value="{{ $workout[$i]['id'] }}">
	     	<div class="col-sm-1 col-xs-3 lh-48 set_nr">
	     		{{ $workout[$i]['set_nr'] }}
     		</div>

	     	<div class="col-sm-5 col-xs-3">
	     		<div class="form-group m-t-0">
	     			<input class="form-control reps" type="number" name="reps" value="{{ $workout[$i]['reps'] }}">
	     		</div>
	     	</div>
	     	
	     	<div class="col-sm-5 col-xs-3">
	     		<div class="form-group m-t-0">
	     			<input class="form-control weight" type="number" step="any" name="weight" value="{{ $workout[$i]['weight'] }}">
     			</div>
	     	</div>

	     	<div class="col-sm-1 col-xs-3">
     			<a class="pull-right pointer updateWorkoutRow"><i class="material-icons material-icons-lg">save</i></a>
	     	</div>
		</div>

  		@if ($i + 1 == count($workout) || ($i + 1 < count($workout) && $workout[$i]['exercise_name'] != $workout[$i + 1]['exercise_name']))
  				</div> <!-- .card-content -->
		    </div> <!-- .card -->
	    @else
			<hr class="m-t-10 m-b-10">
		@endif
	@endfor

	<div class="card">
	    <div class="card-content clearfix">
    		<a href="workout/recap/{{ $workoutId }}" type="button" class="btn btn-primary">View Recap</a>
	  		<button type="button" class="btn btn-danger workout-back pull-right">Back</button>
		</div>
	</div>
</div>