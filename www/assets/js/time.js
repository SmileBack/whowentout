//= require lib/jquery.js
//= require lib/date.format.js
//= require lib/timeinterval.js
//= require lib/timepassedevent.js

$('.current_time').entwine({
    onmatch: function() {
        var self = $(this);
        $('body').bind('timechanged', function(e) {
            self.html(self.formatTime(e.time));
        });
    },
    onunmatch: function() {
    },
    formatTime: function(time) {
        return time.format('dddd, mmm dS, yyyy')
        + '<br/>'
        + time.format('h:MM:ss TT');
    }
});

(function() {

    function format_duration(duration) {
        if (duration.isNegative())
            return '';

        duration = duration.round('s');
        
        return duration.format();
    }

    $('.time_until').entwine({
        onmatch: function() {
            this._super();
            var self = this;

            $('body').bind('timechanged', function(e) {
                var duration = e.time.timeUntil(self.targetTime());
                self.text(format_duration(duration));
            });
        },
        onunmatch: function() {
            this._super();
            //todo unbind behavior?
        },
        targetTime: function() {
            return new Date(this.attr('data-time') * 1000);
        }
    });

})();

jQuery(function($) {
    TimePassedEvent.GetCurrentTime = current_time;
    
    var doorsCloseEvent = new TimePassedEvent(doors_closing_time());
    var doorsOpenEvent = new TimePassedEvent(doors_opening_time());
    var nextDayEvent = new TimePassedEvent(tomorrow_time());

    function reload_dashboard_page() {
        if ( $('#dashboard_page').length > 0)
            window.location.reload(true);
    }

    doorsCloseEvent.bind('timepassed', reload_dashboard_page);
    doorsOpenEvent.bind('timepassed', reload_dashboard_page);
    nextDayEvent.bind('timepassed', reload_dashboard_page);

    function trigger_time_changed() {
        var e = $.Event('timechanged', {time: current_time()});
        $('body').trigger(e);
    }
    every(1, trigger_time_changed);
    trigger_time_changed();
});
