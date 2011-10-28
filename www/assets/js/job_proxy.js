//= require lib/jquery.js
//= require whowentout.channel.js
//= require whowentout.queue.js

jQuery(function($) {

    function SendJobRequestTask(options) {
        console.log('sending job request :: ' + options.url);
        return $.ajax({
            url: options.url,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                console.log('successfully executed job ::');
                console.log(response.job);
            }
        });
    }

    WhoWentOut.Pusher.PusherAuthEndpoint = '/job_proxy/pusherauth';

    var channel = window.channel = WhoWentOut.Channel.Create({
        type: 'PusherChannel',
        id: window.settings.job_proxy.channel
    });

    var queue = window.queue = new WhoWentOut.Queue();
    
    channel.bind('new_job', function(e) {
        console.log('got job request :: ' + e.url);
        queue.add(SendJobRequestTask, {
            url: e.url
        });
    });
    
});
