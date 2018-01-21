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
            <div class="clearfix">
              <div class="sort-icon handle btn-sm btn-primary pull-left">
                <span class="fal fa-arrows-v"></span> Drag to sort
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 col-sm-6">
                {{-- Excersice Name --}}
                <div class="form-group label-floating">
                  <label class="control-label" for="exercise_name">Excersice name</label>
                  <input type="text" class="required form-control exercise_name" id="exercise_name" name="exercises[0][exercise_name]">
                </div>
              </div>

              <div class="col-xs-12 col-sm-6">
                {{-- Muscle Group --}}
                <div class="form-group">
                  <input class="exerciseOrder" type="hidden" name="exercises[0][order_nr]" value="0">
                  <select id="muscle_group" name="exercises[0][muscle_group]" class="selectpicker" data-style="select-with-transition" title="Select a muscle group" data-size="7">
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
              <div class="col-md-4">
                <div class="form-group label-floating">
                  <label class="control-label" for="goal_weight">Weight goal</label>
                  <input type="number" step="any" class="required form-control" id="goal_weight" name="exercises[0][goal_weight]">
                </div>
              </div>

              {{-- Sets Goal --}}
              <div class="col-sm-6 col-xs-6 col-md-4">
                <div class="form-group label-floating">
                  <label class="control-label" for="goal_sets">Sets goal</label>
                  <input type="number" class="required form-control" id="goal_sets" name="exercises[0][goal_sets]">
                </div>
              </div>

              {{-- Reps Goal --}}
              <div class="col-sm-6 col-xs-6 col-md-4">
                <div class="form-group label-floating">
                  <label class="control-label" for="goal_reps">Reps goal</label>
                  <input type="number" class="required form-control" id="goal_reps" name="exercises[0][goal_reps]">
                </div>
              </div>
            </div>
            <div class="row">
              {{-- Reps Goal --}}
              <div class="col-md-8 col-xs-6">
                <div class="form-group label-floating">
                  <label class="control-label" for="media">Media</label>
                  <input type="text" class="required form-control" id="media" name="exercises[0][media]">
                  <i class="material-icons material-icons-sm pointer is-tooltip" 
                      rel="tooltip" 
                      data-placement="top" 
                      title="Here you can add any URL that you like. Maybe to a YouTube video showing how the exercise is done?">
                      help
                  </i>
                </div>
              </div>

              {{-- Warmup --}}
              <div class="col-md-4 col-xs-6">
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
        <div class="row">
          <div class="col-md-7 col-sm-12">
            <button id="addMore" type="button" class="btn btn-primary is-sm-fullwidth">Add another exercise</button>
            <button id="addSuperset" type="button" class="btn btn-primary pull-left is-sm-fullwidth">Add superset group</button>
          </div>
          <div class="col-md-5 col-sm-12">
            <button type="submit" id="addRoutine" class="btn btn-success pull-right is-sm-fullwidth" href="my_routines/add_routine" role="button"><span class="fal fa-save"></span> Add routine</button>
            <a class="btn btn-danger pull-right is-sm-fullwidth" href="/dashboard/my_routines" role="button"><span class="fal fa-ban"></span> Cancel</a>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection

@section('script')
  <script src="{{ mix('/js/routines.min.js') }}"></script>
  <script>


    window.onbeforeunload = function(e) {

      if ($(e.target.activeElement).attr('type') !== 'submit') {
        return "Leaving this page will reset the data you entered";
      }
      
    };
  </script>
@endsection