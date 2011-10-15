//= require whowentout.component.js

WhoWentOut.Component.extend('WhoWentOut.API.Chat', {
    get: function() {
        if (!this._api)
            this._api = new WhoWentOut.API.Chat();

        return this._api;
    }
}, {
    init: function() {
        this._super();
    },
    sendMessage: function(params) {
        var dfd = $.Deferred();

        return $.ajax({
            url: '/chat/send',
            type: 'post',
            dataType: 'json',
            data: {to: params.to_user_id, message: params.message},
            success: function(response) {
                //If the message failed to send, we will get back an error message
                if (response.success == false) {
                    dfd.reject(response);
                }
                else {
                    dfd.resolve(response);
                }
            }
        });

        return dfd.promise();
    },
    loadMessages: function(params) {
        var self = this;

        if (this._alreadyLoadedMessages) //already loaded messages
            return null;

        var dfd = $.Deferred();
        $.ajax({
            url: '/chat/messages',
            type: 'post',
            dataType: 'json',
            data: {state: params.chatbar_state},
            success: function(response) {
                self._alreadyLoadedMessages = true;
                dfd.resolve(response.messages);
            }
        });

        return dfd.promise();
    },
    markAsRead: function(params) {
        return $.ajax({
            url: '/chat/mark_read',
            type: 'post',
            data: {from: params.chatbox_user_id},
            success: function(response) {
            }
        });
    }
});
