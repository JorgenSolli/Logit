<form id="routines" method="POST" action="/dashboard/my_routines/edit/{{ $routine->id }}">

  <div class="card">
    <div class="card-content clearfix">
      <h4 class="modal-title pull-left">Update your routine</h4>
      <div class="pull-right">
        <input type="hidden" value="{{ $routine->active }}" name="status" id="status">
        <input type="hidden" value="{{ $routine->id }}" name="routineId" id="routineId"> 
        @if ($routine->active == 1)
          <button type="button" id="changeStatus" class="btn btn-default" role="button"><span class="fa fa-lock"></span> Set inactive</button>
        @else
          <button type="button" id="changeStatus" class="btn btn-default" role="button"><span class="fa fa-unlock"></span> Set active</button>
        @endif
        <button type="submit" id="addRoutine" class="btn btn-success" role="button"><span class="fa fa-floppy-o"></span> Update</button>
        <button type="button" class="btn btn-danger routine-back">Back</button>
      </div>
    </div>
  </div>
  
  {{ csrf_field() }}
  @php $i = 0; @endphp
  {{-- Routine Name --}}
  <div class="card m-t-10 m-b-10">
    <div class="card-content">
      <div class="form-group">
        <label for="routine_name">Routine Name</label>
        <input type="text" class="form-control" id="routine_name" name="routine_name" value="{{ $routine->routine_name }}">
      </div>
    </div>
  </div>

  <div id="sortable">
    @foreach($junctions as $junction)
      <div class="thisExercise">
        <div class="card m-b-10">
          <div class="card-content">
          {{-- Excersice Name --}}
            <a class="deleteExercise btn btn-sm btn-danger pull-right"><span class="fa fa-sm fa-trash"></span></a>
            <div class="sort-icon handle">
              Drag me to sort
              <span class="fa fa-arrows-v"></span>
            </div>
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
                <option value="biceps">Biceps</option>
                <option value="triceps">Triceps</option>
                <option value="abs">Abs</option>
                <option value="shoulders">Shoulders</option>
                <option value="legs">Legs</option>
                <option value="chest">Chest</option>
              </select>
            </div>
            <div class="row">
              
              {{-- Weight Goal --}}
              <div class="col-md-4">
                <div class="form-group">
                  <label for="goal_weight">Weight goal</label>
                  <input type="number" step="any" class="form-control" id="goal_weight" name="exercises[{{ $i }}][goal_weight]" value="{{ $junction->goal_weight }}">
                </div>
              </div>

              {{-- Sets Goal --}}
              <div class="col-md-4">
                <div class="form-group">
                  <label for="goal_sets">Sets goal</label>
                  <input type="number" class="form-control" id="goal_sets" name="exercises[{{ $i }}][goal_sets]" value="{{ $junction->goal_sets }}">
                </div>
              </div>

              {{-- Reps Goal --}}
              <div class="col-md-4">
                <div class="form-group">
                  <label for="goal_reps">Reps goal</label>
                  <input type="number" class="form-control" id="goal_reps" name="exercises[{{ $i }}][goal_reps]" value="{{ $junction->goal_reps }}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="checkbox">
                  <label>
                    @if ($junction->is_warmup == 1)
                      <input type="checkbox" name="exercises[{{ $i }}][is_warmup]" checked="">
                    @else
                      <input type="checkbox" name="exercises[{{ $i }}][is_warmup]">
                    @endif
                    Warmup set
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @php $i++ @endphp
    @endforeach
  </div>
  <input type="hidden" id="exerciseNr" value="{{ $i }}">
  <div id="formData"></div>

  <div class="card">
    <div class="card-content clearfix">

      <button id="addMore" type="button" class="btn btn-primary">Add another exercise!</button>
      <div class="pull-right">
        <input type="hidden" value="{{ $routine->active }}" name="status" id="status">
        <input type="hidden" value="{{ $routine->id }}" name="routineId" id="routineId"> 
        @if ($routine->active == 1)
          <button type="button" id="changeStatus" class="btn btn-default" role="button"><span class="fa fa-lock"></span> Set inactive</button>
        @else
          <button type="button" id="changeStatus" class="btn btn-default" role="button"><span class="fa fa-unlock"></span> Set active</button>
        @endif
        <button type="submit" id="addRoutine" class="btn btn-success" role="button"><span class="fa fa-floppy-o"></span> Update</button>
        <button type="button" class="btn btn-danger routine-back">Back</button>
      </div>
    </div>
  </div>
</form>