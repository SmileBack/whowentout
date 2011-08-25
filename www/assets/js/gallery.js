$('.party.serverinbox').live('newdata', function(e, newData) {
  console.log('version = ' + newData.toString());
  $('.recent_attendees').update();
});

$('.gallery').entwine({
  onmatch: function() {
    var el = $(this);
    every(10, function() {
      el.refreshAttendees();
    });
  },
  sorting: function() {
    return this.attr('data-sort');
  },
  refreshAttendees: function() {
    $.ajax({
      context: this,
      type: 'post',
      url: '/party/count/' + this.partyID(),
      dataType: 'json',
      data: { sort: this.sorting(), count: this.count() },
      success: function(response) {
        for (var k in response.new_attendees) {
          this.insertAttendee(response.new_attendees[k]);
        }
        this.attr('data-count', response.count);
      }
    });
  },
  insertAttendee: function(attendeeHTML) {
    var el = $('<li>' + attendeeHTML + '</li>');
    var gallery = $(this);
    el.addClass('new');
    el.hide();
    
    el.bind('imageload', function() {
      var position = el.find('.party_attendee').attr('data-after');
      console.log(position);
      if (position == 'first') {
        gallery.prepend(el);
      }
      else {
        $('#party_attendee_' + position).closest('li').after(el);
      }
      el.fadeIn();
    });
  },
  partyID: function() {
    return parseInt( this.attr('data-party-id') );
  },
  count: function() {
    return parseInt( this.attr('data-count') );
  }
});

$('.smile_form :submit').live('click', function(e) {
  e.preventDefault();
  var action = $(this).attr('value');
  var form = $(this).closest('form');
  var canSmile = $(this).hasClass('can');
  if (canSmile) {
    var senderGender = $('#wwo').otherGender();
    var message = senderGender == 'M'
                ? '<p>You are about to ' + action + '.</p>'
                + '<p>He will know that someone has smiled at him, but he will <em>not</em> know it was you unless he smiles at you as well.</p>'
                
                : '<p>You are about to ' + action + '.</p>'
                + '<p>She will know that someone has smiled at her, but she will <em>not</em> know it was you unless she smiles back at you.</p>';

    WWO.dialog.title('Confirm Smile')
              .message(message)
              .setButtons('yesno')
              .refreshPosition()
              .showDialog('confirm_smile', form);
  }
  else {
    action = action.substring(0, 1).toLowerCase() + action.substring(1);
    WWO.dialog.title("Can't Smile")
              .message("You can't " + action + " because you have already used up your smiles.")
              .setButtons('ok')
              .refreshPosition()
              .showDialog('cant_smile');
  }

});

$('.confirm_smile.dialog').live('button_click', function(e, button, form) {
  if (button.hasClass('y')) {
    form.submit();
  }
});

$('.show_mutual_friends').entwine({
  onclick: function(e) {
    e.preventDefault();
    var path = $(this).attr('href');
    $('#wwo').showMutualFriendsDialog(path);
  }
});
