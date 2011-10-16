//= require whowentout.component.js
//= require whowentout.queue.js

WhoWentOut.Component.extend('WhoWentOut.Channel', {
    Create: function(options) {
        var className = options.type;
        var cls = WhoWentOut[className];
        return new cls(options);
    }
}, {
    init: function(options) {
        this._super();
        this._options = _.defaults(options, {});
    },
    id: function() {
        return this._options.id;
    }
});

WhoWentOut.Channel.extend('WhoWentOut.PusherChannel', {
    Pusher: function() {
        if (!this._pusher) {
            this._pusher = new Pusher('23a32666914116c9b891');
            Pusher.channel_auth_endpoint = '/user/pusherauth';
            //Pusher.log = function(msg) { window.console.log(msg); }
        }
        return this._pusher;
    }
}, {
    init: function(options) {
        this._super(options);
        this._options = _.defaults(this._options, {});
        this.openChannel();
    },
    openChannel: function() {
        if (!this._channel) {
            this._channel = this.Class.Pusher().subscribe(this.id());
        }
        this._channel.bind('eventreceived', this.callback('oneventreceived'));
        this._channel.bind('client-eventreceived', this.callback('onclienteventreceived'));
    },
    triggerClientEvent: function(event_name, event_data) {
        event_data.type = event_name;
        this._channel.trigger('client-eventreceived', event_data);
    },
    oneventreceived: function(event) {
        event.source  = 'server';
        console.log('event :: ' + event.type);
        console.log(event);
        this.trigger(event);
    },
    onclienteventreceived: function(event) {
        event.source = 'client';
        console.log('client event :: ' + event.type);
        console.log(event);
        this.trigger(event);
    }
});
