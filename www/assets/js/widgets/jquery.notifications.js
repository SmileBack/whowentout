$('#notifications').entwine({
    onmatch: function() {
        this._super();
        this.loadNotifications();
    },
    onunmatch: function() {
        this._super();
    },
    addNotification: function(notification) {
        var el = this.buildNotification(notification);
        this.prepend(el);
        el.fadeIn(300);
    },
    buildNotification: function(notification) {
        var el = $('<li/>');
        el.data('object', notification);
        el.append('<div class="notification_message"/>');
        el.find('.notification_message').append(notification.message);
        el.hide();
        el.addClass('notification');
        return el;
    },
    loadNotifications: function() {
        var self = this;
        $.ajax({
            url: '/notification/unread',
            type: 'get',
            dataType: 'json',
            success: function(response) {
                for (var i = 0; i < response.notifications.length; i++) {
                    self.addNotification(response.notifications[i]);
                }
                self.listenForNotifications();
            }
        });
    },
    listenForNotifications: function() {
        var self = this;
        $.when(app.load()).then(function() {
            app.channel('current_user').bind('notification', function(e) {
                self.addNotification(e.notification);
                app.playSound('ding');
            });
        });
    }
});

$('#notifications .notification').entwine({
    onmatch: function() {
        this._super();
        this.createCloseLink();
    },
    onunmatch: function() {
        this._super();
    },
    createCloseLink: function() {
        this.append('<a class="notification_close" href="#close">×</a>');
    }
});

$('#notifications .notification').entwine({
    onclose: function() {
        this._super();
        var self = this;
        this.markAsRead().success(function() {
            self.remove();
        });
    },
    object: function() {
        return this.data('object');
    },
    close: function() {
        var self = this;
        this.fadeOut(300, function() {
            self.trigger('close');
        });
    },
    markAsRead: function() {
        return $.ajax({
            url: '/notification/mark_as_read/' + this.object().id,
            type: 'get',
            dataType: 'json'
        });
    }
});

$('#notifications .notification_close').entwine({
    onclick: function(e) {
        e.preventDefault();
        this.closest('.notification').close();
    }
});

