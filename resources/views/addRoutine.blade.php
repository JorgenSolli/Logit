@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
      <ul class="nav nav-sidebar">
        <li><a href="/dashboard"><span class="fa fa-tachometer fa-lg"></span>&nbsp;&nbsp;Dashboard</a></li>
        <li class="active"><a><span class="fa fa-tasks fa-lg"></span>&nbsp;&nbsp;My Routines</a></li>
        <li><a href="#"><span class="fa fa-play fa-lg"></span>&nbsp;&nbsp;Start Workout</a></li>
        <li><a href="#"><span class="fa fa-pencil fa-lg"></span>&nbsp;&nbsp;Edit Workouts</a></li>
      </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <h1 class="page-header">Add Routine</h1>
      <form id="routines" method="POST" action="/dashboard/my_routines/add_routine">
        <input type="hidden" id="exerciseNr" value="0">
        {{ csrf_field() }}
        
        {{-- Routine Name --}}
        <div class="form-group">
          <label for="routine_name">Routine Name</label>
          <input type="text" class="form-control" id="routine_name" name="routine_name" placeholder="Routine Name">
        </div>
        <hr>

        {{-- Excersice Name --}}
        <div class="form-group">
          <label for="exercise_name">Excersice name</label>
          <input type="text" class="form-control" id="exercise_name" name="exercises[0][exercise_name]" placeholder="Excersice name">
        </div>

        {{-- Muscle Group --}}
        <div class="form-group">
          <label for="muscle_group">Muscle group</label>
          <select class="form-control" id="muscle_group" name="exercises[0][muscle_group]">
            <option value="none" selected disabled>Select a muscle group</option>
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
              <input type="number" class="form-control" id="goal_weight" name="exercises[0][goal_weight]" placeholder="How much weight per lift">
            </div>
          </div>

          {{-- Sets Goal --}}
          <div class="col-md-4">
            <label for="goal_sets">Sets goal</label>
            <input type="number" class="form-control" id="goal_sets" name="exercises[0][goal_sets]" placeholder="How many times to repeat this excersice">
          </div>

          {{-- Reps Goal --}}
          <div class="col-md-4">
            <label for="goal_reps">Reps goal</label>
            <input type="number" class="form-control" id="goal_reps" name="exercises[0][goal_reps]" placeholder="How many repetitions per set">
          </div>
        </div>

        <div id="formData"></div>
        <button id="addMore" type="button" class="btn btn-info">Add another exercise!</button>
        <hr>
        <button type="submit" class="btn btn-success" href="my_routines/add_routine" role="button"><span class="fa fa-plus"></span> Add routine</button>
        <a class="btn btn-danger" href="/dashboard" role="button"><span class="fa fa-ban"></span> Cancel</a>
      </form>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script src="/js/routines.js"></script>
@endsection