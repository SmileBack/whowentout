//! whowentout.debug.js
//

(function() {

Type.registerNamespace('whowentout');

////////////////////////////////////////////////////////////////////////////////
// whowentout.JobRelay

whowentout.JobRelay = function whowentout_JobRelay() {
    /// <field name="_queue" type="whowentout.lib.JobQueue">
    /// </field>
    /// <field name="_pusher" type="PusherApi.PusherClient">
    /// </field>
    /// <field name="_channel" type="PusherApi.Channel">
    /// </field>
    /// <field name="_started" type="Boolean">
    /// </field>
}
whowentout.JobRelay.prototype = {
    _queue: null,
    _pusher: null,
    _channel: null,
    _started: false,
    
    _start: function whowentout_JobRelay$_start() {
        if (this._started) {
            return;
        }
        this._started = true;
        this._queue = new whowentout.lib.JobQueue();
        this._queue.add_jobStart(ss.Delegate.create(this, this._queue_JobStart));
        this._queue.add_jobComplete(ss.Delegate.create(this, this._queue_JobComplete));
        this._queue.add_statusChanged(ss.Delegate.create(this, this._queue_StatusChanged));
        this._pusher = new PusherApi.PusherClient('805af8a6919abc9fb047');
        this._pusher.get_connection().add_stateChange(ss.Delegate.create(this, this._connection_StateChange));
        this._channel = this._pusher.subscribe('job_queue');
        this._channel.add_subscriptionSucceeded(ss.Delegate.create(this, this._channel_SubscriptionSucceeded));
        this._channel.add_subscriptionFailed(ss.Delegate.create(this, this._channel_SubscriptionFailed));
        this._channel.bind('new_job', ss.Delegate.create(this, this._onNewJobReceived));
    },
    
    _onNewJobReceived: function whowentout_JobRelay$_onNewJobReceived(jobObject) {
        /// <param name="jobObject" type="Object">
        /// </param>
        var url = jobObject.url;
        var job = new whowentout.SendRequestJob(url);
        this._queue.add(job);
        console.log(String.format('queued job [{0} jobs]', this._queue.get_count()));
        console.log(jobObject);
    },
    
    _queue_StatusChanged: function whowentout_JobRelay$_queue_StatusChanged(sender, e) {
        /// <param name="sender" type="Object">
        /// </param>
        /// <param name="e" type="whowentout.lib.JobQueueStatusChangedEventArgs">
        /// </param>
        console.log(String.format('JOB QUEUE : {0} -> {1}', e.get_oldStatus(), e.get_newStatus()));
    },
    
    _queue_JobComplete: function whowentout_JobRelay$_queue_JobComplete(sender, e) {
        /// <param name="sender" type="Object">
        /// </param>
        /// <param name="e" type="whowentout.lib.JobEventArgs">
        /// </param>
        console.log(String.format('job complete [{0} jobs]', this._queue.get_count()));
    },
    
    _queue_JobStart: function whowentout_JobRelay$_queue_JobStart(sender, e) {
        /// <param name="sender" type="Object">
        /// </param>
        /// <param name="e" type="whowentout.lib.JobEventArgs">
        /// </param>
        console.log('job start');
    },
    
    _channel_SubscriptionFailed: function whowentout_JobRelay$_channel_SubscriptionFailed(sender, e) {
        /// <param name="sender" type="Object">
        /// </param>
        /// <param name="e" type="ss.EventArgs">
        /// </param>
        console.log('subscription failed');
    },
    
    _channel_SubscriptionSucceeded: function whowentout_JobRelay$_channel_SubscriptionSucceeded(sender, e) {
        /// <param name="sender" type="Object">
        /// </param>
        /// <param name="e" type="ss.EventArgs">
        /// </param>
        console.log('subscription succeeded');
    },
    
    _connection_StateChange: function whowentout_JobRelay$_connection_StateChange(sender, e) {
        /// <param name="sender" type="Object">
        /// </param>
        /// <param name="e" type="PusherApi.StateChangeEventArgs">
        /// </param>
        console.log(String.format('PUSHER : {0} -> {1}', e.get_previous(), e.get_current()));
    }
}


////////////////////////////////////////////////////////////////////////////////
// whowentout.SendRequestJob

whowentout.SendRequestJob = function whowentout_SendRequestJob(url) {
    /// <param name="url" type="String">
    /// </param>
    /// <field name="_url$1" type="String">
    /// </field>
    whowentout.SendRequestJob.initializeBase(this);
    this._url$1 = url;
}
whowentout.SendRequestJob.prototype = {
    _url$1: null,
    
    run: function whowentout_SendRequestJob$run() {
        /// <returns type="Object"></returns>
        return $.ajax(this._url$1);
    }
}


////////////////////////////////////////////////////////////////////////////////
// whowentout.ConsoleLogJob

whowentout.ConsoleLogJob = function whowentout_ConsoleLogJob(message) {
    /// <param name="message" type="String">
    /// </param>
    /// <field name="_message$1" type="String">
    /// </field>
    whowentout.ConsoleLogJob.initializeBase(this);
    this._message$1 = message;
}
whowentout.ConsoleLogJob.prototype = {
    _message$1: null,
    
    run: function whowentout_ConsoleLogJob$run() {
        /// <returns type="Object"></returns>
        console.log(this._message$1);
        return null;
    }
}


////////////////////////////////////////////////////////////////////////////////
// whowentout.CountJob

whowentout.CountJob = function whowentout_CountJob(name, n) {
    /// <param name="name" type="String">
    /// </param>
    /// <param name="n" type="Number" integer="true">
    /// </param>
    /// <field name="_target$1" type="Number" integer="true">
    /// </field>
    /// <field name="_cur$1" type="Number" integer="true">
    /// </field>
    /// <field name="_name$1" type="String">
    /// </field>
    whowentout.CountJob.initializeBase(this);
    this._name$1 = name;
    this._target$1 = n;
    this._cur$1 = 0;
}
whowentout.CountJob.prototype = {
    _target$1: 0,
    _cur$1: 0,
    _name$1: null,
    
    run: function whowentout_CountJob$run() {
        /// <returns type="Object"></returns>
        var dfd = $.Deferred();
        var id = 0;
        id = window.setInterval(ss.Delegate.create(this, function() {
            console.log(String.format('{0}: {1}', this._name$1, this._cur$1));
            console.log(String.format('cur = {0}, target = {1}', this._cur$1, this._target$1));
            this._cur$1++;
            if (this._cur$1 > this._target$1) {
                window.clearInterval(id);
                dfd.resolve();
            }
        }), 100);
        return dfd;
    }
}


////////////////////////////////////////////////////////////////////////////////
// whowentout._mainPage



Type.registerNamespace('PusherApi');

////////////////////////////////////////////////////////////////////////////////
// PusherApi.ConnectionState

PusherApi.ConnectionState = function() { 
    /// <field name="initialized" type="Number" integer="true" static="true">
    /// Initial state. No event is emitted in this state.
    /// </field>
    /// <field name="connecting" type="Number" integer="true" static="true">
    /// All dependencies have been loaded and Pusher is trying to connect.
    /// The connection will also enter this state when it is trying to reconnect after a connection failure.
    /// </field>
    /// <field name="connected" type="Number" integer="true" static="true">
    /// The connection to Pusher is open and authenticated with your app.
    /// </field>
    /// <field name="unavailable" type="Number" integer="true" static="true">
    /// The connection is temporarily unavailable. In most cases this means
    /// that there is no internet connection. It could also mean that Pusher is down,
    /// or some intermediary is blocking the connection. In this state, Pusher will
    /// automatically retry the connection every ten seconds.
    /// </field>
    /// <field name="failed" type="Number" integer="true" static="true">
    /// Pusher is not supported by the browser. This implies that Flash is not available,
    /// since that is the only fallback in browsers that do not natively support WebSockets.
    /// </field>
    /// <field name="disconnected" type="Number" integer="true" static="true">
    /// The Pusher connection was previously connected and has now intentionally been closed.
    /// </field>
};
PusherApi.ConnectionState.prototype = {
    initialized: 'initialized', 
    connecting: 'connecting', 
    connected: 'connected', 
    unavailable: 'unavailable', 
    failed: 'failed', 
    disconnected: 'disconnected'
}
PusherApi.ConnectionState.registerEnum('PusherApi.ConnectionState', false);


////////////////////////////////////////////////////////////////////////////////
// PusherApi.Channel

PusherApi.Channel = function PusherApi_Channel(channelJs) {
    /// <param name="channelJs" type="PusherChannelJs">
    /// </param>
    /// <field name="__subscriptionSucceeded" type="Function">
    /// </field>
    /// <field name="__subscriptionFailed" type="Function">
    /// </field>
    /// <field name="_channelJs" type="PusherChannelJs">
    /// </field>
    this._channelJs = channelJs;
    this._channelJs.bind('pusher:subscription_succeeded', ss.Delegate.create(this, function(e) {
        if (this.__subscriptionSucceeded != null) {
            this.__subscriptionSucceeded(this, ss.EventArgs.Empty);
        }
    }));
    this._channelJs.bind('pusher:subscription_failed', ss.Delegate.create(this, function(e) {
        if (this.__subscriptionFailed != null) {
            this.__subscriptionFailed(this, ss.EventArgs.Empty);
        }
    }));
}
PusherApi.Channel.prototype = {
    
    add_subscriptionSucceeded: function PusherApi_Channel$add_subscriptionSucceeded(value) {
        /// <param name="value" type="Function" />
        this.__subscriptionSucceeded = ss.Delegate.combine(this.__subscriptionSucceeded, value);
    },
    remove_subscriptionSucceeded: function PusherApi_Channel$remove_subscriptionSucceeded(value) {
        /// <param name="value" type="Function" />
        this.__subscriptionSucceeded = ss.Delegate.remove(this.__subscriptionSucceeded, value);
    },
    
    __subscriptionSucceeded: null,
    
    add_subscriptionFailed: function PusherApi_Channel$add_subscriptionFailed(value) {
        /// <param name="value" type="Function" />
        this.__subscriptionFailed = ss.Delegate.combine(this.__subscriptionFailed, value);
    },
    remove_subscriptionFailed: function PusherApi_Channel$remove_subscriptionFailed(value) {
        /// <param name="value" type="Function" />
        this.__subscriptionFailed = ss.Delegate.remove(this.__subscriptionFailed, value);
    },
    
    __subscriptionFailed: null,
    _channelJs: null,
    
    bind: function PusherApi_Channel$bind(eventName, handler) {
        /// <param name="eventName" type="String">
        /// </param>
        /// <param name="handler" type="System.Action`1">
        /// </param>
        this._channelJs.bind(eventName, handler);
    },
    
    trigger: function PusherApi_Channel$trigger(eventName, data) {
        /// <param name="eventName" type="String">
        /// </param>
        /// <param name="data" type="String">
        /// </param>
        /// <returns type="Boolean"></returns>
        return this._channelJs.trigger(eventName, data);
    }
}


////////////////////////////////////////////////////////////////////////////////
// PusherApi.Connection

PusherApi.Connection = function PusherApi_Connection(connectionJs) {
    /// <param name="connectionJs" type="PusherConnectionJs">
    /// </param>
    /// <field name="__stateChange" type="System.EventHandler`1">
    /// </field>
    /// <field name="__connected" type="Function">
    /// </field>
    /// <field name="_connectionJs" type="PusherConnectionJs">
    /// </field>
    this._connectionJs = connectionJs;
    this._connectionJs.bind('state_change', ss.Delegate.create(this, this._relayStateChangeEvent));
}
PusherApi.Connection.prototype = {
    
    add_stateChange: function PusherApi_Connection$add_stateChange(value) {
        /// <param name="value" type="Function" />
        this.__stateChange = ss.Delegate.combine(this.__stateChange, value);
    },
    remove_stateChange: function PusherApi_Connection$remove_stateChange(value) {
        /// <param name="value" type="Function" />
        this.__stateChange = ss.Delegate.remove(this.__stateChange, value);
    },
    
    __stateChange: null,
    
    add_connected: function PusherApi_Connection$add_connected(value) {
        /// <param name="value" type="Function" />
        this.__connected = ss.Delegate.combine(this.__connected, value);
    },
    remove_connected: function PusherApi_Connection$remove_connected(value) {
        /// <param name="value" type="Function" />
        this.__connected = ss.Delegate.remove(this.__connected, value);
    },
    
    __connected: null,
    _connectionJs: null,
    
    get_state: function PusherApi_Connection$get_state() {
        /// <value type="PusherApi.ConnectionState"></value>
        return this._jsStateToEnum(this._connectionJs.state);
    },
    
    _relayStateChangeEvent: function PusherApi_Connection$_relayStateChangeEvent(e) {
        /// <param name="e" type="Object">
        /// </param>
        var current = this._jsStateToEnum(e.current);
        var previous = this._jsStateToEnum(e.previous);
        if (this.__connected != null && current === 'connected') {
            this.__connected(this, ss.EventArgs.Empty);
        }
        if (this.__stateChange != null) {
            this.__stateChange(this, new PusherApi.StateChangeEventArgs(previous, current));
        }
    },
    
    _jsStateToEnum: function PusherApi_Connection$_jsStateToEnum(state) {
        /// <param name="state" type="Object">
        /// </param>
        /// <returns type="PusherApi.ConnectionState"></returns>
        var s = state;
        switch (s) {
            case 'initialized':
                return 'initialized';
            case 'connecting':
                return 'connecting';
            case 'connected':
                return 'connected';
            case 'unvailable':
                return 'unavailable';
            case 'failed':
                return 'failed';
            case 'disconnected':
                return 'disconnected';
            default:
                throw new Error('Invalid state ' + s + '.');
        }
    }
}


////////////////////////////////////////////////////////////////////////////////
// PusherApi.StateChangeEventArgs

PusherApi.StateChangeEventArgs = function PusherApi_StateChangeEventArgs(previous, current) {
    /// <param name="previous" type="PusherApi.ConnectionState">
    /// </param>
    /// <param name="current" type="PusherApi.ConnectionState">
    /// </param>
    /// <field name="_previous$1" type="PusherApi.ConnectionState">
    /// </field>
    /// <field name="_current$1" type="PusherApi.ConnectionState">
    /// </field>
    PusherApi.StateChangeEventArgs.initializeBase(this);
    this.set_previous(previous);
    this.set_current(current);
}
PusherApi.StateChangeEventArgs.prototype = {
    _previous$1: null,
    
    get_previous: function PusherApi_StateChangeEventArgs$get_previous() {
        /// <value type="PusherApi.ConnectionState"></value>
        return this._previous$1;
    },
    set_previous: function PusherApi_StateChangeEventArgs$set_previous(value) {
        /// <value type="PusherApi.ConnectionState"></value>
        this._previous$1 = value;
        return value;
    },
    
    _current$1: null,
    
    get_current: function PusherApi_StateChangeEventArgs$get_current() {
        /// <value type="PusherApi.ConnectionState"></value>
        return this._current$1;
    },
    set_current: function PusherApi_StateChangeEventArgs$set_current(value) {
        /// <value type="PusherApi.ConnectionState"></value>
        this._current$1 = value;
        return value;
    }
}


////////////////////////////////////////////////////////////////////////////////
// PusherApi.PusherClient

PusherApi.PusherClient = function PusherApi_PusherClient(applicationKey) {
    /// <param name="applicationKey" type="String">
    /// </param>
    /// <field name="_pusherJs" type="Pusher">
    /// </field>
    /// <field name="_channels" type="Object">
    /// </field>
    /// <field name="_connection" type="PusherApi.Connection">
    /// </field>
    this._channels = {};
    this._pusherJs = new Pusher(applicationKey);
    this.set_connection(new PusherApi.Connection(this._pusherJs.connection));
}
PusherApi.PusherClient.prototype = {
    _pusherJs: null,
    _connection: null,
    
    get_connection: function PusherApi_PusherClient$get_connection() {
        /// <value type="PusherApi.Connection"></value>
        return this._connection;
    },
    set_connection: function PusherApi_PusherClient$set_connection(value) {
        /// <value type="PusherApi.Connection"></value>
        this._connection = value;
        return value;
    },
    
    subscribe: function PusherApi_PusherClient$subscribe(channelName) {
        /// <param name="channelName" type="String">
        /// </param>
        /// <returns type="PusherApi.Channel"></returns>
        console.log(String.format('subscribing to {0}', channelName));
        this._pusherJs.subscribe(channelName);
        return this._getChannel(channelName);
    },
    
    unsubscribe: function PusherApi_PusherClient$unsubscribe(channelName) {
        /// <param name="channelName" type="String">
        /// </param>
        this._pusherJs.unsubscribe(channelName);
        delete this._channels[channelName];
    },
    
    _getChannel: function PusherApi_PusherClient$_getChannel(channelName) {
        /// <param name="channelName" type="String">
        /// </param>
        /// <returns type="PusherApi.Channel"></returns>
        var channelJs = this._pusherJs.channel(channelName);
        if (channelJs == null) {
            return null;
        }
        if (!Object.keyExists(this._channels, channelName)) {
            this._channels[channelName] = new PusherApi.Channel(channelJs);
        }
        return this._channels[channelName];
    },
    get_item: function PusherApi_PusherClient$get_item(channelName) {
        /// <param name="channelName" type="String">
        /// </param>
        /// <param name="value" type="PusherApi.Channel">
        /// </param>
        /// <returns type="PusherApi.Channel"></returns>
        return this._getChannel(channelName);
    }
}


Type.registerNamespace('whowentout.lib');

////////////////////////////////////////////////////////////////////////////////
// whowentout.lib.JobQueueStatus

whowentout.lib.JobQueueStatus = function() { 
    /// <field name="idle" type="Number" integer="true" static="true">
    /// </field>
    /// <field name="busy" type="Number" integer="true" static="true">
    /// </field>
};
whowentout.lib.JobQueueStatus.prototype = {
    idle: 'idle', 
    busy: 'busy'
}
whowentout.lib.JobQueueStatus.registerEnum('whowentout.lib.JobQueueStatus', false);


////////////////////////////////////////////////////////////////////////////////
// whowentout.lib.Job

whowentout.lib.Job = function whowentout_lib_Job() {
}


////////////////////////////////////////////////////////////////////////////////
// whowentout.lib.JobQueue

whowentout.lib.JobQueue = function whowentout_lib_JobQueue() {
    /// <field name="__jobStart" type="System.EventHandler`1">
    /// </field>
    /// <field name="__jobComplete" type="System.EventHandler`1">
    /// </field>
    /// <field name="__statusChanged" type="System.EventHandler`1">
    /// </field>
    /// <field name="_tasks" type="Array">
    /// </field>
    /// <field name="_currentJob" type="whowentout.lib.Job">
    /// </field>
    /// <field name="_status" type="whowentout.lib.JobQueueStatus">
    /// </field>
    this._tasks = [];
    this._status = 'idle';
}
whowentout.lib.JobQueue.prototype = {
    
    add_jobStart: function whowentout_lib_JobQueue$add_jobStart(value) {
        /// <param name="value" type="Function" />
        this.__jobStart = ss.Delegate.combine(this.__jobStart, value);
    },
    remove_jobStart: function whowentout_lib_JobQueue$remove_jobStart(value) {
        /// <param name="value" type="Function" />
        this.__jobStart = ss.Delegate.remove(this.__jobStart, value);
    },
    
    __jobStart: null,
    
    add_jobComplete: function whowentout_lib_JobQueue$add_jobComplete(value) {
        /// <param name="value" type="Function" />
        this.__jobComplete = ss.Delegate.combine(this.__jobComplete, value);
    },
    remove_jobComplete: function whowentout_lib_JobQueue$remove_jobComplete(value) {
        /// <param name="value" type="Function" />
        this.__jobComplete = ss.Delegate.remove(this.__jobComplete, value);
    },
    
    __jobComplete: null,
    
    add_statusChanged: function whowentout_lib_JobQueue$add_statusChanged(value) {
        /// <param name="value" type="Function" />
        this.__statusChanged = ss.Delegate.combine(this.__statusChanged, value);
    },
    remove_statusChanged: function whowentout_lib_JobQueue$remove_statusChanged(value) {
        /// <param name="value" type="Function" />
        this.__statusChanged = ss.Delegate.remove(this.__statusChanged, value);
    },
    
    __statusChanged: null,
    _currentJob: null,
    
    get_currentJob: function whowentout_lib_JobQueue$get_currentJob() {
        /// <value type="whowentout.lib.Job"></value>
        return this._currentJob;
    },
    
    get_count: function whowentout_lib_JobQueue$get_count() {
        /// <summary>
        /// The number of Jobs remaining in the queue.
        /// The job currently being executed is not included.
        /// </summary>
        /// <value type="Number" integer="true"></value>
        return this._tasks.length;
    },
    
    get_status: function whowentout_lib_JobQueue$get_status() {
        /// <value type="whowentout.lib.JobQueueStatus"></value>
        return this._status;
    },
    set_status: function whowentout_lib_JobQueue$set_status(value) {
        /// <value type="whowentout.lib.JobQueueStatus"></value>
        if (this._status !== value) {
            var oldStatus = this._status;
            var newStatus = value;
            this._status = newStatus;
            if (this.__statusChanged != null) {
                this.__statusChanged(this, new whowentout.lib.JobQueueStatusChangedEventArgs(oldStatus, newStatus));
            }
        }
        return value;
    },
    
    clear: function whowentout_lib_JobQueue$clear() {
        this._tasks.clear();
    },
    
    add: function whowentout_lib_JobQueue$add(job) {
        /// <param name="job" type="whowentout.lib.Job">
        /// </param>
        this._tasks.enqueue(job);
        this.run();
    },
    
    run: function whowentout_lib_JobQueue$run() {
        if (this.get_status() === 'busy') {
            return;
        }
        this.set_status('busy');
        this._processNextItemInQueue();
    },
    
    _processNextItemInQueue: function whowentout_lib_JobQueue$_processNextItemInQueue() {
        if (!this.get_count()) {
            this.set_status('idle');
            return;
        }
        this._currentJob = this._tasks.dequeue();
        if (this.__jobStart != null) {
            this.__jobStart(this, new whowentout.lib.JobEventArgs(this._currentJob));
        }
        var result = this._currentJob.run();
        if (this._isDeferredObject(result)) {
            var dfd = result;
            var onCompleteAction = ss.Delegate.create(this, function() {
                this._currentJob = null;
                if (this.__jobComplete != null) {
                    this.__jobComplete(this, new whowentout.lib.JobEventArgs(this._currentJob));
                }
                window.setTimeout(ss.Delegate.create(this, this._processNextItemInQueue), 0);
            });
            dfd.then(onCompleteAction, onCompleteAction);
        }
        else {
            window.setTimeout(ss.Delegate.create(this, this._processNextItemInQueue), 0);
        }
    },
    
    _isDeferredObject: function whowentout_lib_JobQueue$_isDeferredObject(o) {
        /// <param name="o" type="Object">
        /// </param>
        /// <returns type="Boolean"></returns>
        return o != null && o.then != null;
    }
}


////////////////////////////////////////////////////////////////////////////////
// whowentout.lib.JobEventArgs

whowentout.lib.JobEventArgs = function whowentout_lib_JobEventArgs(job) {
    /// <param name="job" type="whowentout.lib.Job">
    /// </param>
    /// <field name="_job$1" type="whowentout.lib.Job">
    /// </field>
    whowentout.lib.JobEventArgs.initializeBase(this);
    this._job$1 = job;
}
whowentout.lib.JobEventArgs.prototype = {
    _job$1: null,
    
    get_job: function whowentout_lib_JobEventArgs$get_job() {
        /// <value type="whowentout.lib.Job"></value>
        return this._job$1;
    }
}


////////////////////////////////////////////////////////////////////////////////
// whowentout.lib.JobQueueStatusChangedEventArgs

whowentout.lib.JobQueueStatusChangedEventArgs = function whowentout_lib_JobQueueStatusChangedEventArgs(oldStatus, newStatus) {
    /// <param name="oldStatus" type="whowentout.lib.JobQueueStatus">
    /// </param>
    /// <param name="newStatus" type="whowentout.lib.JobQueueStatus">
    /// </param>
    /// <field name="_oldStatus$1" type="whowentout.lib.JobQueueStatus">
    /// </field>
    /// <field name="_newStatus$1" type="whowentout.lib.JobQueueStatus">
    /// </field>
    whowentout.lib.JobQueueStatusChangedEventArgs.initializeBase(this);
    this._oldStatus$1 = oldStatus;
    this._newStatus$1 = newStatus;
}
whowentout.lib.JobQueueStatusChangedEventArgs.prototype = {
    _oldStatus$1: null,
    _newStatus$1: null,
    
    get_oldStatus: function whowentout_lib_JobQueueStatusChangedEventArgs$get_oldStatus() {
        /// <value type="whowentout.lib.JobQueueStatus"></value>
        return this._oldStatus$1;
    },
    
    get_newStatus: function whowentout_lib_JobQueueStatusChangedEventArgs$get_newStatus() {
        /// <value type="whowentout.lib.JobQueueStatus"></value>
        return this._newStatus$1;
    }
}


whowentout.JobRelay.registerClass('whowentout.JobRelay');
whowentout.lib.Job.registerClass('whowentout.lib.Job');
whowentout.SendRequestJob.registerClass('whowentout.SendRequestJob', whowentout.lib.Job);
whowentout.ConsoleLogJob.registerClass('whowentout.ConsoleLogJob', whowentout.lib.Job);
whowentout.CountJob.registerClass('whowentout.CountJob', whowentout.lib.Job);
PusherApi.Channel.registerClass('PusherApi.Channel');
PusherApi.Connection.registerClass('PusherApi.Connection');
PusherApi.StateChangeEventArgs.registerClass('PusherApi.StateChangeEventArgs', ss.EventArgs);
PusherApi.PusherClient.registerClass('PusherApi.PusherClient');
whowentout.lib.JobQueue.registerClass('whowentout.lib.JobQueue');
whowentout.lib.JobEventArgs.registerClass('whowentout.lib.JobEventArgs', ss.EventArgs);
whowentout.lib.JobQueueStatusChangedEventArgs.registerClass('whowentout.lib.JobQueueStatusChangedEventArgs', ss.EventArgs);
(function () {
    $(function() {
        var relay = new whowentout.JobRelay();
        relay._start();
    });
})();
})();

//! This script was generated using Script# v0.7.4.0
