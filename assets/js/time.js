jQuery(function($) {
  var closesAt = get_closing_time();
  var triggeredDoorsClose = false;
  
  $('#wwo').live('timechanged', function(e, time) {
    $('#current_time').html( format_time(time) );
  });
  
  $('#wwo').live('timechanged', function(e, time) {
    var duration = time_until(closesAt);
    if (duration != null) {
      var formattedDuration = format_duration(duration);
      $('.remaining_time').text(formattedDuration);
    }
  });
  
  $('#wwo').live('timechanged', function(e, time) {
    var duration = time_until(closesAt);
    if (duration == null && triggeredDoorsClose == false) {
      triggeredDoorsClose = true;
      $('#wwo').trigger('doorsclose');
    }
  });
  
  every(1, function() {
    $('#wwo').trigger('timechanged', current_time());
  });
  $('#wwo').trigger('timechanged', current_time());
  
  function format_time(time) {
    return time.format('dddd, mmm dS, yyyy')
     + '<br/>'
     + time.format('h:MM:ss TT');
    $('#current_time').html( formattedTime );
  }
  
  function format_duration(duration) {
    var msg = [];
    if (duration.ts > 60) {
      if (duration.h > 0)
        msg.push(duration.h + ' hr');
      
      if (duration.m > 0)
        msg.push((duration.m + 1) + ' min');
    }
    else {
      msg.push(duration.ts + ' sec');
    }
    return msg.join(' ');
  }
  
  function get_closing_time() {
    var currentTime = new Date();
    var initialRemainingSeconds = parseInt( $('.remaining_time').attr('remaining') );
    currentTime.setSeconds(currentTime.getSeconds() + initialRemainingSeconds);
    return currentTime;
  }
  
  function time_until(target_time) {
    var now = new Date();
    var totalMs = target_time - now;

    if (totalMs < 0)
      return null;
    
    var totalSeconds = Math.floor(totalMs / 1000);
    var totalMinutes = Math.floor(totalSeconds / 60);
    var totalHours = Math.floor(totalMinutes / 60);
    
    var h = totalHours;
    var m = totalMinutes - h * 60;
    var s = totalSeconds - h * 60 * 60 - m * 60;
    
    return {h: h, m: m, s: s, ts: totalSeconds};
  }
  
});
