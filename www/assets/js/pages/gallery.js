$('.gallery .party.serverinbox').live('newdata', function(e, newData) {
  $(this).closest('.gallery')
         .refreshAttendees()
         .refreshOnlineUsers();
});

$('.gallery').entwine({
  onmatch: function() {
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
        for (var k = 0; k < response.new_attendees.length; k++) {
          this.insertAttendee(response.new_attendees[k]);
        }
        this.attr('data-count', response.count);
      }
    });
    return this;
  },
  refreshOnlineUsers: function() {
    $.ajax({
      context: this,
      type: 'post',
      url: '/party/online_users/' + this.partyID(),
      dataType: 'json',
      success: function(onlineUserIDs) {
        //console.log('refresh online users');
        //console.log(onlineUserIDs);
        this.find('.party_attendee').removeClass('online');
        for (var k = 0; k < onlineUserIDs.length; k++) {
          this.attendee(onlineUserIDs[k]).addClass('online');
        }
      }
    });
    return this;
  },
  insertAttendee: function(attendeeHTML) {
    var el = $('<li>' + attendeeHTML + '</li>');
    var gallery = $(this);
    el.addClass('new').css('display', 'inline-block');
    el.hide();
    
    el.bind('imageload', function() {
      var position = el.find('.party_attendee').attr('data-after');
      //console.log(position);
      if (position == 'first') {
        gallery.find('> ul').prepend(el);
      }
      else {
        gallery.attendee(position).closest('li').after(el);
      }
      el.fadeIn();
    });
  },
  attendee: function(user_id) {
    return this.find('#party_attendee_' + user_id);
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
    var senderGender = current_user().other_gender;
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

$('.gallery .open_chat').entwine({
  onmouseenter: function(e) {
    this.notice('Click to chat', 't');
  },
  onmouseleave: function(e) {
    $('#notice').hideNotice();
  }
});

$('.party_attendee.online').entwine({
  onmatch: function() {
    this.find('.full_name').addClass('open_chat');
  },
  onunmatch: function() {
    this.find('.full_name').removeClass('open_chat');
  }
});
