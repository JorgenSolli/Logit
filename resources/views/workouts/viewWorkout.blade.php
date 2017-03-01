<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Update your workout</h4>
</div>
<div class="modal-body">
	@for ($i = 0; $i < count($workout); $i++)
    @if ($i == 0 || $workout[$i]['exercise_name'] != $workout[$i - 1]['exercise_name'])
			<h4>{{ $workout[$i]['exercise_name'] }}</h4>
			<table class="table">
    		<thead>
    			<tr>
	    			<th>Set nr</th>
		    		<th>Reps</th>
		    		<th>Weight</th>
		    		<th>Delete</th>
		    		<th>Save</th>
	    		</tr>
    		</thead>
  			<tbody>
  	@endif

		<tr>
			<td>{{ $workout[$i]['set_nr'] }}</td>
			<td>
				<input class="form-control" type="text" name="reps" value="{{ $workout[$i]['reps'] }}">
			</td>
	    <td>
	    	<input class="form-control" type="text" name="weight" value="{{ $workout[$i]['weight'] }}">
	    </td>
	    <td class="text-center">
	    	<a href="/api/delete/workout_set/{{ $workout[$i]['id'] }}"><span class="fa fa-trash fa-lg"></span>
	    	</a>
    	</td>
	    <td class="text-center">
	    	<a href="/api/update/workout_set/{{ $workout[$i]['id'] }}" class="pointer"><span class="fa fa-floppy-o fa-lg"></span></a>
    	</td>
  	</tr>

  	@if ($i + 1 == count($workout) || ($i + 1 < count($workout) && $workout[$i]['exercise_name'] != $workout[$i + 1]['exercise_name']))
  			</tbody>
			</table>
		@endif
	@endfor
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>