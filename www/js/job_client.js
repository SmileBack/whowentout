
$(function() {
    var pusher = new Pusher('805af8a6919abc9fb047');
    var channel = pusher.subscribe('job_queue');
    var queue = new Queue();

    var AjaxTask = Component.extend({
        _options: null,
        init: function(options) {
            this._options = options;
        },
        run: function() {
            return $.ajax(this._options);
        }
    });

    channel.bind('new_job', function(e) {
        queue.add(new AjaxTask({
            type: 'post',
            url: e.url
        }));
        console.log(e);
    });
});
