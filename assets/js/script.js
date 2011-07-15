$('#wwo').entwine({
  doorsOpen: function() {
    return $('.closing_time').hasClass('doors_open');
  }
});

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
       .show('confirm_checkin')
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

jQuery(function($) {
  
  $('.smile_form :submit').click(function(e) {
    e.preventDefault();
    
    var action = $(this).attr('value');
    var form = $(this).closest('form');
    var canSmile = $(this).hasClass('can');
    if (canSmile) {
      WWO.dialog.title('Confirm Smile')
                .message(action + '?')
                .setButtons('yesno')
                .refreshPosition()
                .show('confirm_smile', form);
    }
    else {
      action = action.substring(0, 1).toLowerCase() + action.substring(1);
      WWO.dialog.title("Can't Smile")
                .message("You can't " + action + " because you are out of smiles")
                .setButtons('ok')
                .refreshPosition()
                .show('cant_smile');
    }
    
  });
  
  WWO.dialog = dialog.create();
  
  WWO.dialog.anchor('viewport', 'c'); //keeps the dialog box in the center
  $(window).bind('scroll', function() { //even when you scroll
    WWO.dialog.refreshPosition();
  });
  
});

$('.confirm_checkin.dialog').live('button_click', function(e, button) {
  if (button.hasClass('y')) {
    $('#checkin_form').submit();
  }
});

$('.confirm_smile.dialog').live('button_click', function(e, button, form) {
  if (button.hasClass('y')) {
    form.submit();
  }
});

$('.mutual_friends').entwine({
  onclick: function(e) {
    e.preventDefault();
    var path = $(this).attr('href');
    $('#wwo').showMutualFriendsDialog(path);
  }
});
