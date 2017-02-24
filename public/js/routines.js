$("#addMore").on('click', function() {
  var currentExerciseNr = parseInt($("#exerciseNr").val());
  var exerciseNr = currentExerciseNr + 1;

  var formData = '<hr>' +
    '<div class="form-group">' +
      '<label for="excersice_name">Excersice name</label>' +
      '<input type="text" class="form-control" id="excersice_name" name="exercises[' + exerciseNr + '][exercise_name]" placeholder="Excersice name">' +
    '</div>' +
    '<div class="form-group">' +
      '<label for="muscle_group">Muscle group</label>' +
      '<select class="form-control" id="muscle_group" name="exercises[' + exerciseNr + '][muscle_group]">' +
        '<option value="none" selected disabled>Select a muscle group</option>' +
        '<option value="back">Back</option>' +
        '<option value="arms">Arms</option>' +
        '<option value="legs">Legs</option>' +
        '<option value="chest">Chest</option>' +
      '</select>' +
    '</div>' +
    '<div class="row">' +
      '<div class="col-md-4">' +
        '<div class="form-group">' +
          '<label for="goal_weight">Weight goal</label>' +
          '<input type="number" class="form-control" id="goal_weight" name="exercises[' + exerciseNr + '][goal_weight]" placeholder="How much weight per lift">' +
        '</div>' +
      '</div>' +
      '<div class="col-md-4">' +
        '<label for="goal_sets">Sets goal</label>' +
          '<input type="number" class="form-control" id="goal_sets" name="exercises[' + exerciseNr + '][goal_sets]" placeholder="How many times to repeat this excersice">' +
      '</div>' +
      '<div class="col-md-4">' +
        '<label for="goal_reps">Reps goal</label>' +
          '<input type="number" class="form-control" id="goal_reps" name="exercises[' + exerciseNr + '][goal_reps]" placeholder="How many repetitions per set">' +
      '</div>' +
    '</div>';
  console.log(currentExerciseNr);
  $("#exerciseNr").val(exerciseNr);
	$("#formData").append(formData);
});