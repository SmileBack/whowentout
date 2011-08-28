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

jQuery(function($) {
  
  WWO.dialog = $.dialog.create();
  
  WWO.dialog.anchor('viewport', 'c'); //keeps the dialog box in the center
  $(window).bind('scroll', function() { //even when you scroll
    WWO.dialog.refreshPosition();
  });
  
});

$('a.confirm').entwine({
  onclick: function(e) {
    var action = this.attr('action') || 'do this';
    var result = confirm("Are you sure you want to " + action + "?");
    if (!result) {
      e.preventDefault();
    }
  }
});

$('.serverinbox').entwine({
  onmatch: function() {
    this.startChecking();
  },
  onunmatch: function() {},
  inboxUrl: function() {
    return this.attr('url');
  },
  inboxData: function(data) {
    if (data === undefined) {
      return this.data('inboxData');
    }
    else {
      var oldData = this.data('inboxData');
      this.data('inboxData', data);
      this.trigger('newdata', [data, oldData]);
      return this;
    }
  },
  startChecking: function() {
    var self = this;
    var id = every(2, function() {
      self.checkInbox();
    });
    this.data('pollingId', id);
  },
  stopChecking: function() {
    var id = this.data('pollingId');
    if (id)
      cancelEvery(id);
  },
  checkInbox: function() {
    var timestamp = (new Date()).valueOf();
    var url = this.inboxUrl();
    var callback = 'json_' + url.substring(url.lastIndexOf('/') + 1);
    var self = this;
    $.ajax({
      type: 'get',
      url: this.inboxUrl() + '?timestamp=' + timestamp,
      dataType: 'jsonp',
      jsonp: false,
      jsonpCallback: callback,
      context: this,
      success: function(newInboxData) {
        var currentInboxData = self.inboxData();
        if (currentInboxData != newInboxData) {
          self.inboxData(newInboxData);
        }
      }
    });
  }
});
