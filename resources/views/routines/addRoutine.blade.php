@extends('layouts.app')

@section('content')
  <form id="routines" method="POST" action="/dashboard/my_routines">
    <div class="card m-t-10 m-b-10">
      <div class="card-header card-header-icon" data-background-color="rose">
        <i class="material-icons">today</i>
      </div>
      <div class="card-content">
        <h4 class="card-title">New Routine</h4>
        <input type="hidden" id="exerciseNr" value="0">
        <input type="hidden" id="supersetNr" value="0">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        
        {{-- Routine Name --}}
        <div class="form-group label-floating">
          <label class="control-label" for="routine_name">Routine Name</label>
          <input type="text" class="required form-control" id="routine_name" name="routine_name">
        </div>
      </div>
    </div>

    <div id="sortable">
      <div class="card m-t-10 m-b-10">
        <div class="card-content">
          <div class="sortable-content">
            <div class="sort-icon handle">
              Drag me to sort
              <span class="fa fa-arrows-v"></span>
            </div>
            {{-- Excersice Name --}}
            <div class="form-group label-floating">
              <label class="control-label" for="exercise_name">Excersice name</label>
              <input type="text" class="required form-control exercise_name" id="exercise_name" name="exercises[0][exercise_name]">
            </div>

            {{-- Muscle Group --}}
            <div class="form-group">
              <input class="exerciseOrder" type="hidden" name="exercises[0][order_nr]" value="0">
              <select id="muscle_group" name="exercises[0][muscle_group]" class="selectpicker" data-style="select-with-transition" title="Choose a muscle group" data-size="8">
                <option selected disabled>Select a muscle group</option>
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
                <div class="form-group label-floating">
                  <label class="control-label" for="goal_weight">Weight goal</label>
                  <input type="number" step="any" class="required form-control" id="goal_weight" name="exercises[0][goal_weight]">
                </div>
              </div>

              {{-- Sets Goal --}}
              <div class="col-md-4">
                <div class="form-group label-floating">
                  <label class="control-label" for="goal_sets">Sets goal</label>
                  <input type="number" class="required form-control" id="goal_sets" name="exercises[0][goal_sets]">
                </div>
              </div>

              {{-- Reps Goal --}}
              <div class="col-md-4">
                <div class="form-group label-floating">
                  <label class="control-label" for="goal_reps">Reps goal</label>
                  <input type="number" class="required form-control" id="goal_reps" name="exercises[0][goal_reps]">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="exercises[0][is_warmup]">
                    Warmup set
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> <!-- .card -->
    </div> <!-- #sortable -->
          
    <div id="alert-field" class="m-t-15">
    </div>
    
    <div class="card m-t-10 m-b-10">
      <div class="card-content">
        <button id="addMore" type="button" class="btn btn-primary">Add another exercise</button>
        <button id="addSuperset" type="button" class="btn btn-primary">Add superset group</button>
        <div class="pull-right">
          <button type="submit" id="addRoutine" class="btn btn-success" href="my_routines/add_routine" role="button"><span class="fa fa-plus"></span> Add routine</button>
          <a class="btn btn-danger" href="/dashboard/my_routines" role="button"><span class="fa fa-ban"></span> Cancel</a>
        </div>
      </div>
    </div>
  </form>
@endsection

@section('script')
  <script src="/js/routines.js"></script>
  <script>


    window.onbeforeunload = function(e) {

      if ($(e.target.activeElement).attr('type') !== 'submit') {
        return "Leaving this page will reset the data you entered";
      }
      
    };
  </script>
@endsection