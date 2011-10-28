//= require widgets/jquery.countdowntimer.js
//= require lib/timepassedevent.js

jQuery(function($) {
    if ($('#countdown').targetTime() != null)
        $('#countdown').delay(1200).fadeIn(500);

    function transition_out() {
        $('body > *').fadeOut(1000, function() {
           window.location = '/';
        });
    }

    var event = new TimePassedEvent( $('#countdown').targetTime() );
    event.bind('timepassed', function() {
        setTimeout(transition_out, 3000);
    });
});
