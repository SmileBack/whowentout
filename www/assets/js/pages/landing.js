//= require widgets/jquery.countdowntimer.js
//= require lib/timepassedevent.js

jQuery(function($) {
    function transition_out() {
        $('body > *').fadeOut(1000, function() {
           window.location = '/';
        });
    }

    var targetTime = $('#countdown').targetTime();
    var currentTime = new Date();
    var timeLeft = currentTime.timeUntil(targetTime);

    if (timeLeft.isNegative()) {
        transition_out();
    }

    if (targetTime != null)
        $('#countdown').delay(1200).fadeIn(500);

    var event = new TimePassedEvent( targetTime );
    event.bind('timepassed', function() {
        setTimeout(transition_out, 3000);
    });
});
