$('.chat.serverinbox').live('newdata', function(e, version) {
  console.log('---');
  console.log('chat version changed!');
  console.log('chat old version = ' + $('#chatbar').version());
  console.log('chat version = ' + version);
  $('#chatbar').checkForNewMessages();
});

jQuery(function() {
  $('#chatbar').checkForNewMessages();
});
$(window).bind('beforeunload', function() {
  $('#chatbar').saveState();
});

$('#chatbar').entwine({
  onmatch: function() {},
  onunmatch: function() {},
  state: function(state) {
    if (state === undefined) {
      state = {};
      this.find('.chatbox').each(function() {
        state[ $(this).attr('to') ] = $(this).state();
      });
      return state;
    }
    else {
      this.find('.chatbox').each(function() {
        $(this).state( state[ $(this).attr('to') ] );
      });
      return this;
    }
  },
  saveState: function() {
    $.jStorage.set('chatbarstate', this.state());
    $.ajax({
      url: '/chat/save_chatbar_state',
      type: 'post',
      data: { chatbar_state: this.state() },
      async: false,
      success: function(response) {
      }
    });
    return this;
  },
  getSavedState: function() {
    if ( $('#wwo').data('chatbar_state') != null ) {
      $.jStorage.set('chatbarstate', $('#wwo').data('chatbar_state') );
    }
    return $.jStorage.get('chatbarstate', {});
  },
  restoreSavedState: function() {
    if (this.data('restoredSavedState') == true)
      return this;
    
    this.state(this.getSavedState());
    this.data('restoredSavedState', true);
    return this;
  },
  addChatbox: function(to) {
    var chatbox = $('<div/>').attr('from', current_user().id).attr('to', to)
                    .append('<div class="header"/>').find('.header')
                      .append('<h3/>')
                      .append('<div class="online_badge"></div>')
                      .append('<a class="chatbox_close">×</a>')
                    .end()
                    .append('<div class="unread_count"/>')
                    .append('<div class="body"/>').find('.body')
                      .append('<div class="message"/>')
                      .append('<ul class="chat_messages"/>')
                      .append('<div class="input"/>').find('.input')
                        .append('<textarea/>')
                      .end()
                    .end()
                    .addClass('chatbox');
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
        self.updateUsers(response.messages);
        self.populateNewMessages(response.messages, response.version);
      }
    });
  },
  updateUsers: function(messages) {
    for (var k in messages) {
      if ( ! user(messages[k].sender.id) )
        user(messages[k].sender.id, messages[k].sender)
      
      if ( ! user(messages[k].receiver.id) )
        user(messages[k].receiver.id, messages[k].receiver)
    }
  },
  populateNewMessages: function(messages, newVersion) {
    for (var k in messages) {
      this.insertNewMessage(messages[k]);
    }
    this.data('version', newVersion);
    this.restoreSavedState();
  },
  insertNewMessage: function(message) {
    this.chatboxForMessage(message, true).addMessage(message);
  },
  chatboxForMessage: function(message, create) {
    var otherUserID = message.sender_id == current_user().id
                    ? message.receiver_id : message.sender_id;
    var chatbox = this.chatbox(otherUserID, create);
    return chatbox;
  },
  chatboxPresent: function(user_id) {
    return this.chatbox(user_id).length > 0;
  },
  chatbox: function(user_id, create) {
    var chatbox = this.find('.chatbox[to=' + user_id + ']');
    if (chatbox.length == 0 && create == true) {
      chatbox = this.addChatbox(user_id);
    }
     
    return chatbox;
  }
});

$('.chatbox').entwine({
  onmatch: function() {
    this.refreshTitle().refreshUnreadCount();
  },
  onunmatch: function() {},
  state: function(state) {
    if (state === undefined) {
      if ( ! this.is(':visible') ) {
        return 'hidden';
      }
      else if ( this.isExpanded() ) {
        return 'expanded';
      }
      else {
        return 'collapsed';
      }
    }
    else {
      if (state == 'hidden') {
        this.hide();
      }
      else if (state == 'expanded') {
        this.show().expand();
      }
      else if (state == 'collapsed') {
        this.show().collapse();
      }
    }
  },
  close: function() {
    this.fadeOut(300);
    return this;
  },
  notice: function(message) {
    this.find('.message').html(message);
    if (message == null || message == '')
      this.find('.message').hide();
    else
      this.find('.message').show();
  },
  title: function(title) {
    if (title === undefined) {
      return this.find('h3').html();
    }
    else {
      this.find('h3').html(title);
      return this;
    }
  },
  refreshTitle: function() {
    var otherUserID = this.otherUserID();
    var u = user(otherUserID);
    this.title(u.first_name + ' ' + u.last_name);
    
    if (this.attr('from') == this.attr('to'))
      this.notice('Do you like talking to yourself?');
    
    return this;
  },
  otherUserID: function() {
    return this.attr('to');
  },
  unreadCount: function() {
    return this.find('.chat_message.normal.unread').length;
  },
  markAsRead: function() {
    $.ajax({
      url: '/chat/mark_read',
      type: 'post',
      data: {from: this.attr('to')},
      success: function(response) {
        console.log('marked as readd');
      }
    });
    this.find('.chat_message.unread').removeClass('unread');
    this.refreshUnreadCount();
  },
  refreshUnreadCount: function() {
    var count = this.unreadCount();
    var badge = this.find('.unread_count');
    badge.text(count);
    if (count == 0) {
      badge.addClass('empty');
    }
    else {
      badge.removeClass('empty');
    }
  },
  lastMessage: function() {
    return this.find('.chat_message:last');
  },
  addMessage: function(message) {
    var msgEl = $('<li class="chat_message"/>');
    
    msgEl.attr('from', message.sender.id).attr('to', message.receiver.id);
    msgEl.append('<div class="chat_sender">' + message.sender.first_name + '</div>');
    msgEl.append('<div class="chat_message_body">' + message.message + '</div>');
    msgEl.addClass(message.type);
    
    if (message.receiver_id == current_user().id && message.is_read == 0) {
      msgEl.addClass('unread');
    }
    
    msgEl.data('message', message);
    
    if (this.lastMessage().attr('from') == msgEl.attr('from'))
      msgEl.find('.chat_sender').hide();
    
    if (message.type == 'notice')
      msgEl.hide();
    
    if (message.type == 'notice' && message.message == 'online') {
      this.addClass('online');
    }
    if (message.type == 'notice' && message.message == 'offline') {
      this.removeClass('online');
    }
    
    this.find('.chat_messages').append(msgEl);
    this.scrollToBottom();
    this.refreshUnreadCount();
    return this;
  },
  scrollToBottom: function() {
    var messagesEl = this.find('.chat_messages');
    var scrollHeight = messagesEl.get(0).scrollHeight;
    messagesEl.scrollTop(scrollHeight);
  },
  isExpanded: function() {
    return this.find('.body').is(':visible');
  },
  expand: function() {
    this.removeClass('collapsed');
    this.scrollToBottom();
    this.find('textarea');
    return this;
  },
  setFocus: function() {
    this.find('textarea').focus();
    return this;
  },
  collapse: function() {
    this.addClass('collapsed');
    return this;
  },
  toggle: function() {
    if (this.isExpanded()) {
      this.collapse();
    }
    else {
      this.expand();
    }
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
      data: {to: this.otherUserID(), message: message},
      success: function(response) {
      }
    });
  }
});

$('.chatbox .header').entwine({
  onclick: function() {
    var chatbox = this.closest('.chatbox');
    chatbox.toggle();
    if (chatbox.isExpanded()) {
      chatbox.setFocus();
    }
  }
});

$('.chatbox .chatbox_close').entwine({
  onclick: function(e) {
    e.preventDefault();
    e.stopPropagation();
    this.closest('.chatbox').close();
  }
});

$('.chatbox textarea').entwine({
  onkeypress: function(e) {
    if (e.which == 13) {  // enter key
      e.preventDefault();
      this.closest('.chatbox').sendTypedMessage();
    }
  },
  onfocusin: function(e) {
    this.closest('.chatbox').markAsRead();
  }
});

$('.open_chat').entwine({
  onclick: function(e) {
    e.preventDefault();
    var to = this.attr('to');
    $('#chatbar').chatbox(to, true).expand().setFocus();
  }
});
