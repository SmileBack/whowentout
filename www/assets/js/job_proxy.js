//= require lib/jquery.js
//= require lib/jquery.entwine.js
//= require whowentout.channel.js
//= require whowentout.queue.js
//= require whowentout.component.js
//= require whowentout.presencebeacon.js

WhoWentOut.Component.extend('WhoWentOut.JobProxy', {
    _options: null,
    _queue: null,
    _jobChannel: null,
    beacon: null,
    init: function(options) {
        this._options = options;

        WhoWentOut.Pusher.PusherAuthEndpoint = this._options.pusherAuthEndpoint;
        this.initPresenceBeacon();
    },
    initPresenceBeacon: function() {
        this.beacon = new WhoWentOut.PresenceBeacon([this._options.presenceChannel]);
        this.beacon.goOnline();
    },
    initJobQueue: function() {
        this._jobChannel = WhoWentOut.Channel.Create({
            type: 'PusherChannel',
            id: this._options.channel
        });
        this._queue = new WhoWentOut.Queue();
        this._jobChannel.bind('new_job', this.callback('_onNewJob'));
    },
    _onNewJob: function(e) {
        this.trigger({
            type: 'jobreceived',
            url: e.url
        });

        console.log('got job request :: ' + e.url);
        this._queue.add(this.callback('_sendJobRequestTask'), {
            url: e.url
        });
    },
    _sendJobRequestTask: function (options) {
        var self = this;
        this.trigger({
            type: 'jobbeginsend',
            url: options.url
        });
        console.log('sending job request :: ' + options.url);
        return $.ajax({
            url: options.url,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                console.log('successfully executed job ::');
                self.trigger({
                    type: 'jobran',
                    job: response.job
                });
            }
        });
    }
});

$('.online_list').entwine({
    addUser: function(id) {
        var li = $('<li/>');
        li.attr('id', 'user_' + id);
        li.text(this.formatUser(id));
        this.append(li);
    },
    removeUser: function(id) {
        this.find('#user_' + id).remove();
    },
    formatUser: function(id) {
        var date = new Date(parseInt(id) * 1000);
        return id + ' :: ' + date.toString();
    }
});

jQuery(function($) {
    window.jobProxy = new WhoWentOut.JobProxy({
        pusherAuthEndpoint: '/job_proxy/pusherauth',
        channel: window.settings.job_proxy.channel,
        presenceChannel: window.settings.job_proxy.presence_channel
    });

    setInterval(function() {
        var state = WhoWentOut.Pusher.Get().connection.state;
        $('.pusher_status').text(state);
        $('.time').text( (new Date()).toString() );
    }, 1000);

    window.jobProxy.beacon.bind('load', function(e) {
        var ids = this.getOnlineUserIDs();
        for (var k = 0; k < ids.length; k++) {
            $('.online_list').addUser(ids[k]);
        }
    });

    window.jobProxy.beacon.bind('user_came_online', function(e) {
        $('.online_list').addUser(e.user_id);
    });

    window.jobProxy.beacon.bind('user_went_offline', function(e) {
        $('.online_list').removeUser(e.user_id);
    });

    window.jobProxy.bind('jobran', function() {
        $('.job_count').text( parseInt($('.job_count').text()) + 1 );
    });
});

window.start = function() {
    window.jobProxy.initJobQueue();
};
