@if ($type === 'regular')
	@unless (session($exercise->exercise_name))
		<h1>You have already finished this exercise</h1>
		<a id="cancelExercise" style="width:100%" class="btn btn-white">Go back</a>
	@else
		<form action="{{ url("/exercise/{$routineId}/{$exercise_id}") }}" method="POST">
			{{ csrf_field() }}
		  	{{ method_field('PUT') }}
			<input type="hidden" name="routine_junction_id" value="{{ $exercise->id }}">
			<input type="hidden" name="type" value="regular">
			<input type="hidden" name="exercise_name" value="{{ $exercise->exercise_name }}">
			<input type="hidden" name="media" value="{{ $exercise->media }}">

			<h2 class="h2" id="exercise_name">{{ $exercise->exercise_name }} </h2>

			@if ($note && $note->note)
				<div class="alert {{ $note->label }} alert-dismissible fade show" role="alert">
                    <span data-notify="message">
                        <b class="alert-header m-b-10">Your note form last session:</b><br/> {{ $note->note }}
                    </span>

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    	<i class="material-icons">close</i>
                    </button>
                </div>
			@endif

			@for ($i = 1; $i <= $nrOfSets; $i++)
				<div class="exercise-card card m-b-10 m-t-10">
					<div class="card-header">
						<h4 class="card-title">Set nr {{ $i }}</h4>
					</div>
					<div class="card-body pt-0">
						<input type="hidden" name="exercise[{{ $i }}][set]" value="{{ $i }}">
						@if ($exercise->is_warmup === 1)
							<input type="hidden" name="exercise[{{ $i }}][is_warmup]" value="1">
						@else
							<input type="hidden" name="exercise[{{ $i }}][is_warmup]" value="0">
						@endif
						<input type="hidden" name="exercise-tag" value="exercise[{{ $i }}]">

						<div class="form-group m-t-0">
							<label for="weight" class="mb-0">Weight Type</label>
							<select id="weight_type" name="exercise[{{ $i }}][weight_type]" class="selectpicker mt-0"
									data-style="select-with-transition" title="Choose weight type" data-size="8">
								<option selected value="raw">Raw Weight</option>
								<option value="assisted">Assisted Weight</option>
								<option value="band">Resistance Band</option>
							</select>
						</div>

					    @php $placeholder = "";@endphp
					    @unless(empty($prevExercise[$i - 1]))
				    		@if ($prevExercise[$i - 1]['weight_type'] === "band")
				    			@php $placeholder = "Last time you used the " . $prevExercise[$i - 1]['band_type'] . " "  .$prevExercise[$i - 1]['weight_type']; @endphp
			    			@else
			    				@php $placeholder = "Last time you lifted " . $prevExercise[$i - 1]['weight']; @endphp
			    			@endif
		    			@endunless
		    			
		    			<input type="hidden" name="exercise-goal" value="{{ $exercise->goal_weight }}">
						<input type="hidden" name="exercise-pre" value="{{ $placeholder }}">
						
						<div class="form-group weight_type">
						    <label class="bmd-label-floating" for="weight">Weight - Your goal is {{ $exercise->goal_weight }}. {{ $placeholder }}</label>
						    <input type="number" step="any" class="required form-control" name="exercise[{{ $i }}][weight]">
				  		</div>

					  	<div class="form-group">
							<label class="bmd-label-floating" for="reps">Reps - Your goal is {{ $exercise->goal_reps }}. @unless(empty($prevExercise[$i - 1])) Last time you did {{ $prevExercise[$i - 1]['reps'] }} @endunless</label>
						    <input type="number" class="required form-control" name="exercise[{{ $i }}][reps]">
					  	</div>
				  	</div>
			  	</div>
			@endfor
			<div class="card">
				<div class="card-body">
					<div class="form-group">
						<textarea name="note" class="form-control" placeholder="Something worth noting? You can also label the note below (if you like)"></textarea>
					</div>
					<div class="form-check form-check-radio form-check-inline">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" name="labelType" value="alert-info" checked=""> Info
							<span class="circle">
								<span class="check"></span>
							</span>
						</label>
                    </div>
                    <div class="form-check form-check-radio form-check-inline">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" name="labelType" value="alert-success" checked=""> Success
							<span class="circle">
								<span class="check"></span>
							</span>
						</label>
                    </div>
                    <div class="form-check form-check-radio form-check-inline">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" name="labelType" value="alert-warning" checked=""> Warning
							<span class="circle">
								<span class="check"></span>
							</span>
						</label>
                    </div>
					<hr>
					<div class="row">
						<div class="col-5">
							<button id="cancelExercise" style="width:100%" class="btn btn-danger">Cancel</button>
						</div>
						<div class="col-7">
							<button style="width:100%" type="button" id="saveWorkout" class="btn btn-success"><span class="fal fa-save"></span> Save</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	@endunless
@elseif ($exercise[0])
	@unless (session($exercise[0]->superset_name))
		<h1>You have already finished this exercise</h1>
		<a id="cancelExercise" style="width:100%" class="btn btn-white">Go back</a>
	@else
		<form action="{{ url("/exercise/{$routineId}/{$exercise_id}") }}" method="POST">
			{{ csrf_field() }}
		  	{{ method_field('PUT') }}

			<input type="hidden" name="type" value="superset">
			<input type="hidden" name="routine_junction_id" value="{{ $exercise[0]->id }}">
			<input type="hidden" name="superset_name" value="{{ $exercise[0]->superset_name }}">

			@foreach ($exercise as $media)
				@if ($media->media)
					<input type="hidden" name="media[{{ $loop->index }}]" value='{"media":"{{ $media->media }}","name":"{{ $media->exercise_name }}"}'>
				@endif
			@endforeach

			<h1>Superset: {{ $exercise[0]->superset_name }}</h1>
			<div id="media"></div>

			@if ($note && $note->note)
				<div class="alert {{ $note->label }} alert-dismissible fade show" role="alert">
                    <span data-notify="message">
                        <b class="alert-header m-b-10">Your note form last session:</b><br/> {{ $note->note }}
                    </span>

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    	<i class="material-icons">close</i>
                    </button>
                </div>
			@endif

			@php $exerciseNr = 0; @endphp
			
			@for ($i = 1; $i <= $nrOfSets; $i++)
				<div class="thisExercise">
					<div class="exercise-card card m-b-10 m-t-10 card-transparent">
						<div class="card-body">
							<h4 class="m-b-0">Set nr {{ $i }}</h4>
							@for ($j = 0; $j <= $supersetsCount - 1; $j++)
								<input type="hidden" name="superset[{{ $j }}][{{ $i }}][exercise_name]" value="{{ $exercise[$j]->exercise_name }}">
								<div class="card m-b-10 m-t-10">
									<div class="card-header">
										<h4 class="card-title">{{ $exercise[$j]->exercise_name }}</h4>
									</div>
									<div class="card-body">
										<input type="hidden" name="superset[{{ $j }}][{{ $i }}][set]" value="{{ $i }}">
										<input type="hidden" name="exercise-tag" value="superset[{{ $j }}][{{ $i }}]">

										<div class="form-group m-t-0">
											<label class="bmd-label-floating mb-0" for="weight">Weight Type</label>
											<select id="weight_type" name="superset[{{ $j }}][{{ $i }}][weight_type]" class="selectpicker"
													data-style="select-with-transition" title="Choose weight type" data-size="8">
												<option selected value="raw">Raw Weight</option>
												<option value="assisted">Assisted Weight</option>
												<option value="band">Resistance Band</option>
											</select>
										</div>

										@php $placeholder = "";@endphp
									    @unless(empty($prevExercise[$j]))
								    		@if ($prevExercise[$j][$exerciseNr]['weight_type'] === "band")
								    			@php $placeholder = "Last time you used the " . $prevExercise[$j][$exerciseNr]['band_type'] . " "  .$prevExercise[$j][$exerciseNr]['weight_type']; @endphp
							    			@else
							    				@php $placeholder = "Last time you lifted " . $prevExercise[$j][$exerciseNr]['weight']; @endphp
							    			@endif
						    			@endunless
										<input type="hidden" name="exercise-goal" value="{{ $exercise[$j]->goal_weight }}">
										<input type="hidden" name="exercise-pre" value="{{ $placeholder }}">
										<div class="form-group weight_type">
										    <label class="bmd-label-floating" for="weight">Weight - Your goal is {{ $exercise[$j]->goal_weight }}. {{ $placeholder }}</label>
										    <input type="number" step="any" class="required form-control" name="superset[{{ $j }}][{{ $i }}][weight]"
											    	placeholder="">
									  	</div>

									  	<div class="form-group">
											<label class="bmd-label-floating" for="weight">Reps - Your goal is {{ $exercise[$j]->goal_reps }}. @unless(empty($prevExercise[$j][$exerciseNr])) Last time you did {{ $prevExercise[$j][$exerciseNr]['reps'] }} @endunless</label>
										    <input type="number" class="required form-control" name="superset[{{ $j }}][{{ $i }}][reps]"
										    	placeholder="">
									  	</div>
								  	</div>
							  	</div>
							@endfor
						</div>
					</div>
				</div>
			  	@php $exerciseNr ++; @endphp
			@endfor
			<div class="card">
				<div class="card-body">
					<div class="form-group">
						<textarea name="note" class="form-control" placeholder="Something worth noting? You can also label the note below (if you like)"></textarea>
					</div>
					<div class="form-check form-check-radio form-check-inline">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" name="labelType" value="alert-info" checked=""> Info
							<span class="circle">
								<span class="check"></span>
							</span>
						</label>
                    </div>
                    <div class="form-check form-check-radio form-check-inline">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" name="labelType" value="alert-success" checked=""> Success
							<span class="circle">
								<span class="check"></span>
							</span>
						</label>
                    </div>
                    <div class="form-check form-check-radio form-check-inline">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" name="labelType" value="alert-warning" checked=""> Warning
							<span class="circle">
								<span class="check"></span>
							</span>
						</label>
                    </div>
					<hr>
					<div class="row">
						<div class="col-4">
							<button id="cancelExercise" style="width:100%" class="btn btn-danger">Cancel</button>
						</div>
						<div class="col-8">
							<button style="width:100%" type="button" id="saveWorkout" class="btn btn-success"><span class="fal fa-save"></span> Save</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	@endunless
@endif