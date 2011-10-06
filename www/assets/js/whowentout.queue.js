(function() {

    WhoWentOut.Component.extend('WhoWentOut.Queue', {}, {
        _tasks: [],
        _currentTask: null,
        _taskStartTime: null,
        _options: {
            taskTimeout: null
        },
        init: function(options) {
            var self = this;
            this._super();

            this._options = $.extend(this._options, options);

            if (this._options.taskTimeout) {
                //create timer to check if a task in the queue is taking too long
                this._timer = new WhoWentOut.Timer(500);
                this._timer.tick(this.callback('_checkIfTaskIsTakingTooLong'));
                this._timer.start();
            }
        },
        count: function() {
            return this._tasks.length;
        },
        clear: function() {
            this._tasks = [];
        },
        add: function(task) {
            if (task != null) {
                this._tasks.unshift(task);
                this.run();
            }
            return this;
        },
        run: function() {
            if (this.isRunning())
                return;

            this._isRunning = true;
            this._processQueue();
        },
        drop: function() {
            if (this.count() > 0)
                this._tasks.pop();
        },
        isRunning: function() {
            return this._currentTask != null;
        },
        taskElapsedTime: function() {
            if (!this.isRunning() || this._taskStartTime == null)
                return null;

            var currentTime = new Date();
            return currentTime.getTime() - this._taskStartTime.getTime();
        },
        _processQueue: function() {
            var self = this;
            if (this.count() == 0) {
                this._isRunning = false;
            }
            else {
                this._currentTask = this._tasks.pop();

                try {
                    this._taskStartTime = new Date();
                    var result = this._currentTask();
                }
                catch (err) {
                    console.log('--error when running task--');
                    console.log(err);
                }

                console.log('--running task--');
                if (result && result.then) {
                    result.then(
                    function() {
                        console.log('-- done: finished running task --');
                        self._currentTask = null;
                        setTimeout(self.callback('_processQueue'), 0);
                    },
                    function() {
                        console.log('-- fail: finished running task --');
                        self._currentTask = null;
                        setTimeout(self.callback('_processQueue'), 0);
                    }
                    );
                }
                else {
                    console.log('-- done: non-deferred function --');
                    self._currentTask = null;
                    setTimeout(self.callback('_processQueue'), 0);
                }
            }
        },
        _checkIfTaskIsTakingTooLong: function() {
            if (this.isRunning() && this.taskElapsedTime() > this._options.taskTimeout) {
                this.trigger({
                    type: 'tasktimedout',
                    task: this._currentTask
                });

                this._currentTask = null;
                setTimeout(this.callback('_processQueue'), 0);
            }
        }
    });

})(jQuery);
