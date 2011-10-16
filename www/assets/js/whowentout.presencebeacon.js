//= require lib/underscore.js
//= require whowentout.component.js
//= require whowentout.channel.js

WhoWentOut.Component.extend('WhoWentOut.PresenceBeacon', {
    _onlineUsers: {},
    init: function() {
        this._super();
        
        this._isOnline = false;
    },
    userIsOnline: function(user_id) {
        return this._onlineUsers[user_id] == true;
    },
    getOnlineUserIDs: function() {
        return _.keys(this._onlineUsers);
    },
    goOnline: function() {
        var self = this;

        if ( this._isOnline) //already online
            return;

        this._onlineUsers = {};
        this._presenceChannel = this.pusher().subscribe(this.channelName());
        this._presenceChannel.bind('pusher:subscription_succeeded', function(members) {
            console.log('--subscribed--');
            members.each(function(member) {
                console.log(member);
                self._onlineUsers[member.id] = true;
            });
            self.trigger('load');
        });

        this._presenceChannel.bind('pusher:member_added', function(member) {
            console.log('--member added--');
            console.log(member);
            self._onlineUsers[member.id] = true;
            self.trigger({type: 'user_came_online', user_id: member.id});
        });

        this._presenceChannel.bind('pusher:member_removed', function(member) {
            console.log('--member removed--');
            console.log(member);
            delete self._onlineUsers[member.id];
            self.trigger({type: 'user_went_offline', user_id: member.id});
        });

        this._isOnline = true;
    },
    goOffline: function() {
        if ( ! this._isOnline) //already offline
            return;

        var onlineUserIDs = this.getOnlineUserIDs();
        this._onlineUsers = {};
        
        console.log('--online user ids--');
        console.log(onlineUserIDs);
        
        for (var i = 0; i < onlineUserIDs.length; i++) {
            this.trigger({ type: 'user_went_offline', user_id: onlineUserIDs[i] });
        }
        this.pusher().unsubscribe(this.channelName());

        this._isOnline = false;
    },
    pusher: function() {
        return WhoWentOut.PusherChannel.Pusher();
    },
    channelName: function() {
        return 'presence-whowentout_development';
    }
});
