//= require lib/jquery.class.js

WhoWentOut.Component.extend('WhoWentOut.Timer', {
    _interval: 1000,
    _timerId: null,
    init: function(interval) {
        this._super();
        this.interval(interval);
    },
    start: function() {
        if (this.isRunning())
            return;

        this._timerId = setInterval(
            this.callback('ontimertick'),
            this.interval()
        );
    },
    stop: function() {
        if (this.isRunning()) {
            clearInterval(this._timerId);
            this._timerId = null;
        }
    },
    isRunning: function() {
        return this._timerId != null;
    },
    interval: function(v) {
        if (v === undefined) {
            return this._interval;
        }
        else {
            if (this.isRunning()) {
                this.stop();
                this._interval = v;
                this.start();
            }
            else {
                this._interval = v;
            }
        }
    },
    ontimertick: function() {
        this.trigger('tick');
    },
    tick: function(fn) {
        this.bind('tick', fn);
    }
});
