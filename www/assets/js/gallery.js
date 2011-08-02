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
        var el;
        console.log(response);
        for (var k in response.new_attendees) {
          el = $('<li>' + response.new_attendees[k] + '</li>');
          el.addClass('new');
          el.hide();
          this.prepend(el);
          el.fadeIn();
        }
        this.attr('data-count', response.count);
      }
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
