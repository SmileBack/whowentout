WhoWentOut.Model.extend('WhoWentOut.Application', {}, {
    init: function() {
        this._super();

        if (!window.console)
            window.console = { log: function() {
            } };

        this.load();

        $.when(this.load()).then(this.callback('onload'));
        $.when(this.load()).then(this.callback('initIdleEvents'));
    },
    onload: function() {
        var self = this;

        this.initChatbar();

        this.startPingingServer();

        this._every(10, function() {
            self.updateOfflineUsers();
        });

        $(window).bind('leave', function() {
            self.pingLeavingServer();
        });
    },
    updateOfflineUsers: function() {
        return $.getJSON('/college/update_offline_users');
    },
    startPingingServer: function() {
        if (this._pingingId) //already pinging
            return;

        this.pingServer();
        this._pingingId = this._every(5, this.callback('pingServer'));
    },
    stopPingingServer: function() {
        this._cancelEvery(this._pingingId);
        this._pingingId = null;
    },
    pingServer: function() {
        $.ajax({
            url: '/user/ping',
            type: 'get',
            data: { isIdle: this.isIdle() },
            success: function(response) {
                console.log('pinged server!');
            }
        });
    },
    pingIdle: function() {
        return $.getJSON('/user/ping_idle');
    },
    pingActive: function() {
        return $.getJSON('/user/ping_active');
    },
    pingLeavingServer: function() {
        $.ajax({
            url: '/user/ping_leaving',
            type: 'get',
            async: false,
            success: function(response) {
            }
        });
    },
    initIdleEvents: function() {
        var self = this;
        $(document.body).idleTimer(10000);
        $(document.body).bind("idle.idleTimer", function() {
            self.pingIdle();
            self.trigger('becameidle');
        });
        $(document.body).bind("active.idleTimer", function() {
            self.pingActive();
            self.trigger('becameactive');
        });
    },
    idleFor: function() {
        return this.isIdle() ? $(document.body).idleTimer('getElapsedTime') : 0;
    },
    isIdle: function() {
        return $.data(document.body, 'idleTimer') == 'idle';
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

        this.loadSounds();

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
    loadSounds: function() {
        var self = this;
        soundManager.onready(function() {
            self._dingSound = soundManager.createSound({
                id: 'dingSound',
                url: '/assets/sounds/ding.mp3',
                autoLoad: true,
                autoPlay: false,
                volume: 50
            });
        });
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
    },
    playSound: function() {
        this._dingSound.play();
    },
    _cancelEvery: function(id) {
        clearInterval(id);
    },
    _every: function(seconds, fn) {
        return setInterval(fn, seconds * 1000);
    }
})
;

window.app = new WhoWentOut.Application();
