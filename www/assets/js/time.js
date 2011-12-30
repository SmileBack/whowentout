//= require lib/jquery.js
//= require lib/date.format.js
//= require lib/timeinterval.js
//= require lib/timepassedevent.js

(function() {

    var timeDelta = 0;

    function calculate_time_delta() {
        var serverUnixTs = window.settings.time.current;
        //Unix timestamp uses seconds while JS Date uses milliseconds
        var serverTime = new Date(serverUnixTs * 1000);
        var browserTime = new Date();
        var delta = (serverTime - browserTime);
        timeDelta = delta;
    }
    calculate_time_delta();

    window.time_delta = function() {
        return timeDelta;
    }
    
})();

function current_time() {
    var time = new Date();
    var tzOffset = 0;//-50400;
    time.setMilliseconds(time.getMilliseconds() + time_delta() + tzOffset);
    return time;
}

function yesterday_time() {
    var unixTs = window.settings.time.yesterday;
    //Unix timestamp uses seconds while JS Date uses milliseconds
    return new Date(unixTs * 1000);
}

function tomorrow_time() {
    var unixTs = window.settings.time.tomorrow;
    //Unix timestamp uses seconds while JS Date uses milliseconds
    return new Date(unixTs * 1000);
}

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
    //this method prevents browsers with an incorrect time form providing incorrect results
    TimePassedEvent.GetCurrentTime = current_time;

    var nextDayEvent = new TimePassedEvent(tomorrow_time());

    function reload_dashboard_page() {
        if ($('#dashboard_page').length > 0)
            window.location.reload(true);
    }

    nextDayEvent.bind('timepassed', reload_dashboard_page);

    function trigger_time_changed() {
        var e = $.Event('timechanged', {time: current_time()});
        $('body').trigger(e);
    }

    every(1, trigger_time_changed);
    trigger_time_changed();
});
