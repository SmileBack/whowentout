$('#checkin_form').entwine({
  selectedPlace: function() {
    return {
      id: this.find('option:selected').attr('value'),
      name: this.find('option:selected').text()
    };
  }
});

$('#checkin_form :submit').entwine({
  form: function() {
    return this.closest('form');
  },
  onclick: function(e) {
    e.preventDefault();
    
    var doorsOpen = $('#wwo').doorsOpen();
    var place = this.form().selectedPlace();
    
    if (doorsOpen) {
      WWO.dialog.title('Confirm Checkin')
         .message('Checkin to ' + place.name + '?')
         .setButtons('yesno')
         .refreshPosition()
         .show('confirm_checkin');
    }
    else {
      WWO.dialog.title("Can't Checkin")
         .message("You can't checkin because the doors have closed")
         .setButtons('ok')
         .refreshPosition()
         .show('cant_checkin');
    }
  }
});

$('.confirm_checkin.dialog').live('button_click', function(e, button) {
  if (button.hasClass('y')) {
    $('#checkin_form').submit();
  }
});

jQuery(function($) {
  
  WWO.dialog = dialog.create();
  
  WWO.dialog.anchor('viewport', 'c'); //keeps the dialog box in the center
  $(window).bind('scroll', function() { //even when you scroll
    WWO.dialog.refreshPosition();
  });
  
});
