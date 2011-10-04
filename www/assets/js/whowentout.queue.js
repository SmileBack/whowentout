(function() {
    
    $.Class.extend('WhoWentOut.Queue', {}, {
        _tasks: [],
        _isRunning: false,
        init: function() {
        },
        count: function() {
            return this._tasks.length;
        },
        clear: function() {
            this._tasks = [];
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
        drop: function() {
            if (this.count() > 0)
                this._tasks.pop();
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
                console.log('--running task--');
                if (result && result.done && result.fail) {
                    result
                    .done(function() {
                        console.log('done!');
                        setTimeout(self.callback('_runNextTask'), 0);
                    })
                    .fail(function() {
                        console.log('fail!');
                        setTimeout(self.callback('_runNextTask'), 0);
                    });
                }
                else {
                    console.log('non deferred function');
                    setTimeout(self.callback('_runNextTask'), 0);
                }
            }
        }
    });

})(jQuery);
