//= require widgets/jquery.countdowntimer.js
//= require lib/timepassedevent.js

jQuery(function($) {
    if ($('#countdown').targetTime() != null)
        $('#countdown').delay(1200).fadeIn(500);

    var event = new TimePassedEvent( $('#countdown').targetTime() );
    
});
