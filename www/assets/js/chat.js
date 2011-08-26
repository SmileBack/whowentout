$('.chat.serverinbox').live('newdata', function(e, version) {
  $('#chatbar').checkForNewMessages();
});

$('#chatbar').entwine({
  onmatch: function() {
    this.addChatbox().title('Chat with Dan B.').collapse();
  },
  onunmatch: function() {},
  addChatbox: function() {
    var chatbox = $('<div class="chatbox" from="me" to="33"/>')
                    .append('<div class="header"/>').find('.header')
                      .append('<h3/>')
                    .end()
                    .append('<div class="body"/>').find('.body')
                      .append('<ul class="messages"/>')
                      .append('<div class="input"/>').find('.input')
                        .append('<textarea/>')
                      .end()
                    .end();
    this.append(chatbox);
    return chatbox;
  },
  version: function() {
    return this.data('version') || 0;
  },
  checkForNewMessages: function() {
    var self = this;
    $.ajax({
      url: '/chat/messages/' + this.version(),
      type: 'get',
      dataType: 'json',
      success: function(response) {
        for (var k in response.messages) {
          self.find('.chatbox').addMessage(response.messages[k].message);
        }
        self.data('version', response.version);
      }
    });
  }
});

$('.chatbox').entwine({
  title: function(title) {
    if (title === undefined) {
      return this.find('h3').html();
    }
    else {
      this.find('h3').html(title);
      return this;
    }
  },
  otherUserId: function() {
    return this.attr('to');
  },
  addMessage: function(message) {
    var msgLi = $('<li/>').append(message);
    this.find('.messages').append(msgLi);
    this.scrollToBottom();
    return this;
  },
  scrollToBottom: function() {
    var messagesEl = this.find('.messages');
    var scrollHeight = messagesEl.get(0).scrollHeight;
    messagesEl.scrollTop(scrollHeight);
  },
  isExpanded: function() {
    return this.find('.body').is(':visible');
  },
  expand: function() {
    this.removeClass('collapsed');
    this.scrollToBottom();
  },
  collapse: function() {
    this.addClass('collapsed');
  },
  toggle: function() {
    this.toggleClass('collapsed');
    this.scrollToBottom();
  },
  getTypedMessage: function() {
    return this.find('textarea').val();
  },
  clearTypedMessage: function() {
    this.find('textarea').val('');
    this.find('textarea').focus();
  },
  sendTypedMessage: function() {
    this.sendMessage(this.getTypedMessage());
    this.clearTypedMessage();
  },
  sendMessage: function(message) {
    $.ajax({
      url: '/chat/send',
      type: 'post',
      dataType: 'json',
      data: {to: this.otherUserId(), message: message},
      success: function(response) {
      }
    });
  }
});

$('.chatbox .header').entwine({
  onclick: function() {
    this.closest('.chatbox').toggle();
  }
});

$('.chatbox textarea').entwine({
  onkeypress: function(e) {
    if (e.which == 13) {  // enter key
      e.preventDefault();
      this.closest('.chatbox').sendTypedMessage();
    }
  }
});
