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
    this.hide();
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
