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
							    <label class="control-label" for="weight">Weight ({{ $unit }})</label>
								<input id="weight" type="number" step="any" class="form-control" name="weight" value="{{ $measurements->weight }}" />
							</div>
						</div>

						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="body_fat">Body Fat (%)</label>
								<input id="body_fat" type="number" step="any" class="form-control" name="body_fat" value="{{ $measurements->body_fat }}" />
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="neck">Neck ({{ $unit }})</label>
								<input id="neck" type="number" step="any" class="form-control" name="neck" value="{{ $measurements->neck }}" />
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="shoulders">Shoulders ({{ $unit }})</label>
								<input id="shoulders" type="number" step="any" class="form-control" name="shoulders" value="{{ $measurements->shoulders }}" />
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="arms">Arms ({{ $unit }})</label>
								<input id="arms" type="number" step="any" class="form-control" name="arms" value="{{ $measurements->arms }}" />
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="chest">Chest ({{ $unit }})</label>
								<input id="chest" type="number" step="any" class="form-control" name="chest" value="{{ $measurements->chest }}" />
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="waist">Waist ({{ $unit }})</label>
								<input id="waist" type="number" step="any" class="form-control" name="waist" value="{{ $measurements->waist }}" />
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="forearms">Forearms ({{ $unit }})</label>
								<input id="forearms" type="number" step="any" class="form-control" name="forearms" value="{{ $measurements->forearms }}" />
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="calves">Calves ({{ $unit }})</label>
								<input id="calves" type="number" step="any" class="form-control" name="calves" value="{{ $measurements->calves }}" />
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="thighs">Thighs ({{ $unit }})</label>
								<input id="thighs" type="number" step="any" class="form-control" name="thighs" value="{{ $measurements->thighs }}" />
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="hips">Hips ({{ $unit }})</label>
								<input id="hips" type="number" step="any" class="form-control" name="hips" value="{{ $measurements->hips }}" />
							</div>
						</div>
					</div>

					<input type="submit" id="save-measurements" class="btn-fullwidth btn btn-success" value="Save Measurements">
				</form>
			</div>
		</div>

		<div class="card">
			<div class="card-header card-header-icon" data-background-color="green">
				<i class="material-icons">pregnant_woman</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Measurement logs</h4>
				<div class="toolbar"></div>	
			</div>
		</div>
	</div>

@endsection

@section('script')
@endsection