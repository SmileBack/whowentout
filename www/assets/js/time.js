$('#dashboard_page #wwo, #home_page #wwo').live('doorsclose doorsopen nextday', function() {
  window.location.reload(true);
});

$('.current_time').entwine({
  onmatch: function() {
    var self = $(this);
    $('#wwo').bind('timechanged', function(e, time) {
      self.html( self.formatTime(time) );
    });
  },
  onunmatch: function() {},
  formatTime: function(time) {
    return time.format('dddd, mmm dS, yyyy')
     + '<br/>'
     + time.format('h:MM:ss TT');
  }
});

function time_passed(time, fn) {
  var alreadyFired = false;
  
  // time has already passed
  if ( current_time().timeUntil(time).isNegative() )
    return;
  
  $('#wwo').live('timechanged', function(e, currentTime) {
    var duration = currentTime.timeUntil( time );
    if (duration.isNegative() && !alreadyFired) {
      alreadyFired = true;
      fn();
    }
  });
  
}

jQuery(function($) {
  
  if ( $('#wwo').doorsOpen() ) {
    $('#wwo').live('timechanged', function(e, time) {
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
  }
  
  time_passed(doors_closing_time(), function() {
    $('#wwo').trigger('doorsclose');
  });
  
  time_passed(doors_opening_time(), function() {
    $('#wwo').trigger('doorsopen');
  });
  
  time_passed(tomorrow_time(), function() {
    $('#wwo').trigger('nextday');
  });
  
  every(1, function() {
    $('#wwo').trigger('timechanged', current_time());
  });
  $('#wwo').trigger('timechanged', current_time());
  
});
