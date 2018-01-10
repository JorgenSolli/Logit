@extends('layouts.app')

@section('content')
	<div id="measurements">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="green">
				<i class="material-icons">pregnant_woman</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">My Measurements</h4>
				<div class="toolbar"></div>
				<form action="/dashboard/measurements/save" method="post">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
							    <label class="control-label" for="weight">Weight ({{ $unit_weight }})</label>
								<input id="weight" type="number" step="any" class="form-control" name="weight" 
									value="@if($lastInput && $lastInput->weight){{ $lastInput->weight }}@endif"/>
							</div>
						</div>

						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="body_fat">Body Fat (%)</label>
								<input id="body_fat" type="number" step="any" class="form-control" name="body_fat" 
									value="@if($lastInput && $lastInput->body_fat){{ $lastInput->body_fat }}@endif"/>
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="neck">Neck ({{ $unit_distance }})</label>
								<input id="neck" type="number" step="any" class="form-control" name="neck" 
									value="@if($lastInput && $lastInput->neck){{ $lastInput->neck }}@endif"/>
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="shoulders">Shoulders ({{ $unit_distance }})</label>
								<input id="shoulders" type="number" step="any" class="form-control" name="shoulders" 
									value="@if($lastInput && $lastInput->shoulders){{ $lastInput->shoulders }}@endif"/>
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="arms">Arms ({{ $unit_distance }})</label>
								<input id="arms" type="number" step="any" class="form-control" name="arms" 
									value="@if($lastInput && $lastInput->arms){{ $lastInput->arms }}@endif"/>
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="chest">Chest ({{ $unit_distance }})</label>
								<input id="chest" type="number" step="any" class="form-control" name="chest" 
									value="@if($lastInput && $lastInput->chest){{ $lastInput->chest }}@endif"/>
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="waist">Waist ({{ $unit_distance }})</label>
								<input id="waist" type="number" step="any" class="form-control" name="waist" 
									value="@if($lastInput && $lastInput->waist){{ $lastInput->waist }}@endif"/>
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="forearms">Forearms ({{ $unit_distance }})</label>
								<input id="forearms" type="number" step="any" class="form-control" name="forearms" 
									value="@if($lastInput && $lastInput->forearms){{ $lastInput->forearms }}@endif"/>
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="calves">Calves ({{ $unit_distance }})</label>
								<input id="calves" type="number" step="any" class="form-control" name="calves" 
									value="@if($lastInput && $lastInput->calves){{ $lastInput->calves }}@endif"/>
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="thighs">Thighs ({{ $unit_distance }})</label>
								<input id="thighs" type="number" step="any" class="form-control" name="thighs" 
									value="@if($lastInput && $lastInput->thighs){{ $lastInput->thighs }}@endif"/>
							</div>
						</div>

						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="hips">Hips ({{ $unit_distance }})</label>
								<input id="hips" type="number" step="any" class="form-control" name="hips"
									value="@if($lastInput && $lastInput->hips){{ $lastInput->hips }}@endif"/>
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group">
								<input type="text" class="form-control datetimepicker" placeholder="Select a date" name="date" 
									value="{{ $dateNow }}" />
							</div>
						</div>
					</div>

					<input type="submit" id="save-measurements" class="btn-fullwidth btn btn-success" value="Save Measurements">
				</form>
			</div>
		</div>

		<div class="card">
			<div class="card-header card-header-icon" data-background-color="green">
				<i class="material-icons">history</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Measurement logs</h4>
				<div class="toolbar"></div>

				@if ($measurements->first())
					<div class="material-datatables">
				        <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
							<thead>
								<tr>
									<th>Date</th>
									<th>Weight</th>
									<th>Body_fat</th>
									<th>Neck</th>
									<th>Shoulders</th>
									<th>Arms</th>
									<th>Chest</th>
									<th>Waist</th>
									<th>Forearms</th>
									<th>Calves</th>
									<th>Thighs</th>
									<th>Hips</th>
									<th class="text-center disabled-sorting">Delete</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($measurements as $measurement)
								<tr id="workout-{{ $measurement->id }}">
									<td>
										<span class="hidden">{{ Carbon\Carbon::parse($measurement->date)->format('Y/m/d H:i') }}</span>
										{{ Carbon\Carbon::parse($measurement->date)->format('d/m/Y H:i') }}
									</td>

									<td>{{ $measurement->weight }}</td>
									<td>{{ $measurement->body_fat }}</td>
									<td>{{ $measurement->neck }}</td>
									<td>{{ $measurement->shoulders }}</td>
									<td>{{ $measurement->arms }}</td>
									<td>{{ $measurement->chest }}</td>
									<td>{{ $measurement->waist }}</td>
									<td>{{ $measurement->forearms }}</td>
									<td>{{ $measurement->calves }}</td>
									<td>{{ $measurement->thighs }}</td>
									<td>{{ $measurement->hips }}</td>

									<td class="text-center">
										<a id="{{ $measurement->id }}" class="pointer deleteMeasurement">
											<span class="fal fa-trash fa-lg danger-color"></span>
										</a>
									</td>
								</tr>
								@endforeach
							</tbody>
				        </table>
			      	</div>
		      	@else
		      		<h3 class="text-center m-b-15">You have no entries yet...</h3>
		      	@endif
			</div>
		</div>

		<div class="card">
			<div class="card-header card-header-icon" data-background-color="green">
				<i class="material-icons">show_chart</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Your progress</h4>
				<div class="toolbar"></div>
				<div style="position: relative; width: 100%; height: 450px">
					<canvas id="measurementProgress"></canvas>
				</div>
			</div>
		</div>
	</div>

@endsection

@section('script')
	<script src="{{ mix('/js/measurements.min.js') }}"></script>
@endsection