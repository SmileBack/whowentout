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
      var date = yesterday_time().format('mmm dS');
      WWO.dialog.title('Confirm Checkin')
         .message('<p>You are about to check into <em>' + place.name + '</em> for the night of ' + date + '<p>'
                + '<p>This will allow you to see others to have checked in as well.</p>')
         .setButtons('yesno')
         .refreshPosition()
         .showDialog('confirm_checkin');
    }
    else {
      WWO.dialog.title("Can't Checkin")
         .message("You can't checkin because the doors have closed")
         .setButtons('ok')
         .refreshPosition()
         .showDialog('cant_checkin');
    }
  }
});

$('.confirm_checkin.dialog').live('button_click', function(e, button) {
  if (button.hasClass('y')) {
    $('#checkin_form').submit();
  }
});

