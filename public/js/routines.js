$(function() {
  $("#sortable")
    .sortable({
      handle: '.handle',
      cursor: 'move',
      cancel: ''
    })
    .disableSelection();
});

$(".viewRoutine").on('click', function() {
  var routineId = $(this).children('input').val();

  $.ajax({
    url: '/dashboard/my_routines/view/' + routineId,
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    method: 'GET',
    success: function(data) {
      $("#modalData").html(data['data']);
      $("#sortable")
      .sortable({
        handle: '.handle',
        cursor: 'move',
        cancel: ''
      })
      .disableSelection();
    },
  })
});

/* Functions for deleting a routing */
$(".deleteRoutine").on('click', function() {
  var routineId = $(this).attr('id');
  $(".okDelete").attr('id', routineId);
});

$(".okDelete").on('click', function() {
  var routineId = $(this).attr('id');
  deleteRoutine(routineId);
});

var deleteRoutine = function(routineId) {
  $.ajax({
    url: '/dashboard/my_routines/delete/' + routineId,
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    method: 'GET',
    success: function(data) {
      $("#routine-" + routineId).fadeOut();
    },
  });
}

/* Functions for removing/adding exerciserows */
$(document).on('click', '#addMore', function() {
  var currentExerciseNr = parseInt($("#exerciseNr").val());
  var exerciseNr = currentExerciseNr + 1;

  var formData = '<div class="thisExercise">' +
    '<hr>' + 
    '<div class="sort-icon handle">Drag me to sort' +
      '<span class="fa fa-arrows-v"></span>' +
    '</div>' +
    '<div class="form-group">' +
      '<label for="excersice_name">Excersice name</label>' +
      '<label class="control-label hidden"> | This field is required</label>' +
      '<input type="text" class="required form-control exercise_name" id="excersice_name" name="exercises[' + exerciseNr + '][exercise_name]" placeholder="Excersice name">' +
    '</div>' +
    '<div class="form-group">' +
      '<label for="muscle_group">Muscle group</label>' +
      '<label class="control-label hidden"> | This field is required</label>' +
      '<select class="required form-control" id="muscle_group" name="exercises[' + exerciseNr + '][muscle_group]">' +
        '<option value="none" selected disabled>Select a muscle group</option>' +
        '<option value="back">Back</option>' +
        '<option value="biceps">Biceps</option>' +
        '<option value="triceps">Triceps</option>' +
        '<option value="abs">Abs</option>' +
        '<option value="shoulders">Shoulders</option>' +
        '<option value="legs">Legs</option>' +
        '<option value="chest">Chest</option>' +
      '</select>' +
    '</div>' +
    '<div class="row">' +
      '<div class="col-md-4">' +
        '<div class="form-group">' +
          '<label for="goal_weight">Weight goal</label>' +
          '<label class="control-label hidden"> | This field is required</label>' +
          '<input type="number" step="any" class="required form-control" id="goal_weight" name="exercises[' + exerciseNr + '][goal_weight]" placeholder="How much weight per lift">' +
        '</div>' +
      '</div>' +
      '<div class="col-md-4">' +
        '<div class="form-group">' +
          '<label for="goal_weight">Weight goal</label>' +
          '<label for="goal_sets">Sets goal</label>' +
          '<label class="control-label hidden"> | This field is required</label>' + 
          '<input type="number" class="required form-control" id="goal_sets" name="exercises[' + exerciseNr + '][goal_sets]" placeholder="How many times to repeat this excersice">' +
        '</div>' +
      '</div>' +
      '<div class="col-md-4">' +
        '<div class="form-group">' +
          '<label for="goal_reps">Reps goal</label>' +
          '<label class="control-label hidden"> | This field is required</label>' +
          '<input type="number" class="required form-control" id="goal_reps" name="exercises[' + exerciseNr + '][goal_reps]" placeholder="How many repetitions per set">' +
        '</div>' +
      '</div>' +
    '</div>' +
    '<a class="deleteExercise btn btn-sm btn-danger pull-right"><span class="fa fa-trash"></span></a>' +
  '</div>';
  $("#exerciseNr").val(exerciseNr);
	$("#sortable").append(formData);
});

$(document).on('click', '.deleteExercise', function() {
  $(this).closest('.thisExercise').fadeOut(function() {
    $(this).empty();
  });
});

$(document).on('click', '#addRoutine', function() {
  var ok = true
  $(".required").each(function() {
    if ($(this).val() == "" || $(this).val() == null) {
      $(this).closest(".form-group").addClass("has-error").find(".control-label").removeClass("hidden")
      ok = false
    } else {
      $(this).closest(".form-group").removeClass("has-error").find(".control-label").addClass("hidden")
    }
  })

  var names = [];
  var dupes = [];
  var namesOk = false;
  $(".exercise_name").each(function() {
    names.push($(this).val())
  })
  names.sort()
  for (var i = 0; i < names.length - 1; i++) {
    if (names[i + 1] == names[i]) {
      dupes.push(names[i]);
      namesOk = true;
      ok = false;
    }
  }

  if (namesOk) {
    $("#alert-field").html('<div class="alert alert-danger">' +
        '<strong>Whops!</strong> Some of your exercises shares the same name (' + dupes[0] + '). This might cause issues. Append something to your duplicate exercisenames and try again.' +
      '</div>')
  } else {
    $("#alert-field").empty();
  }



  return ok
})

$(document).on('click', '#changeStatus', function() {
  var routineId = $("#routineId").val();
  var status = $("#status").val();

  $.ajax({
    url: '/dashboard/my_routines/edit/status/' + routineId,
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    method: 'POST',
    data: {
      'routineId': routineId,
      'status': status,
    },
    success: function(data) {
      console.log(data.success);

      if (data.success) {
        location.reload();
      } else {
        $("#changeStatus").removeClass("btn-default").addClass("btn-danger").text("Refresh page and try again!")
      }
    },
    error: function() {
      $("#changeStatus").removeClass("btn-default").addClass("btn-danger").text("Refresh page and try again!")
    }
  })
})

