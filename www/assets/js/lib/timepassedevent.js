//= require whowentout.component.js

WhoWentOut.Component.extend('TimePassedEvent', {
    _targetTime: null,
    _timeOut: null,
    init: function(targetTime) {
        this.setTargetTime(targetTime);
    },
    ontimepassed: function() {
        alert('time passed');
    },
    getTargetTime: function() {
        return this._targetTime;
    },
    setTargetTime: function(time) {
        clearTimeout(this._timeOut);

        this._targetTime = time;
        var delta = this.getTimeDifference();
        if (delta > 0) {
            this._timeOut = setTimeout(this.callback('ontimepassed'), delta);
        }
    },
    getTimeDifference: function() {
        return this.getTargetTime().getTime() - this.getCurrentTime().getTime();
    },
    getCurrentTime: function() {
        return new Date();
    }
});
