$.when(window.app.load()).then(function() {

    app.channel('current_user')
    .bind('chat_received', function(e) {
        $.when($('#chatbar').loadMessages())
        .then(function() {
            $('#chatbar').addNewMessage(e.message);
            $('#chatbar').chatboxForMessage(e.message).show().scrollToBottom();
        });
    })
    .bind('chat_sent', function(e) {
        $.when($('#chatbar').loadMessages())
        .then(function() {
            $('#chatbar').addNewMessage(e.message);
        });
    });

    $(window).bind('beforeunload', function() {
        // if the chatbar hasn't restored a state yet it might be too early to do anything
        if ($('#chatbar').alreadyRestoredSavedState())
            $('#chatbar').saveState();
    });
    
    $('#chatbar').entwine({
        loadMessages: function() {
            var self = this;

            if (this.alreadyLoadedMessages()) //already loaded messages
                return null;

            var dfd = $.Deferred();
            $.ajax({
                url: '/chat/messages',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    $.each(response.messages, function(key, msg) {
                        self.addNewMessage(msg);
                    });

                    self.restoreSavedState();
                    self.data('alreadyLoadedMessages', true);
                    dfd.resolve(response.messages);
                }
            });
            return dfd.promise();
        },
        onmatch: function() {
            this._super();
            this.loadMessages();
        },
        onunmatch: function() {
            this._super();
        },
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
                    $(this).state(state[ $(this).attr('to') ]);
                });
                return this;
            }
        },
        alreadyLoadedMessages: function() {
            return !!this.data('alreadyLoadedMessages');
        },
        saveState: function() {
            $.jStorage.set('chatbarstate', this.state());
            $.ajax({
                url: '/chat/save_chatbar_state',
                type: 'post',
                data: { chatbar_state: this.state() },
                async: false, //async false so the browser stays open during the request
                success: function(response) {
                }
            });
            return this;
        },
        getSavedState: function() {
            if ($('#wwo').data('chatbar_state') != null) {
                $.jStorage.set('chatbarstate', $('#wwo').data('chatbar_state'));
            }
            return $.jStorage.get('chatbarstate', {});
        },
        alreadyRestoredSavedState: function() {
            return !!this.data('alreadyRestoredSavedState');
        },
        restoreSavedState: function() {
            if (this.alreadyRestoredSavedState())
                return this;

            this.state(this.getSavedState());
            this.data('alreadyRestoredSavedState', true);
            return this;
        },
        addChatbox: function(to) {
            var chatbox = $('<div/>');
            var currentUserID = app.currentUserID();

            chatbox.attr('from', currentUserID);
            chatbox.attr('to', to);
            chatbox.addClass('user').attr('data-user-id', to).addClass('user_' + to);
            chatbox.append('<div class="header"/>').find('.header')
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
        addNewMessage: function(message) {
            var chatbox = this.chatboxForMessage(message, true);
            chatbox.addMessage(message);
        },
        chatboxForMessage: function(message, create) {
            var otherUserID = message.sender_id == app.currentUserID()
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
            this._super();

            this.refreshTitle()
            .refreshUnreadCount();
        },
        onunmatch: function() {
            this._super();
        },
        fromUserID: function() {
            return this.attr('from');
        },
        toUserID: function() {
            return this.attr('to');
        },
        //This may be a deferred object so be sure to use $.when to get the result
        fromUser: function() {
            return user(this.fromUserID());
        },
        //This may be a deferred object so be sure to use $.when to get the result
        toUser: function() {
            return user(this.toUserID());
        },
        state: function(state) {
            if (state === undefined) {
                if (! this.is(':visible')) {
                    return 'hidden';
                }
                else if (this.isExpanded()) {
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
            return this;
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
        talkingToSelf: function() {
            return this.attr('from') == this.attr('to');
        },
        refreshTitle: function() {
            var self = this;
            $.when(this.toUser()).then(function(u) {
                self.title(u.fullName());
            });

            if (this.talkingToSelf())
                this.notice('Do you like talking to yourself?');

            return this;
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
                }
            });
            this.find('.chat_message.unread').removeClass('unread');
            this.refreshUnreadCount();

            return this;
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
            return this;
        },
        lastMessage: function() {
            return this.find('.chat_message.normal:last');
        },
        messageWasSentHere: function(message) {
            return message.receiver_id == app.currentUserID();
        },
        addMessage: function(message) {
            var self = this;

            var msgEl = $('<li/>');
            msgEl.attr('from', message.sender_id).attr('to', message.receiver_id);
            msgEl.append('<div class="message_sender"></div>');
            msgEl.append('<div class="message_body">' + message.message + '</div>');
            msgEl.append('<div class="message_time">' + this.formatSentAt(message) + '</div>');
            msgEl.addClass(message.type);
            msgEl.data('message', message);
            msgEl.addClass('chat_message');

            if (this.lastMessage().attr('from') == msgEl.attr('from'))
                msgEl.find('.chat_sender').hide();

            if (this.messageWasSentHere(message) && message.is_read == 0)
                msgEl.addClass('unread');

            $.when(user(message.sender_id), user(message.receiver_id)).then(function(sender, receiver) {
                msgEl.find('.message_sender').text(sender.get('first_name'));
                self.find('.chat_messages').append(msgEl);
                self.scrollToBottom().refreshUnreadCount();
            });

            return this;
        },
        formatSentAt: function(message) {
            var sentAt = new Date(message.sent_at * 1000);
            return 'sent on ' + sentAt.format('mmmm dS') + ' at ' + sentAt.format('h:MM tt');
        },
        scrollToBottom: function() {
            var messagesEl = this.find('.chat_messages');
            var scrollHeight = messagesEl.get(0).scrollHeight;
            messagesEl.scrollTop(scrollHeight);
            return this;
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
            var self = this;
            $.ajax({
                url: '/chat/send',
                type: 'post',
                dataType: 'json',
                data: {to: this.toUserID(), message: message},
                success: function(response) {
                    //If the message failed to send, we will get back an error message
                    if (response.success == false) {
                        self.notice(response.message);
                    }
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
            $('#chatbar').chatbox(to, true).show().expand().setFocus();
        }
    });

});
