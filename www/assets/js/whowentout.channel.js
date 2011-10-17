//= require whowentout.component.js
//= require whowentout.queue.js
//= require lib/getflashplayerversion.js

//= require widgets/jquery.dialog.js

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
            Pusher.channel_auth_endpoint = '/user/pusherauth';
            this._pusher = new Pusher('23a32666914116c9b891');
            this.CheckForFailedPusher();
        }
        return this._pusher;
    },
    EnablePusherLogging: function() {
        Pusher.log = function(msg) {
            window.console.log(msg);
        }
    },
    CheckForFailedPusher: function() {
        var self = this;
        if (this._pusher.connection.state == 'failed')
            this.OnPusherFail();
        else {
            this._pusher.connection.bind('state_change', function(state) {
                if (state.current == 'failed')
                    self.OnPusherFail();
            });
        }
    },
    OnPusherFail: function() {
        var version = getFlashPlayerVersion();
        if (!version) {
            var dialog = $.dialog.create({centerInViewport: true});
            $.dialog.hideMaskOnClick(false);
            
            dialog.title('Flash Player required.');
            dialog.message(
            '<p>Download Flash Player to use WhoWentOut.</p>'
            + '<p><a href="http://get.adobe.com/flashplayer/" target="_blank"><img src="/assets/images/get_flash_player_button.jpg" /></a></p>'
            );

            dialog.showDialog();
        }
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
        event.source = 'server';
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
