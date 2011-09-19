WhoWentOut.Model.extend('WhoWentOut.Application', {}, {
    init: function() {
        this._super();

        if (!window.console)
            window.console = { log: function() {} };

        this.load();
        $.when(this.load()).then(this.callback('onload'));
    },
    onload: function() {
        this.initChatbar();
    },
    load: function() {
        var self = this;

        if (this._loadDfd)
            return this._loadDfd;

        this._loadDfd = $.Deferred();

        $.ajax({
            url: '/js/app',
            type: 'post',
            dataType: 'json',
            data: { user_ids: this.userIdsOnPage(), party_ids: this.partyIdsOnPage() },
            success: function(response) {
                console.log(response);

                _.each(response.application, function(v, k) {
                    self.set(k, v);
                });

                self.loadCollege(response.college);
                self.loadUsers(response.users);
                self.loadChannels(response.channels);

                self._loadDfd.resolve();
            }
        });
        return this._loadDfd.promise();
    },
    loadCollege: function(collegeJson) {
        this._college = WhoWentOut.College.FromJson(collegeJson);
    },
    loadUsers: function(users) {
        if (users) {
            _.each(users, function(userJson) {
                WhoWentOut.User.add(userJson);
            });
        }
    },
    loadChannels: function(channels) {
        if (channels) {
            this._channels = {};
            var curChannel = null;
            _.each(channels, function(channelConfig, k) {
                curChannel = WhoWentOut.Channel.Create(channelConfig);
                this._channels[ k ] = curChannel;
            }, this);
        }
    },
    channel: function(id) {
        return this._channels[id];
    },
    userIdsOnPage: function() {
        var ids = [];
        $('.user').each(function() {
            ids.push($(this).attr('data-user-id'));
        });
        return _.uniq(ids);
    },
    partyIdsOnPage: function() {
        var ids = [];
        $('.party').each(function() {
            ids.push($(this).attr('data-party-id'));
        });
        return _.uniq(ids);
    },
    college: function() {
        return this._college;
    },
    currentUserID: function() {
        return this.get('currentUserID');
    },
    currentUser: function() {
        return WhoWentOut.User.get(this.currentUserID());
    },
    initChatbar: function() {
        $('body').append('<div id="chatbar" />');
    }
});

window.app = new WhoWentOut.Application();
