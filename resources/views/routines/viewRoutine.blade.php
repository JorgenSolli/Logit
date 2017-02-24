<form id="routines" method="POST" action="/dashboard/my_routines/edit/{{ $routine->id }}">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Update your routine</h4>
  </div>
  <div class="modal-body">

    {{ csrf_field() }}
    @php $i = 0; @endphp
    
    {{-- Routine Name --}}
    <div class="form-group">
      <label for="routine_name">Routine Name</label>
      <input type="text" class="form-control" id="routine_name" name="routine_name" value="{{ $routine->routine_name }}">
    </div>
    <hr>
    @foreach($junctions as $junction)
      {{-- Excersice Name --}}
      <div class="form-group">
        <label for="exercise_name">Excersice name</label>
        <input type="text" class="form-control" name="exercises[{{ $i }}][exercise_name]" value="{{ $junction->exercise_name }}">
      </div>

      {{-- Muscle Group --}}
      <div class="form-group">
        <label for="muscle_group">Muscle group</label>
        <select class="form-control" id="muscle_group" name="exercises[{{ $i }}][muscle_group]">
          <option value="{{ $junction->muscle_group }}" selected>{{ $junction->muscle_group }}</option>
          <option value="back">Back</option>
          <option value="arms">Arms</option>
          <option value="legs">Legs</option>
          <option value="chest">Chest</option>
        </select>
      </div>
      <div class="row">
        
        {{-- Weight Goal --}}
        <div class="col-md-4">
          <div class="form-group">
            <label for="goal_weight">Weight goal</label>
            <input type="number" class="form-control" id="goal_weight" name="exercises[{{ $i }}][goal_weight]" value="{{ $junction->goal_weight }}">
          </div>
        </div>

        {{-- Sets Goal --}}
        <div class="col-md-4">
          <label for="goal_sets">Sets goal</label>
          <input type="number" class="form-control" id="goal_sets" name="exercises[{{ $i }}][goal_sets]" value="{{ $junction->goal_sets }}">
        </div>

        {{-- Reps Goal --}}
        <div class="col-md-4">
          <label for="goal_reps">Reps goal</label>
          <input type="number" class="form-control" id="goal_reps" name="exercises[{{ $i }}][goal_reps]" value="{{ $junction->goal_reps }}">
        </div>
      </div>
      <hr>
      @php $i++ @endphp
    @endforeach
    <input type="hidden" id="exerciseNr" value="{{ $i }}">
    <div id="formData"></div>
    <button id="addMore" type="button" class="btn btn-info">Add another exercise!</button>
  </div>
  <div class="modal-footer">
    <button type="submit" class="btn btn-success" role="button"><span class="fa fa-floppy-o"></span> Update</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  </div>
</form>