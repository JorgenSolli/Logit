@unless (session($exercise->exercise_name))
	<h1>You have already finished this exercise</h1>
@else
	<form action="/api/exercise/{{ $routineId[0]['routine_id'] }}" method="POST">
		{{ csrf_field() }}
	  {{ method_field('PUT') }}
		
		<h1>{{ $exercise->exercise_name }}</h1>
		<input type="hidden" name="exercise_name" value="{{ $exercise->exercise_name }}">
		
		@for ($i = 1; $i <= $nrOfSets; $i++)
			<h3>Set nr {{ $i }}</h3>
			<input type="hidden" name="exercise[{{ $i }}][set]" value="{{ $i }}">
			<div class="form-group">
		    <label for="weight">Weight</label>
		    <input type="number" class="form-control" name="exercise[{{ $i }}][weight]" placeholder="Your goal is {{ $exercise->goal_weight }}">
		  </div>

		  <div class="form-group">
		    <label for="reps">Reps</label>
		    <input type="number" class="form-control" name="exercise[{{ $i }}][reps]" placeholder="Your goal is {{ $exercise->goal_reps }}">
		  </div>

		  <hr>
		@endfor

		<div class="row">
			<div class="col-xs-4">
				<a style="width:100%" href="/dashboard/start" class="btn btn-danger">Cancel</a>
			</div>
			<div class="col-xs-8">
				<button style="width:100%" type="submit" class="btn btn-success"><span class="fa fa-floppy-o"></span> Save</button>
			</div>
		</div>
	</form>
@endunless