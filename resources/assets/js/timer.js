
var soundInterval, ding, cosmeticSec, cosmeticMin;
var hasPlayedAudio = false;
var playDing = false;
var timer = new Timer();

var timerSettings = {};

setTimerSettings = function(data) {
    timerSettings.direction = data.timer_direction;
    timerSettings.play_sound = data.timer_play_sound;
    timerSettings.seconds = data.timer_seconds;
    timerSettings.minutes = data.timer_minutes;

    // Keep track of soundqueue. Will reset once audio plays.
    if (timerSettings.play_sound) {
        ding = new Audio('/media/ding.wav');
    }
}

var getSettings = function() {
    $.ajax({
        method: 'GET',
        url: '/user/settings/get',
        success: function(data) {
            setTimerSettings(data);   
        }
    });
}
getSettings();

$(document).ready(function() {

    var timerHtml = '<div id="timer">' +
                        '<div class="row">' +
                            '<div class="col-xs-4 text-center">' +
                                '<div id="timerValues"></div>' +
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

    if (timerSettings.direction == 'default') {
        $("#timerValues").html('00:00');
    } else {        
        cosmeticSec = timerSettings.seconds < 10 ? cosmeticSec = ('0' + timerSettings.seconds).slice(-2) : cosmeticSec = timerSettings.seconds;
        cosmeticMin = timerSettings.minutes < 10 ? cosmeticMin = ('0' + timerSettings.minutes).slice(-2) : cosmeticMin = timerSettings.minutes;

        $("#timerValues").html(cosmeticMin + ':' + cosmeticSec);
    }

    $(document).on('click', '#timer-play', function() {
        hasPlayedAudio = false;
        
        if (timerSettings.direction == 'default') {
            timer.start();
        } else {
            timer.start({
                countdown: true, 
                startValues: {
                    seconds: timerSettings.seconds,
                    minutes: timerSettings.minutes,
                }
            });
        }
        $(this).removeClass("fa-play").addClass('fa-pause').attr('id', "timer-pause");
        // Loads the sound imidediately so its initialized on all phone devices. 
        if (timerSettings.play_sound) {
            ding.play(); ding.pause();
        }
    });

    $(document).on('click', '#timer-pause', function() {
        timer.pause();
        $(this).removeClass("fa-pause").addClass('fa-play').attr('id', "timer-play");
    });

    $(document).on('click', '#timer-reset', function() {
        timer.reset();
        hasPlayedAudio = false;
        $("#timer-play").removeClass("fa-play").addClass('fa-pause').attr('id', "timer-pause");
    });

    timer.addEventListener('secondsUpdated', function (e) {
        $('#timer #timerValues').html(timer.getTimeValues().toString(['minutes', 'seconds']));
        var time = timer.getTimeValues();

        if (timerSettings.direction == 'default') {
            if (time.seconds >= timerSettings.seconds && time.minutes == timerSettings.minutes && timerSettings.play_sound && !hasPlayedAudio) {
                ding.play();
                hasPlayedAudio = true;
            }
        } else {
            if (time.minutes == 0 && time.seconds < 1) {
                ding.play();
                hasPlayedAudio = true;
                $("#timer-pause").removeClass("fa-pause").addClass('fa-play').attr('id', "timer-play");
            }
        }

    });

    timer.addEventListener('started', function (e) {
        $('#timer #timerValues').html(timer.getTimeValues().toString(['minutes', 'seconds']));
    });

    timer.addEventListener('reset', function (e) {
        $('#timer #timerValues').html(timer.getTimeValues().toString(['minutes', 'seconds']));
    });
});