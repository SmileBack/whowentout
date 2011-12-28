$(function () {
    var queue = new Queue();

    var pusher = new Pusher('805af8a6919abc9fb047');
    var channel = pusher.subscribe('job_queue');

    channel.bind('pusher:subscription_succeeded', function () {
        console.log('subscription succeeded');
    });

    channel.bind('pusher:subscription_error', function(status) {
        console.log('subscription failed');
    });

    var AjaxTask = Component.extend({
        _options:null,
        init:function (options) {
            this._options = options;
        },
        run:function () {
            return $.ajax(this._options);
        }
    });

    channel.bind('new_job', function (e) {
        queue.add(new AjaxTask({
            type:'post',
            url:e.url
        }));
        console.log(e);
    });
});
