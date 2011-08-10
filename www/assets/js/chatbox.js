$('.chatbox').entwine({
  onmatch: function() {
    this.append('<ul/>');
    var self = this;
    
    self.updateMessages();
    every(3, function() {
      self.updateMessages();
    });
  },
  onunmatch: function() {},
  updateMessages: function() {
    $.ajax({
      url: '/chat/get',
      type: 'post',
      dataType: 'json',
      data: {user_id: this.userID()},
      success: function(response) {
        for (var k in response.messages) {
          this.appendMessage(response.messages[k]);
        }
        console.log(response);
      },
      context: this
    });
  },
  sendMessage: function(message) {
    $.ajax({
      url: '/chat/send',
      type: 'post',
      dataType: 'json',
      data: {user_id: this.userID(), message: message},
      success: function(response) {
        this.updateMessages();
        console.log(response);
      },
      context: this
    });
  },
  appendMessage: function(message) {
    if (this.hasMessage(message))
      return this;
    
    var el = $('<li/>');
    el.html('<em>' + this.getUserName(message.sender_id) + "</em>  said: <div>" + message.message + '</div>').attr('id', 'message_' + message.id);
    this.find('> ul').append(el);
    
    return this;
  },
  getUserName: function(user_id) {
    if (user_id == 159)
      return 'Venkat Dinavahi';
    if (user_id == 33)
      return 'Dan Berenholtz';
  },
  hasMessage: function(message) {
    return this.find('#message_' + message.id).length > 0;
  },
  userID: function() {
    return parseInt( this.attr('user') );
  }
});

$('.chatbox_send').entwine({
  onclick: function() {
    var message = this.chatinput().val();
    this.chatbox().sendMessage(message);
    this.chatinput().val('').focus();
  },
  chatinput: function() {
    return this.chatbox().find('.chatbox_message_value');
  },
  chatbox: function() {
    return this.closest('.chatbox');
  }
});
