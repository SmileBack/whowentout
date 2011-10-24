//= require whowentout.component.js

(function($) {

    WhoWentOut.Component.extend('WhoWentOut.Task', {}, {
        _fn: null,
        _options: null,
        init: function(fn, options) {
            this._fn = fn;
            this._options = options;
        },
        run: function() {
            return this._fn(this._options);
        }
    });

    WhoWentOut.Component.extend('WhoWentOut.Queue', {}, {
        _tasks: [],
        _currentTask: null,
        _options: {taskTimeout: null},
        init: function(options) {
            var self = this;
            this._super();

            this._options = $.extend(this._options, options);
        },
        count: function() {
            return this._tasks.length;
        },
        clear: function() {
            this._tasks = [];
        },
        add: function(fn, options) {
            var task = new WhoWentOut.Task(fn, options);
            this._tasks.unshift(task);
            this.run();
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
        _processQueue: function() {
            var self = this;
            if (this.count() == 0) {
                this._isRunning = false;
            }
            else {
                this._currentTask = this._tasks.pop();
                var result = this._currentTask.run();

//                console.log('--running task--');
                if (result && result.then) {
                    result.then(
                    function() {
//                        console.log('-- done: finished running task --');
                        self._currentTask = null;
                        setTimeout(self.callback('_processQueue'), 0);
                    },
                    function() {
//                        console.log('-- fail: finished running task --');
                        self._currentTask = null;
                        setTimeout(self.callback('_processQueue'), 0);
                    }
                    );
                }
                else {
//                    console.log('-- done: non-deferred function --');
                    self._currentTask = null;
                    setTimeout(self.callback('_processQueue'), 0);
                }
            }
        }
    });

})(jQuery);
