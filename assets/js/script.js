jQuery(function($) {
  var closesAt = get_closing_time();
  
  function get_closing_time() {
    var currentTime = new Date();
    var initialRemainingSeconds = parseInt( $('.remaining_time').attr('remaining') );
    currentTime.setSeconds(currentTime.getSeconds() + initialRemainingSeconds);
    return currentTime;
  }
  
  function every(seconds, fn) {
    return setInterval(fn, seconds * 1000);
  }
  
  function time_until(target_time) {
    var now = new Date();
    var totalMs = target_time - now;

    if (totalMs < 0)
      return null;
    
    var totalMinutes = Math.floor(totalMs / (60 * 1000));
    var h = Math.floor(totalMinutes / 60);
    var m = totalMinutes % 60;
    return {h: h, m: m};
  }

  function update_time_message() {
    var time = time_until(closesAt);
    var msg = [];
    
    if (time.h > 0) {
      msg.push(time.h + ' hr');
    }
    
    if (time.m > 0) {
      msg.push(time.m + ' min');
    }
    
    $('.remaining_time').text(msg.join(' '));
  }

  every(1, update_time_message);
  update_time_message();
});

WWO = {};

jQuery(function($) {
  
  $('#checkin_form :submit').click(function(e) {
    e.preventDefault();
    
    var place = $('#checkin_form option:selected').text();
    WWO.dialog.title('Confirm Checkin')
     .message('Checkin to ' + place + '?')
     .refreshPosition()
     .show('confirm_checkin')
  });
  
  $('.smile_form :submit').click(function(e) {
    e.preventDefault();
    
    var action = $(this).attr('value');
    var form = $(this).closest('form');
    WWO.dialog.title('Confirm Smile')
              .message(action + '?')
              .refreshPosition()
              .show('confirm_smile', form);
  });
  
  var d = WWO.dialog = dialog.create();
});

$('.confirm_checkin.dialog').live('button_click', function(e, button) {
  if (button.hasClass('yes')) {
    $('#checkin_form').submit();
  }
});

$('.confirm_smile.dialog').live('button_click', function(e, button, form) {
  if (button.hasClass('yes')) {
    form.submit();
  }
});
