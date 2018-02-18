
var soundInterval, ding, cosmeticSec, cosmeticMin;
var hasPlayedAudio = false;
var playDing = false;
var timer = new Timer();

if (timerSettings.play_sound) {    
    var ding = new Audio('/media/ding.wav'); 
    ding.volume = 0;
}

$(document).ready(function() {
    var loadTimerHtml = '<div id="timer">' +
                            '<div class="row">' +
                                '<div class="col-xs-12 text-center">' +
                                    '<button id="loadTimer" class="btn btn-rose">' +
                                        '<i class="far fa-clock"></i> Load timer' + 
                                    '</button>' +
                                '</div>' +
                            '</div>' +
                        '</div>';

    var timerHtml = '<div class="row">' +
                        '<div class="col-xs-4 text-center">' +
                            '<div id="timerValues"></div>' +
                        '</div>' +
                        '<div class="col-xs-4 text-center">' +
                            '<i id="timer-play" class="fal fa-play"></i>' +
                        '</div>' +
                        '<div class="col-xs-4 text-center">' +
                            '<i id="timer-reset" class="fal fa-repeat"></i>' +
                        '</div>' +
                    '</div>';

    $("#app").append(loadTimerHtml);
    $("footer.footer").addClass("hasTimer");

    $(document).on('click', '#loadTimer', function() {
        $("#timer").html(timerHtml);
        
        // Loads the sound so it's initialized on all phone devices. 
        if (timerSettings.play_sound) {
            ding.play();
        }

        if (timerSettings.direction == 'default') {
            $("#timerValues").html('00:00');
        } else {
            cosmeticSec = timerSettings.seconds < 10 ? cosmeticSec = ('0' + timerSettings.seconds).slice(-2) : cosmeticSec = timerSettings.seconds;
            cosmeticMin = timerSettings.minutes < 10 ? cosmeticMin = ('0' + timerSettings.minutes).slice(-2) : cosmeticMin = timerSettings.minutes;
            $("#timerValues").html(cosmeticMin + ':' + cosmeticSec);
        }
    });

    var playSound = function() {
        ding.volume = 1.0;
        ding.play();

        hasPlayedAudio = true;
    }

    $(document).on('click', '#timer-play', function() {
        hasPlayedAudio = false;
        
        if (timerSettings.direction == 'default') {
            var time = timer.getTimeValues();
            // If the audio has already been played with the current timer-session
            if (time && time.minutes == timerSettings.minutes && time.seconds >= timerSettings.seconds) {
                hasPlayedAudio = true;
            }
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
            if (time.minutes == timerSettings.minutes && time.seconds >= timerSettings.seconds && timerSettings.play_sound && !hasPlayedAudio) {
                playSound();
            }
        } else {
            if (time.minutes == 0 && time.seconds < 1 && timerSettings.play_sound && !hasPlayedAudio) {
                playSound();
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