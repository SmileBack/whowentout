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

jQuery(function($) {
  
  var x = parseInt( $('#x').val() ),
      y = parseInt( $('#y').val() ),
      width = parseInt( $('#width').val() ),
      height = parseInt( $('#height').val() );
  
  var api = WWO.api = $.Jcrop('#crop img', {
    aspectRatio: 0.75,
    onChange: onChange,
    onSelect: onSelect
  });
  
  api.setSelect([x, y, x + width, y + height]);
  
  api.selection.enableHandles();
  
  function set_textbox_coordinates(x, y, width, height) {
    $('#x').val(x);
    $('#y').val(y);
    $('#width').val(width);
    $('#height').val(height);
  }
  
  function onChange(coords) {
    set_textbox_coordinates(coords.x, coords.y, coords.w, coords.h);
    showPreview(coords);
  }
  
  function onSelect(coords) {
    set_textbox_coordinates(coords.x, coords.y, coords.w, coords.h);
    showPreview(coords);
  }
  
  function showPreview(coords) {
    
    if (parseInt(coords.w) > 0) {
      var smallWidth = $('#crop_preview').width();
      var smallHeight = $('#crop_preview').height();
      var largeWidth = $('#crop img').width();
      var largeHeight = $('#crop img').height();
      var rx = smallWidth / coords.w;
      var ry = smallHeight / coords.h;
 
      $('#crop_preview img').css({
        width: Math.round(rx * largeWidth) + 'px',
        height: Math.round(ry * largeHeight) + 'px',
        marginLeft: '-' + Math.round(rx * coords.x) + 'px',
        marginTop: '-' + Math.round(ry * coords.y) + 'px'
      });
    }
    
  }
  
});
