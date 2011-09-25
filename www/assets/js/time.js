$('#dashboard_page #wwo, #home_page #wwo').live('doorsclose doorsopen nextday', function() {
    window.location.reload(true);
});

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

function time_passed(time, fn) {
    var alreadyFired = false;

    // time has already passed
    if (current_time().timeUntil(time).isNegative())
        return;

    $('body').live('timechanged', function(e) {
        var duration = e.time.timeUntil(time);
        if (duration.isNegative() && !alreadyFired) {
            alreadyFired = true;
            fn();
        }
    });

}

(function() {

    function format_duration(duration) {
        if (duration.isNegative())
            return '';

        if ( duration.total('m') < 1 ) {
            duration = duration.round('s');
        }
        else {
            duration = duration.roundUp('m');
        }
        
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

    /*
     if ( $('#wwo').doorsOpen() ) {
     $('body').live('timechanged', function(e, time) {
     var duration = time.timeUntil( doors_closing_time() );

     if (duration.isNegative())
     return;

     if ( duration.total('m') < 1 ) {
     duration = duration.round('s');
     }
     else {
     duration = duration.roundUp('m');
     }
     $('.remaining_time').text(duration.format());
     });
     }*/

    time_passed(doors_closing_time(), function() {
        $('#wwo').trigger('doorsclose');
    });

    time_passed(doors_opening_time(), function() {
        $('#wwo').trigger('doorsopen');
    });

    time_passed(tomorrow_time(), function() {
        $('#wwo').trigger('nextday');
    });

    function trigger_time_changed() {
        var e = $.Event('timechanged', {time: current_time()});
        $('body').trigger(e);
    }

    every(1, trigger_time_changed);
    trigger_time_changed();
});
