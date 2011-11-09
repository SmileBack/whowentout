//= require lib/jquery.js
//= require whowentout.component.js
//= require whowentout.queue.js

(function() {

    function LogTask(options) {
        var action_name = options.action_name,
            action_data = options.action_data;
        
        action_data = action_data || {};

        action_data.url = window.location.pathname;
        action_data.browser_time = Math.floor(new Date().getTime() / 1000);

        return $.ajax({
            type: 'post',
            dataType: 'json',
            url: '/userlogentry/create',
            data: {action_name: action_name, action_data: action_data},
            success: function() {
                console.log('logged ' + action_name);
            }
        });
    }

    WhoWentOut.Component.extend('WhoWentOut.UserLogger', {
        init: function() {
            this._queue = new WhoWentOut.Queue();
        },
        log: function(action_name, action_data) {
            this._queue.add(LogTask, {
                action_name: action_name,
                action_data: action_data
            });
        }
    });

})();
