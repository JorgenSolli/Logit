@if ($type === 'regular')
	@unless (session($exercise->exercise_name))
		<h1>You have already finished this exercise</h1>
	@else
		<form action="/api/exercise/{{ $routineId[0]['routine_id'] }}" method="POST">
			{{ csrf_field() }}
		  	{{ method_field('PUT') }}
			<input type="hidden" name="routine_junction_id" value="{{ $exercise->id }}">
			<input type="hidden" name="type" value="regular">
			<input type="hidden" name="exercise_name" value="{{ $exercise->exercise_name }}">

			<h1>{{ $exercise->exercise_name }}</h1>

			@if ($note && $note->note)
				<div class="alert {{ $note->label }} alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <strong>Last time you did this exercise you left a note!<br><hr style="margin-top: 5px; margin-bottom: 5px"></strong> {{ $note->note }}
				</div>
			@endif

			@for ($i = 1; $i <= $nrOfSets; $i++)
				<div class="card m-b-10 m-t-10">
					<div class="card-header">
						<h4 class="card-title">Set nr {{ $i }}</h4>

					</div>
					<div class="card-content">
						<input type="hidden" name="exercise[{{ $i }}][set]" value="{{ $i }}">
						@if ($exercise->is_warmup === 1)
							<input type="hidden" name="exercise[{{ $i }}][is_warmup]" value="1">
						@else
							<input type="hidden" name="exercise[{{ $i }}][is_warmup]" value="0">
						@endif
						<div class="form-group m-t-0">
						    <label for="weight">Weight</label>

						    <label class="hidden control-label" for="weight"> | Hey don't give up! Finish all sets. You can do it!</label>
						    <input type="number" step="any" class="required form-control" name="exercise[{{ $i }}][weight]" 
						    	placeholder="Your goal is {{ $exercise->goal_weight }}. @unless(empty($prevExercise[$i - 1])) Last time you lifted {{ $prevExercise[$i - 1]['weight'] }} @endunless">
				  		</div>

					  	<div class="form-group">
							<label for="reps">Reps</label>
							<label class="control-label hidden" for="weight"> | Hey don't give up! At least do ONE rep!</label>
						    <input type="number" class="required form-control" name="exercise[{{ $i }}][reps]" 
						    	placeholder="Your goal is {{ $exercise->goal_reps }}. @unless(empty($prevExercise[$i - 1])) Last time you did {{ $prevExercise[$i - 1]['reps'] }} @endunless">
					  	</div>
				  	</div>
			  	</div>
			@endfor
			<div class="card">
				<div class="card-content">
					<div class="form-group">
						<textarea name="note" class="form-control" placeholder="Something worth noting? You can also label the note below (if you like)"></textarea>
					</div>
					<div class="form-group">
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
					<hr>
					<div class="row">
						<div class="col-xs-4">
							<a id="cancelExercise" style="width:100%" class="btn btn-danger">Cancel</a>
						</div>
						<div class="col-xs-8">
							<button style="width:100%" type="submit" id="saveWorkout" class="btn btn-success"><span class="fa fa-floppy-o"></span> Save</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	@endunless
@elseif ($exercise[0])
	@unless (session($exercise[0]->superset_name))
		<h1>You have already finished this exercise</h1>
	@else
		<form action="/api/exercise/{{ $routineId[0]['routine_id'] }}" method="POST">
			{{ csrf_field() }}
		  	{{ method_field('PUT') }}

			<input type="hidden" name="type" value="superset">
			<input type="hidden" name="routine_junction_id" value="{{ $exercise[0]->id }}">
			<input type="hidden" name="superset_name" value="{{ $exercise[0]->superset_name }}">
			<h1>Superset: {{ $exercise[0]->superset_name }}</h1> 

			@if ($note && $note->note)
				<div class="alert {{ $note->label }} alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <strong>Last time you did this exercise you left a note!<br><hr style="margin-top: 5px; margin-bottom: 5px"></strong> {{ $note->note }}
				</div>
			@endif

			@for ($i = 1; $i <= $nrOfSets; $i++)
				<div class="thisExercise">
					<div class="card card-transparent m-t-10 m-b-10">
						<div class="card-content">
							<h4>Set nr {{ $i }}</h4>
							@for ($j = 0; $j <= $supersetsCount - 1; $j++)
								<input type="hidden" name="superset[{{ $j }}][{{ $i }}][exercise_name]" value="{{ $exercise[$j]->exercise_name }}">
								<div class="card m-b-10 m-t-10">
									<div class="card-header">
										<h4 class="card-title">{{ $exercise[$j]->exercise_name }}</h4>
									</div>
									<div class="card-content">
										<input type="hidden" name="superset[{{ $j }}][{{ $i }}][set]" value="{{ $i }}">
										<div class="form-group m-t-0">
										    <label for="weight">Weight</label>

										    <label class="hidden control-label" for="weight"> | Hey don't give up! Finish all sets. You can do it!</label>
										    <input type="number" step="any" class="required form-control" name="superset[{{ $j }}][{{ $i }}][weight]" 
										    	placeholder="Your goal is {{ $exercise[$j]->goal_weight }}. @unless(empty($prevExercise[$i - 1])) Last time you lifted {{ $prevExercise[$i - 1]['weight'] }} @endunless">
								  		</div>

									  	<div class="form-group">
											<label for="reps">Reps</label>
											<label class="control-label hidden" for="weight"> | Hey don't give up! At least do ONE rep!</label>
										    <input type="number" class="required form-control" name="superset[{{ $j }}][{{ $i }}][reps]" 
										    	placeholder="Your goal is {{ $exercise[$j]->goal_reps }}. @unless(empty($prevExercise[$i - 1])) Last time you did {{ $prevExercise[$i - 1]['reps'] }} @endunless">
									  	</div>
								  	</div>
							  	</div>
							@endfor
						</div>
					</div>
				</div>
			@endfor
			<div class="card">
				<div class="card-content">
					<div class="form-group">
						<textarea name="note" class="form-control" placeholder="Something worth noting? You can also label the note below (if you like)"></textarea>
					</div>
					<div class="form-group">
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
					<hr>
					<div class="row">
						<div class="col-xs-4">
							<a id="cancelExercise" style="width:100%" class="btn btn-danger">Cancel</a>
						</div>
						<div class="col-xs-8">
							<button style="width:100%" type="submit" id="saveWorkout" class="btn btn-success"><span class="fa fa-floppy-o"></span> Save</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	@endunless
@endif