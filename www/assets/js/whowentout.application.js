//= require lib/jquery.js
//= require lib/jquery.entwine.js
//= require lib/underscore.js

//= require whowentout.model.js
//= require whowentout.channel.js
//= require whowentout.college.js
//= require whowentout.place.js
//= require whowentout.party.js
//= require whowentout.user.js

//= require whowentout.presencebeacon.js
//= require whowentout.feedback.js
//= require whowentout.userlogger.js

//= require whowentout.dialog.js

//= require widgets/jquery.countdowntimer.js

//= require lib/soundmanager2.config.js
//= require lib/getflashplayerversion.js

WhoWentOut.Model.extend('WhoWentOut.Application', {
    Mask: function() {
        if ($('#mask').length == 0) {
            $('body').append('<div id="#mask" />"');
        }
        return $('#mask');
    }
}, {
    init: function() {
        this._super();
        var self = this;

        if (!window.console)
            window.console = { log: function() {
            } };

        this.load();

        $.when(this.load()).then(this.callback('onload'));
        $.when(this.load()).then(this.callback('initPresenceBeacon'));
    },
    onload: function() {
        var self = this;
        this.initChatbar();
    },
    getPresenceBeacon: function() {
        return this._presenceBeacon;
    },
    initPresenceBeacon: function() {
        this._presenceBeacon = new WhoWentOut.PresenceBeacon(this._config.presence_channels);

        if (this.currentUser().visibleTo() == 'online')
            this._presenceBeacon.goOnline();

        this._presenceBeacon.bind('load', function() {
            var onlineUserIDs = this.getOnlineUserIDs();
            for (var i = 0; i < onlineUserIDs.length; i++) {
                $('.user_' + onlineUserIDs[i]).addClass('online');
            }
        });

        this._presenceBeacon.bind('user_came_online', function(e) {
            console.log('user ' + e.user_id + ' went online');
            $('.user_' + e.user_id).addClass('online');
        });

        this._presenceBeacon.bind('user_went_offline', function(e) {
            console.log('user ' + e.user_id + ' went offline');
            console.log($('.user_' + e.user_id).length);
            $('.user_' + e.user_id).removeClass('online');
        });

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
                self._config = response;

                _.each(response.application, function(v, k) {
                    self.set(k, v);
                });

                self.loadCollege(response.college);
                self.loadUsers(response.users);
                self.loadChannels(response.channels);

                self._presenceToken = response.presence_token;

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
                if (!this._channels[ k ]) {
                    curChannel = WhoWentOut.Channel.Create(channelConfig);
                    this._channels[ k ] = curChannel;
                }
            }, this);
        }
    },
    loadSounds: function() {
        var self = this;
        self._sounds = {};
        soundManager.onready(function() {
            self._sounds['ding'] = soundManager.createSound({
                id: 'dingSound',
                url: '/assets/sounds/ding.mp3',
                autoLoad: true,
                autoPlay: false,
                volume: 50
            });
            self._sounds['boop'] = soundManager.createSound({
                id: 'boopSound',
                url: '/assets/sounds/boop.mp3',
                autoLoad: true,
                autoPlay: false,
                volume: 100
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
        return window.settings.current_user_id;
    },
    currentUser: function() {
        return WhoWentOut.User.get(this.currentUserID());
    },
    initChatbar: function() {
        $('body').append('<div id="chatbar" />');
    },
    playSound: function(name) {
        name = name || 'ding';
        this._sounds[name].play();
    },
    showSmileHelp: function() {
        $('.see_smile_help_tip').hideTip();
        WhoWentOut.Dialog.Show({
            title: 'What is a Smile?',
            buttons: 'close',
            cls: 'smile_help',
            url: '/help/smile'
        });
    },
    showHowItWorksHelp: function() {
        WhoWentOut.Dialog.Show({
            title: 'How WhoWentOut Works',
            buttons: 'close',
            cls: 'how_whowentout_works',
            url: '/help/howitworks'
        });
    },
    showMutualFriendsDialog: function(path) {
        WhoWentOut.Dialog.Show({
            title: 'Mutual Friends',
            buttons: 'close',
            cls: 'mutual_friends_dialog',
            url: path,
            onload: function() {
                var dialog = WhoWentOut.Dialog.Get();
                var count = dialog.find('.mutual_friends').attr('count') || 0;
                dialog.title(dialog.title() + ' (' + count + ')');
                dialog.refreshPosition();
            }
        });
    },
    _fetchUsers: function(userIds) {
        var dfd = $.Deferred();
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '/js/users',
            data: {user_ids: userIds},
            success: function(response) {
                dfd.resolve(response.users);
            }
        });
        return dfd.promise();
    },
    _cancelEvery: function(id) {
        clearInterval(id);
    },
    _every: function(seconds, fn) {
        return setInterval(fn, seconds * 1000);
    }
});

window.app = new WhoWentOut.Application();
window.userLogger = new WhoWentOut.UserLogger();
