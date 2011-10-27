//= require lib/jquery.js
//= require whowentout.channel.js
//= require whowentout.queue.js

jQuery(function($) {

    function SendJobRequestTask(options) {
        console.log('sending job request :: ' + options.url);
        $.ajax({
            url: options.url,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                console.log('successfully executed job ::');
                console.log(response.job);
            }
        });
    }

    var channel = window.channel = WhoWentOut.Channel.Create({
        type: 'PusherChannel',
        id: 'job_proxy'
    });

    var queue = window.queue = new WhoWentOut.Queue();
    
    channel.bind('new_job', function(e) {
        console.log('got job request :: ' + e.url);
        queue.add(SendJobRequestTask, {
            url: e.url
        });
    });
    
});
