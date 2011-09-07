jQuery(function($) {

    WWO.dialog = $.dialog.create();

    WWO.dialog.anchor('viewport', 'c'); //keeps the dialog box in the center
    $(window).bind('scroll', function() { //even when you scroll
        WWO.dialog.refreshPosition();
    });

});

$('a.confirm').entwine({
    onclick: function(e) {
        var action = this.attr('action') || 'do this';
        var result = confirm("Are you sure you want to " + action + "?");
        if (!result) {
            e.preventDefault();
        }
    }
});

$('.serverevents').entwine({
    onmatch: function() {
        var self = this;
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
    onneweventsversion: function(e, newVersion, oldVersion) {
        this.fetchNewEvents(oldVersion);
    },
    eventsSource: function() {
      return this.attr('source');
    },
    eventsVersionUrl: function() {
        return '/events/' + this.eventsSource();
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
        var id = every(2, function() {
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
        var url = this.eventsVersionUrl();
        var callback = 'json_' + url.substring(url.lastIndexOf('/') + 1);
        var self = this;
        $.ajax({
            type: 'get',
            url: this.eventsVersionUrl() + '?timestamp=' + timestamp,
            dataType: 'jsonp',
            jsonp: false,
            jsonpCallback: callback,
            context: this,
            success: function(newVersion) {
                var currentVersion = self.eventsVersion();
                if (newVersion != currentVersion) {
                    self.eventsVersion(newVersion);
                }
            }
        });
    },
    fetchNewEvents: function(version) {
        var self = this;
        $.ajax({
            url: '/events/fetch/' + this.eventsSource() + '/' + version,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                self.triggerServerEvents(response.events);
            }
        });
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
