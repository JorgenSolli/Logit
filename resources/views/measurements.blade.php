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
								<input id="weight" type="number" step="any" class="form-control" name="weight" value="
									@if($measurements && $measurements->weight)
										{{ $measurements->weight }} 
									@endif "/>
							</div>
						</div>

						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="body_fat">Body Fat (%)</label>
								<input id="body_fat" type="number" step="any" class="form-control" name="body_fat" value="
									@if($measurements && $measurements->body_fat)
										{{ $measurements->body_fat }} 
									@endif "/>
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="neck">Neck ({{ $unit }})</label>
								<input id="neck" type="number" step="any" class="form-control" name="neck" value="
									@if($measurements && $measurements->neck)
										{{ $measurements->neck }} 
									@endif "/>
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="shoulders">Shoulders ({{ $unit }})</label>
								<input id="shoulders" type="number" step="any" class="form-control" name="shoulders" value="
									@if($measurements && $measurements->shoulders)
										{{ $measurements->shoulders }} 
									@endif "/>
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="arms">Arms ({{ $unit }})</label>
								<input id="arms" type="number" step="any" class="form-control" name="arms" value="
									@if($measurements && $measurements->arms)
										{{ $measurements->arms }} 
									@endif "/>
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="chest">Chest ({{ $unit }})</label>
								<input id="chest" type="number" step="any" class="form-control" name="chest" value="
									@if($measurements && $measurements->chest)
										{{ $measurements->chest }} 
									@endif "/>
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="waist">Waist ({{ $unit }})</label>
								<input id="waist" type="number" step="any" class="form-control" name="waist" value="
									@if($measurements && $measurements->waist)
										{{ $measurements->waist }} 
									@endif "/>
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="forearms">Forearms ({{ $unit }})</label>
								<input id="forearms" type="number" step="any" class="form-control" name="forearms" value="
									@if($measurements && $measurements->forearms)
										{{ $measurements->forearms }} 
									@endif "/>
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="calves">Calves ({{ $unit }})</label>
								<input id="calves" type="number" step="any" class="form-control" name="calves" value="
									@if($measurements && $measurements->calves)
										{{ $measurements->calves }} 
									@endif "/>
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="thighs">Thighs ({{ $unit }})</label>
								<input id="thighs" type="number" step="any" class="form-control" name="thighs" value="
									@if($measurements && $measurements->thighs)
										{{ $measurements->thighs }} 
									@endif "/>
							</div>
						</div>
						
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group label-floating">
								<label class="control-label" for="hips">Hips ({{ $unit }})</label>
								<input id="hips" type="number" step="any" class="form-control" name="hips" value="
									@if($measurements && $measurements->hips)
										{{ $measurements->hips }} 
									@endif "/>
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