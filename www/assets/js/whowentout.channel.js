WhoWentOut.Component.extend('WhoWentOut.Channel', {}, {
    init: function(options) {
        this._super();

        this._options = _.defaults(options, {
            frequency: 1,
            id: null,
            url: null
        });

        var self = this;
        this._isFetchingNewEvents = false;

        $.ajax({
            url: '/events/version/' + this.id(),
            type: 'get',
            dataType: 'json',
            success: function(response) {
                self._eventsVersion = response.version;
                self.startChecking();
            }
        });
    },
    onunmatch: function() {
    },
    id: function() {
        return this._options.id;
    },
    url: function() {
        return this._options.url;
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
    frequency: function() {
        return this._options.frequency;
    },
    startChecking: function() {
        var self = this;
        var id = this._every(this.frequency(), function() {
            self.checkIfEventsVersionChanged();
        });
        this._pollVersionId = id;

        return this;
    },
    stopChecking: function() {
        var id = this._pollVersionId;
        if (id)
            this._cancelEvery(id);

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
                    self.fetchNewEvents();
                }
            }
        });
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
        console.log('--trigger--');
        console.log(events);
        
        var self = this;
        var e;
        $.each(events, function(k, event) {
            self.trigger(event.type, event);
        });
    },
    _every: function(seconds, fn) {
        return setInterval(fn, seconds * 1000);
    },
    _cancelEvery: function(id) {
        clearInterval(id);
    }
});
