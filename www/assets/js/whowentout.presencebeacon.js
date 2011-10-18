//= require lib/underscore.js
//= require whowentout.component.js
//= require whowentout.channel.js

WhoWentOut.Component.extend('WhoWentOut.PresenceBeacon', {
    _onlineUsers: {},
    _presenceChannels: {},
    _channelNames: [],
    init: function(channelNames) {
        this._super();
        this._isOnline = false;

        this._channelNames = channelNames;
    },
    userIsOnline: function(user_id) {
        return this._onlineUsers[user_id] == true;
    },
    userIsOffline: function(user_id) {
        return ! this.userIsOnline(user_id);
    },
    getOnlineUserIDs: function() {
        return _.keys(this._onlineUsers);
    },
    createPresenceChannel: function(channelName) {
        var self = this;
        
        var channel = this.pusher().subscribe(channelName);
        
        channel.bind('pusher:subscription_succeeded', function(members) {
            console.log('--subscribed--');
            members.each(function(member) {
                console.log(member);
                self._onlineUsers[member.id] = true;
            });
            self.trigger('load');
        });
        
        channel.bind('pusher:member_added', function(member) {
            if (self.userIsOnline(member.id)) //user is already marked as online
                return;

            console.log('--member added--');
            console.log(member);
            self._onlineUsers[member.id] = true;
            self.trigger({type: 'user_came_online', user_id: member.id});
        });
        
        channel.bind('pusher:member_removed', function(member) {
            if (self.userIsOffline(member.id)) //user is already marked as offline
                return;

            console.log('--member removed--');
            console.log(member);
            delete self._onlineUsers[member.id];
            self.trigger({type: 'user_went_offline', user_id: member.id});
        });

        this._presenceChannels[channelName] = channel;

        return channel;
    },
    destroyPresenceChannel: function(channelName) {
        this.pusher().unsubscribe(channelName);
        delete this._presenceChannels[channelName];
    },
    goOnline: function() {
        var self = this;
        
        if (this._isOnline) //already online
            return;

        $.each(this.channelNames(), function(k, channelName) {
            self.createPresenceChannel(channelName);
        });

        this._isOnline = true;
    },
    goOffline: function() {
        if (! this._isOnline) //already offline
            return;
        
        var onlineUserIDs = this.getOnlineUserIDs();
        this._onlineUsers = {};
        
        console.log('--online user ids--');
        console.log(onlineUserIDs);
        
        for (var i = 0; i < onlineUserIDs.length; i++) {
            this.trigger({ type: 'user_went_offline', user_id: onlineUserIDs[i] });
        }
        
        $.each(this.channelNames(), function(k, channelName) {
            self.destroyPresenceChannel();
        });

        this._isOnline = false;
    },
    pusher: function() {
        return WhoWentOut.PusherChannel.Pusher();
    },
    channelNames: function() {
        return this._channelNames;
    }
});
