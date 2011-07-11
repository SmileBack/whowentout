jQuery(function($) {
  
  $('#checkin_form :submit').click(function(e) {
    e.preventDefault();
    
    var place = $('#checkin_form option:selected').text();
    var doorsOpen = $('.closing_time').hasClass('doors_open');
    if (doorsOpen) {
      WWO.dialog.title('Confirm Checkin')
       .message('Checkin to ' + place + '?')
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
  });
  
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
