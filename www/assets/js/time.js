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
  
  if ( $('#wwo').doorsOpen() ) {
    var alreadyTriggeredDoorsClose = false;
    $('#wwo').live('timechanged', function(e, time) {
      var duration = time.timeUntil( doors_closing_time() );
      if (duration.isNegative() && $('#wwo').doorsOpen() && alreadyTriggeredDoorsClose == false) {
        alreadyTriggeredDoorsClose = true;
        $('#wwo').trigger('doorsclose');
      }
    });
  }
  
  if ( $('#wwo').doorsClosed() ) {
    var alreadyTriggeredDoorsOpen = false;
    $('#wwo').live('timechanged', function(e, time) {
      var duration = time.timeUntil( doors_opening_time() );
      if (duration.isNegative() && $('#wwo').doorsClosed() && alreadyTriggeredDoorsOpen == false) {
        alreadyTriggeredDoorsOpen = true;
        $('#wwo').trigger('doorsclose');
      }
    });
  }
  
  every(1, function() {
    $('#wwo').trigger('timechanged', current_time());
  });
  $('#wwo').trigger('timechanged', current_time());
  
});
