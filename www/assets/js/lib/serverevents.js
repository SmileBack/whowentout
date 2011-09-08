$('.serverevents').entwine({
    onmatch: function() {
        this._super();
        var self = this;
        this.data('isFetchingNewEvents', false);
        $.ajax({
            url: '/events/version',
            type: 'get',
            dataType: 'json',
            success: function(response) {
                self.data('eventsVersion', response.version);
                self.startChecking();
            }
        });
    },
    onunmatch: function() {
    },
    channelID: function() {
        return this.attr('channel-id');
    },
    channelUrl: function() {
        return this.attr('channel-url');
    },
    eventsVersion: function(v) {
        if (v === undefined) {
            return this.data('eventsVersion');
        }
        else {
            var oldVersion = this.data('eventsVersion');
            this.data('eventsVersion', v);
            this.trigger('neweventsversion', [v, oldVersion]);
            return this;
        }
    },
    startChecking: function() {
        var self = this;
        var id = every(1, function() {
            self.checkIfEventsVersionChanged();
        });
        this.data('pollVersionId', id);

        return this;
    },
    stopChecking: function() {
        var id = this.data('pollVersionId');
        if (id)
            cancelEvery(id);

        return this;
    },
    checkIfEventsVersionChanged: function() {
        var timestamp = (new Date()).valueOf();
        var url = this.channelUrl();
        //each channel needs its own callback otherwise there may be race conditions
        //and multiple simultaneous ajax requests may step on each other
        var callback = 'json_' + url.substring(url.lastIndexOf('/') + 1);
        var self = this;
        $.ajax({
            type: 'get',
            url: this.channelUrl() + '?timestamp=' + timestamp,
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
        return this.data('isFetchingNewEvents');
    },
    fetchNewEvents: function() {
        if (this.isFetchingNewEvents()) {
            return this;
        }
        
        var self = this;
        this.data('isFetchingNewEvents', true);
        $.ajax({
            url: '/events/fetch/' + this.channelID() + '/' + this.eventsVersion(),
            type: 'get',
            dataType: 'json',
            success: function(response) {
                self.eventsVersion(response.version);
                self.triggerServerEvents(response.events);
                self.data('isFetchingNewEvents', false);
            }
        });
        return this;
    },
    triggerServerEvents: function(events) {
        var self = this;
        var e;
        $.each(events, function(k, event) {
            e = $.Event(event.type, event);
            self.trigger(e);
        });
    }
});
