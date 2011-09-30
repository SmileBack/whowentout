(function() {

    $.Class.extend('WhoWentOut.Queue', {}, {
        _tasks: [],
        _isRunning: false,
        init: function() {
        },
        count: function() {
            return this._tasks.length;
        },
        add: function(task) {
            this._tasks.unshift(task);
            this.run();
            return this;
        },
        run: function() {
            if (this._isRunning)
                return;

            this._isRunning = true;
            this._runNextTask();
        },
        isRunning: function() {
            return this._isRunning;
        },
        _runNextTask: function() {
            var self = this;
            if (this.count() == 0) {
                this._isRunning = false;
            }
            else {
                var nextTask = this._tasks.pop();
                var result = nextTask();
                if (result && result.done && result.fail) {
                    result
                    .done(function() {
                        setTimeout(self.callback('_runNextTask'), 0);
                    })
                    .fail(function() {
                        setTimeout(self.callback('_runNextTask'), 0);
                    });
                }
                else {
                    setTimeout(self.callback('_runNextTask'), 0);
                }
            }
        }
    });

})(jQuery);