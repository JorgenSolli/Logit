<form id="routines" method="POST" action="/dashboard/my_routines/edit/{{ $routine->id }}">

  <div class="card">
    <div class="card-content clearfix">
      <h4 class="modal-title pull-left">Update your routine</h4>
      <div class="pull-right">
        <input type="hidden" value="{{ $routine->active }}" name="status" id="status">
        <input type="hidden" value="{{ $routine->id }}" name="routineId" id="routineId"> 
        @if ($routine->active == 1)
          <button type="button" id="changeStatus" class="btn btn-default" role="button"><i class="fal fa-lock"></i> Set inactive</button>
        @else
          <button type="button" id="changeStatus" class="btn btn-default" role="button"><i class="fal fa-unlock"></i> Set active</button>
        @endif
        <button type="submit" id="addRoutine" class="btn btn-success" role="button"><i class="fal fa-save"></i> Update</button>
        <button type="button" class="btn btn-danger routine-back"><i class="fal fa-angle-left"></i> Back</button>
      </div>
    </div>
  </div>
  
  {{ csrf_field() }}
  {{-- Adds an array that we can push every superset we print out in. This way we can keep track of them. No dupes! --}}
  @php 
    $i = 0; 
    $ssNr = 0;
    $printed_supersets = []; 
  @endphp
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
      {{-- If the the current exercise is a superset and has not been printed before, start printing --}}
      @if ($junction->type == 'superset' && !in_array($junction->superset_name, $printed_supersets))
        <div class="thisExercise">
          <input class="exerciseOrder" type="hidden" name="supersets[{{ $ssNr }}][order_nr]" value="{{ $junction->order_nr }}">
          <div class="card m-t-10 m-b-10" style="background: rgba(255, 255, 255, 0.8)">
            <div class="card-content">
              <div class="sortable-content">
                <div class="clearfix">
                  <div class="btn-sm btn-primary sort-icon handle pull-left">             
                    <span class="fal fa-arrows-v"></span> Drag to sort
                  </div>
                  <a class="deleteExercise btn btn-sm btn-danger pull-right"><span class="fal fa-trash"></span></a>
                </div>
                <div class="form-group label-floating">
                  <label class="control-label">Superset Name</label>
                  <input type="text" class="required form-control exercise_name" 
                         name="supersets[{{ $ssNr }}][superset_name]" value="{{ $junction->superset_name }}">
                </div>
              </div>
              <div class="sortable-content-children">
                {{-- Here we go go through our superset collection and only print the ones with the current name we're accessing --}}
                @foreach ($supersets as $superset)
                  @if ($superset->superset_name == $junction->superset_name)
                    <div class="thisExercise">
                      <div class="card m-t-10 m-b-10">
                        <div class="card-content">
                          <div class="sortable-content">
                            <div class="clearfix">
                              <div class="btn-sm btn-primary sort-icon handle pull-left">             
                                <span class="fal fa-arrows-v"></span> Drag to sort
                              </div>
                              <a class="deleteExercise btn btn-sm btn-danger pull-right"><span class="fal fa-trash"></span></a>
                            </div>

                            <div class="row">
                              {{-- Excersice Name --}}
                              <div class="col-xs-12 col-sm-6">
                                <div class="form-group label-floating">
                                  <label class="control-label" for="exercise_name">Excersice name</label>
                                  <input type="text" class="required form-control exercise_name" id="exercise_name" 
                                         name="supersets[{{ $ssNr }}][{{ $i }}][exercise_name]" value="{{ $superset->exercise_name }}">
                                </div>
                              </div>

                              {{-- Muscle Group --}}
                              <div class="col-xs-12 col-sm-6">
                                <div class="form-group" style="margin-top: 4px;">
                                  <select class="selectpicker" name="supersets[{{ $ssNr }}][{{ $i }}][muscle_group]" 
                                          data-style="select-with-transition" title="Choose a muscle group" data-size="8">
                                    <option value="{{ $superset->muscle_group }}" selected>{{ $superset->muscle_group }}</option>
                                    <option value="back">Back</option>
                                    <option value="biceps">Biceps</option>
                                    <option value="triceps">Triceps</option>
                                    <option value="abs">Abs</option>
                                    <option value="shoulders">Shoulders</option>
                                    <option value="legs">Legs</option>
                                    <option value="chest">Chest</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            
                            <div class="row">
                              {{-- Weight Goal --}}
                              <div class="col-xs-12 col-sm-4">
                                <div class="form-group label-floating">
                                  <label class="control-label" for="goal_weight">Weight goal</label>
                                  <input type="number" step="any" class="required form-control" id="goal_weight" 
                                         name="supersets[{{ $ssNr }}][{{ $i }}][goal_weight]" value="{{ $superset->goal_weight }}">
                                </div>
                              </div>
                              {{-- Sets Goal --}}
                              <div class="col-xs-6 col-sm-4">
                                <div class="form-group label-floating">
                                  <label class="control-label" for="goal_sets">Sets goal</label>
                                  <input type="number" class="required form-control" id="goal_sets" 
                                         name="supersets[{{ $ssNr }}][{{ $i }}][goal_sets]" value="{{ $superset->goal_sets }}">
                                </div>
                              </div>
                              {{-- Reps Goal --}}
                              <div class="col-xs-6 col-sm-4">
                                <div class="form-group label-floating">
                                  <label class="control-label" for="goal_reps">Reps goal</label>
                                  <input type="number" class="required form-control" id="goal_reps" 
                                         name="supersets[{{ $ssNr }}][{{ $i }}][goal_reps]" value="{{ $superset->goal_reps }}">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-4">
                                <div class="checkbox">
                                  <label>
                                    <input type="checkbox" name="supersets[{{ $ssNr }}][{{ $i }}][is_warmup]">
                                      Warmup set
                                  </label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    @php $i++; @endphp
                  @endif
                @endforeach
              </div>
              <input type="hidden" class="thisSupersetNr" value=" {{ $ssNr }}">
              <button id="addMore-superset" type="button" class="btn btn-primary">Add another exercise</button>
            </div>
          </div>
        </div>
        {{-- We push the current superset to out array so we can keep track. --}}
        @php 
          $ssNr++;  
          array_push($printed_supersets, $junction->superset_name); 
        @endphp
      {{-- 
        Since after the first iteration the next superset-exercies will be run through and obsiously fail the first check, we need
        to make sure it doesnt get printed here as a regular exercise!
       --}}
      @elseif (!in_array($junction->superset_name, $printed_supersets))
        <div class="thisExercise">
          <input class="exerciseOrder" type="hidden" name="exercises[{{ $i }}][order_nr]" value="">
          <div class="card m-b-10">
            <div class="card-content">
            {{-- Excersice Name --}}
              <div class="clearfix">
                <div class="btn-sm btn-primary sort-icon handle pull-left">             
                  <span class="fal fa-arrows-v"></span> Drag to sort
                </div>
                <a class="deleteExercise btn btn-sm btn-danger pull-right"><span class="fal fa-trash"></span></a>
              </div>
              <div class="row">
                <div class="col-xs-12 col-sm-6">
                  <div class="form-group label-floating m-t-10">
                    <label class="control-label" for="exercise_name">Excersice name</label>
                    <input type="text" class="form-control" name="exercises[{{ $i }}][exercise_name]" value="{{ $junction->exercise_name }}">
                  </div>
                </div>

                <div class="col-xs-12 col-sm-6">
                  {{-- Muscle Group --}}
                  <div class="form-group label-floating" style="margin-top: 4px;">
                    <select id="muscle_group" class="selectpicker" name="exercises[{{ $i }}][muscle_group]"  
                            data-style="select-with-transition" title="Choose a muscle group" data-size="8">
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
                </div>
              </div>
              
              <div class="row">
                
                {{-- Weight Goal --}}
                <div class="col-xs-12 col-sm-4">
                  <div class="form-group label-floating m-t-10">
                    <label class="control-label" for="goal_weight">Weight goal</label>
                    <input type="number" step="any" class="form-control" id="goal_weight" name="exercises[{{ $i }}][goal_weight]" value="{{ $junction->goal_weight }}">
                  </div>
                </div>

                {{-- Sets Goal --}}
                <div class="col-xs-6 col-sm-4">
                  <div class="form-group label-floating m-t-10">
                    <label class="control-label" for="goal_sets">Sets goal</label>
                    <input type="number" class="form-control" id="goal_sets" name="exercises[{{ $i }}][goal_sets]" value="{{ $junction->goal_sets }}">
                  </div>
                </div>

                {{-- Reps Goal --}}
                <div class="col-xs-6 col-sm-4">
                  <div class="form-group label-floating m-t-10">
                    <label class="control-label" for="goal_reps">Reps goal</label>
                    <input type="number" class="form-control" id="goal_reps" name="exercises[{{ $i }}][goal_reps]" value="{{ $junction->goal_reps }}">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
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
      @endif

      @php 
        $i++; 
      @endphp
    @endforeach
  </div>
  <input type="hidden" id="exerciseNr" value="{{ $i }}">
  <div id="formData"></div>

  <div class="card">
    <div class="card-content clearfix">
      <div class="row">
        <div class="col-md-7">
          <button id="addMore" type="button" class="btn btn-primary btn-fullwidth">Add another exercise!</button>
          <button id="addSuperset" type="button" class="btn btn-primary btn-fullwidth">Add superset group</button>
        </div>
        <div class="col-md-5">
          <input type="hidden" value="{{ $routine->active }}" name="status" id="status">
          <input type="hidden" value="{{ $routine->id }}" name="routineId" id="routineId"> 
          @if ($routine->active == 1)
            <button type="button" id="changeStatus" class="btn btn-default btn-fullwidth" role="button"><i class="fal fa-lock"></i> Set inactive</button>
          @else
            <button type="button" id="changeStatus" class="btn btn-default btn-fullwidth" role="button"><i class="fal fa-unlock"></i> Set active</button>
          @endif

          <div class="row">
            <div class="col-md-6">
              <button type="submit" id="addRoutine" class="btn btn-success btn-fullwidth" role="button"><i class="fal fa-save"></i> Update</button>
            </div>
            <div class="col-md-6">
              <button type="button" class="btn btn-danger routine-back btn-fullwidth"><i class="fal fa-angle-left"></i> Back</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>