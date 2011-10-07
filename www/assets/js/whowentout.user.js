//= require lib/jquery.js
//= require WhoWentOut.Model.js
//= require WhoWentOut.Hash.js

WhoWentOut.Model.extend('WhoWentOut.User', {
    get: function(id) {

        if (id === undefined) {
            alert('aaaa');
        }

        var self = this;

        if (!this._users)
            this._users = new WhoWentOut.Hash();

        if (!this._users.contains(id)) {
            this._users.set(id, this.fetchFromServer(id));
        }

        return this._users.get(id);
    },
    all: function() {
        if (!this._users)
            this._users = new WhoWentOut.Hash();

        return this._users;
    },
    fetchFromServer: function(id) {
        if (this._users.get(id))
            return this._users.get(id);

        console.log('--fetching ' + id + ' from server--');

        var self = this;
        var dfd = $.Deferred();
        $.ajax({
            url: '/js/user/' + id,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    self.add(response.user);
                    dfd.resolve(self.get(id));
                }
                else {
                    dfd.reject();
                }
            }
        });
        return dfd.promise();
    },
    add: function(userJson) {
        if (!this._users)
            this._users = new WhoWentOut.Hash();

        var user = new WhoWentOut.User(userJson);

        this._users.set(user.get('id'), user);
    }
}, {
    init: function(attrs) {
        this._super(attrs);
    },
    firstName: function() {
        return this.get('first_name');
    },
    lastName: function() {
        return this.get('last_name');
    },
    fullName: function() {
        return this.firstName() + ' ' + this.lastName();
    },
    isOnline: function(v) {
        return this.val.call(this, 'is_online', v);
    },
    isIdle: function(v) {
        return this.val.call(this, 'is_idle', v);
    },
    visibleTo: function() {
        return this.get('visible_to');
    },
    thumbUrl: function() {
        return this.get('thumb_url');
    },
    otherGender: function() {
        return this.get('other_gender');
    }
});

$('.user').entwine({
    onmatch: function() {
        this._super();

        var self = this;
        $.when(app.load()).then(function() {
            $.when(WhoWentOut.User.get( self.userID() )).then(function(u) {
                if (u.isOnline()) {
                    self.addClass('online');
                }
                if (u.isIdle()) {
                    self.addClass('idle');
                }
            });
        });
    },
    onunmatch: function() {
        this._super();
    },
    userID: function() {
        return parseInt(this.attr('data-user-id'));
    }
});

function user(id) {
    return WhoWentOut.User.get(id);
}
