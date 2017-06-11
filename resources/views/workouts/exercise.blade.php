@unless (session($exercise->exercise_name))
	<h1>You have already finished this exercise</h1>
@else
	<form action="/api/exercise/{{ $routineId[0]['routine_id'] }}" method="POST">
		{{ csrf_field() }}
	  {{ method_field('PUT') }}
		<input type="hidden" name="exercise_name" value="{{ $exercise->exercise_name }}">
		<input type="hidden" name="routine_junction_id" value="{{ $exercise->id }}">
		<h1>{{ $exercise->exercise_name }}</h1>

		@if ($note && $note->note)
			<div class="alert {{ $note->label }} alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Last time you did this exercise you left a note!<br><hr style="margin-top: 5px; margin-bottom: 5px"></strong> {{ $note->note }}
			</div>
		@endif

		@for ($i = 1; $i <= $nrOfSets; $i++)
			<h3>Set nr {{ $i }}</h3>
			<input type="hidden" name="exercise[{{ $i }}][set]" value="{{ $i }}">
			<div class="form-group">
			    <label for="weight">Weight</label>

			    <label class="hidden control-label" for="weight"> | Hey don't give up! Finish all sets. You can do it!</label>
			    <input type="number" class="required form-control" name="exercise[{{ $i }}][weight]" placeholder="Your goal is {{ $exercise->goal_weight }}. 
			    @unless(empty($prevExercise[$i - 1])) Last time you lifted {{ $prevExercise[$i - 1]['weight'] }} @endunless">
	  		</div>

		  	<div class="form-group">
				<label for="reps">Reps</label>
				<label class="control-label hidden" for="weight"> | Hey don't give up! At least do ONE rep!</label>
			    <input type="number" class="required form-control" name="exercise[{{ $i }}][reps]" placeholder="Your goal is {{ $exercise->goal_reps }}.
			    @unless(empty($prevExercise[$i - 1])) Last time you did '{{ $prevExercise[$i - 1]['reps'] }} @endunless">
		  	</div>
		  	<hr>
		@endfor
		<div class="form-group">
			<label for="note">Something worth noting? You can also label the note below (if you like)</label>
			<textarea name="note" class="form-control" placeholder="Remind you of something next time you do this exercise..."></textarea>
			<label class="radio-inline">
			  <input type="radio" name="labelType" value="alert-info"> Info
			</label>
			<label class="radio-inline">
			  <input type="radio" name="labelType" value="alert-success"> Success
			</label>
			<label class="radio-inline">
			  <input type="radio" name="labelType" value="alert-warning"> Warning
			</label>
		</div>

		<div class="row">
			<div class="col-xs-4">
				<a style="width:100%" href="/dashboard/start" class="btn btn-danger">Cancel</a>
			</div>
			<div class="col-xs-8">
				<button style="width:100%" type="submit" id="saveWorkout" class="btn btn-success"><span class="fa fa-floppy-o"></span> Save</button>
			</div>
		</div>
	</form>
@endunless