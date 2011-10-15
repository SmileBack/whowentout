//= require lib/underscore.js
//= require whowentout.component.js
//= require whowentout.channel.js

WhoWentOut.Component.extend('WhoWentOut.PresenceBeacon', {
    init: function() {
        this._super();
        var self = this;
        
        var pusher = WhoWentOut.PusherChannel.Pusher();
        
        this._onlineUsers = {};
        this._presenceChannel = pusher.subscribe('presence-whowentout_development');
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
    },
    userIsOnline: function(user_id) {
        return this._onlineUsers[user_id] == true;
    },
    getOnlineUserIDs: function() {
        return _.keys(this._onlineUsers);
    }
});
