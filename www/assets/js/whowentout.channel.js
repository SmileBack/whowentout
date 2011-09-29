WhoWentOut.Component.extend('WhoWentOut.Channel', {
    Create: function(options) {
        var className = options.type;
        var cls = WhoWentOut[className];
        return new cls(options);
    }
}, {
    init: function(options) {
        var self = this;

        this._super();
        this._options = _.defaults(options, {});

        this._isFetchingNewEvents = false;
        this._queue = new WhoWentOut.Queue();
        this.bind('eventsversionchanged', this.callback('oneventsversionchanged'));

        this._queue.add(this.callback('initSourceEventsVersion'));
    },
    initSourceEventsVersion: function() {
        var self = this;
        return $.ajax({
            url: '/events/version/' + this.id(),
            type: 'get',
            dataType: 'json',
            success: function(response) {
                self.sourceEventsVersion(response.version);
                self.localEventsVersion(response.version);
                self.openChannel();
            }
        });
    },
    oneventsversionchanged: function(e) {
        this._queue.add( this.callback('fetchNewEvents') );
    },
    id: function() {
        return this._options.id;
    },
    sourceEventsVersion: function(v) {
        if (v === undefined) {
            return this._sourceEventsVersion;
        }
        else {
            this._sourceEventsVersion = v;
            console.log('sourceEventsVersion = ' + this._sourceEventsVersion);
        }
    },
    localEventsVersion: function(v) {
        if (v === undefined) {
            return this._localEventsVersion;
        }
        else {
            this._localEventsVersion = v;
            return this;
        }
    },
    isFetchingNewEvents: function() {
        return this._isFetchingNewEvents;
    },
    fetchNewEvents: function() {
        if (this.isFetchingNewEvents()) {
            console.log('++ALREADY FETCHING NEW EVENTS++');
            return;
        }

        if (this.localEventsVersion() == this.sourceEventsVersion()) {
            console.log('++LOCAL EVENTS ARE UP TO DATE++');
            return;
        }

        var self = this;
        this._isFetchingNewEvents = true;
        console.log('begin fetching new events');
        return $.ajax({
            url: '/events/fetch/' + this.id() + '/' + this.localEventsVersion(),
            type: 'get',
            dataType: 'json',
            success: function(response) {
                self.localEventsVersion(response.version);
                self.triggerServerEvents(response.events);
                self._isFetchingNewEvents = false;
                console.log('end fetching new events');
            }
        });
    },
    triggerServerEvents: function(events) {
        var self = this;
        var e;
        $.each(events, function(k, event) {
            console.log('event :: ' + event.type);
            console.log(event);
            self.trigger(event.type, event);
        });
    },
    openChannel: function() {
        //this should be overridden
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
            self.checkIfSourceEventsVersionChanged();
        });
        this._pollVersionId = id;

        return this;
    },
    checkIfSourceEventsVersionChanged: function() {
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
            success: function(sourceEventsVersion) {
                console.log('[[' + this.id() + ']]');
                console.log('sourceEventsVersion = ' + sourceEventsVersion);
                console.log('this.sourceEventsVersion() = ' + this.sourceEventsVersion());
                console.log('this.localEventsVersion() = ' + this.localEventsVersion());
                if (sourceEventsVersion != this.sourceEventsVersion()) {
                    self.sourceEventsVersion(sourceEventsVersion);
                    self.trigger('eventsversionchanged', {version: sourceEventsVersion});
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
        this._options = _.defaults(this._options, {});
    },
    openChannel: function() {
        if (!this._channel) {
            this._channel = this.Class.Pusher().subscribe(this.id());
        }
        this._channel.bind('datareceived', this.callback('ondatareceived'));
    },
    ondatareceived: function(sourceEventsVersion) {
        this.sourceEventsVersion(sourceEventsVersion);
        this.trigger('eventsversionchanged', {version: sourceEventsVersion});
    }
});
