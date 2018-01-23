var soundInterval;
$.ajax({
  method: 'GET',
  url: '/user/settings/get',
  async: false,
  success: function(data) {
    soundInterval = data.timer_sound_interval;
  }
});

var timerHtml = '<div id="timer">' +
    '<div class="row">' +
      '<div class="col-xs-4 text-center">' +
        '<span id="timer-minutes">00</span>:<span id="timer-seconds">00</span>' +
      '</div>' +
      '<div class="col-xs-4 text-center">' +
        '<i id="timer-play" class="fal fa-play"></i>' +
      '</div>' +
      '<div class="col-xs-4 text-center">' +
        '<i id="timer-reset" class="fal fa-repeat"></i>' +
      '</div>' +
    '</div>' +
  '</div>';

$("#app").append(timerHtml);
$("footer.footer").addClass("hasTimer");

// Keep track of soundqueue. Will reset once audio plays.
var secondsSinceAudio = 0;
var hasPlayedAudtio = false;
var seconds = 0;
var minutes = 0;

var realStartTime, timeSinceLastDing, totalSeconds;

var timerMinutes = $("#timer-minutes");
var timerSeconds = $("#timer-seconds");

if (soundInterval) {
  var ding = new Audio('/media/ding.wav');
}

var addSecond = function(timeStarted) {
    return Math.floor((new Date - timeStarted) / 1000);
}

var intervarSettings = function(reset) {  
  totalSeconds = addSecond(realStartTime);
  secondsSinceAudio = addSecond(timeSinceLastDing);

  minutes = Math.floor(totalSeconds / 60);
  if (minutes > 0) {
    seconds = totalSeconds - (minutes * 60);
  } else {
    seconds = totalSeconds;
  }

  if (soundInterval && secondsSinceAudio > soundInterval && !hasPlayedAudtio) {
    ding.play();
    hasPlayedAudtio = true;
  }
  
  if (seconds < 60 && minutes < 1) {
    timerMinutes.html('00');
  }
  if (seconds < 10) {
    seconds = ('0' + seconds).slice(-2); 
  }

  if (minutes < 10) {
    minutes = ('0' + minutes).slice(-2);
  }

  timerSeconds.html(seconds);
  timerMinutes.html(minutes);
}

var countSeconds = null;

var resetSound = function(timer) {
  ding.pause();
  ding.currentTime = 0;
  secondsSinceAudio = 0;
  minutes = 0;
  seconds = 0;
  window.clearInterval(countSeconds);

  if (timer) {
    realStartTime = new Date;
    timeSinceLastDing = new Date;
    timerMinutes.html('00');
    timerSeconds.html('00');

    countSeconds = setInterval(function() {
      intervarSettings(true);
    }, 1000);
  }
}

var operators = function(method) {
  if (method === "pause") {
    resetSound();
    hasPlayedAudtio = true;
  }

  else if (method === "play") {
    realStartTime = new Date;
    timeSinceLastDing = new Date;
    countSeconds = setInterval(function() {
      intervarSettings(true);
    }, 1000);
  }

  else if (method === "reset") {
    resetSound(true);
  }
};

$(document).on('click', '#timer-play', function() {
  $(this).removeClass("fa-play").addClass('fa-stop').attr('id', "timer-pause");
  if (soundInterval) {
    ding.play(); ding.pause();
  }
  operators("play");
});

$(document).on('click', '#timer-pause', function() {
  $(this).removeClass("fa-stop").addClass('fa-play').attr('id', "timer-play");
  operators("pause");
});

$(document).on('click', '#timer-reset', function() {
  $("#timer-play").removeClass("fa-play").addClass('fa-stop').attr('id', "timer-pause");
  operators("reset");
});