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

        var self = this;
        this._isFetchingNewEvents = false;

        this.bind('eventsversionchanged', this.callback('oneventsversionchanged'));

        $.ajax({
            url: '/events/version/' + this.id(),
            type: 'get',
            dataType: 'json',
            success: function(response) {
                self._eventsVersion = response.version;
                self.openChannel();
            }
        });
    },
    oneventsversionchanged: function(e) {
        console.log('oneventsversionchanged');
        this.fetchNewEvents();
    },
    id: function() {
        return this._options.id;
    },
    eventsVersion: function(v) {
        if (v === undefined) {
            return this._eventsVersion;
        }
        else {
            this._eventsVersion = v;
            return this;
        }
    },
    isFetchingNewEvents: function() {
        return this._isFetchingNewEvents;
    },
    fetchNewEvents: function() {
        if (this.isFetchingNewEvents()) {
            return this;
        }

        var self = this;
        this._isFetchingNewEvents = true;

        $.ajax({
            url: '/events/fetch/' + this.id() + '/' + this.eventsVersion(),
            type: 'get',
            dataType: 'json',
            success: function(response) {
                self.eventsVersion(response.version);
                self.triggerServerEvents(response.events);
                self._isFetchingNewEvents = false;
            }
        });
        return this;
    },
    triggerServerEvents: function(events) {
        var self = this;
        var e;
        $.each(events, function(k, event) {
            self.trigger(event.type, event);
        });
    },
    openChannel: function() {
    }
});

WhoWentOut.Channel.extend('WhoWentOut.PollingChannel', {}, {
    init: function(options) {
        this._super(options);

        this._options = _.defaults(this._options, {
            frequency: 1,
            id: null,
            url: null
        });
    },
    openChannel: function() {
        var self = this;
        var id = this._every(this.frequency(), function() {
            self.checkIfEventsVersionChanged();
        });
        this._pollVersionId = id;

        return this;
    },
    checkIfEventsVersionChanged: function() {
        var timestamp = (new Date()).valueOf();
        var url = this.url();
        //each channel needs its own callback otherwise there may be race conditions
        //and multiple simultaneous ajax requests may step on each other
        var callback = 'json_' + url.substring(url.lastIndexOf('/') + 1);
        var self = this;
        $.ajax({
            type: 'get',
            url: url + '?timestamp=' + timestamp,
            dataType: 'jsonp',
            jsonp: false,
            jsonpCallback: callback,
            context: this,
            success: function(newVersion) {
                var currentVersion = self.eventsVersion();
                if (newVersion != currentVersion) {
                    self.trigger('eventsversionchanged');
                }
            }
        });
    },
    url: function() {
        return this._options.url;
    },
    _every: function(seconds, fn) {
        return setInterval(fn, seconds * 1000);
    },
    _cancelEvery: function(id) {
        clearInterval(id);
    },
    frequency: function() {
        return this._options.frequency;
    },
    stopChecking: function() {
        var id = this._pollVersionId;
        if (id)
            this._cancelEvery(id);

        return this;
    }
});

WhoWentOut.Channel.extend('WhoWentOut.PusherChannel', {
    Pusher: function() {
        if (!this._pusher) {
            this._pusher = new Pusher('23a32666914116c9b891');
        }
        return this._pusher;
    }
}, {
    init: function(options) {
        this._super(options);
        this._options = _.defaults(this._options, {
        });
    },
    openChannel: function() {
        if (!this._channel) {
            this._channel = this.Class.Pusher().subscribe(this.id());
        }
        this._channel.bind('datareceived', this.callback('ondatareceived'));
    },
    ondatareceived: function(version) {
        if (version != this.eventsVersion()) {
            this.trigger('eventsversionchanged');
        }
    }
});
