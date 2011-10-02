(function($) {
    window.Actions = {};
    window.JsAction = {
        RunActions: function(actions) {
            var action;
            for (var i = 0; i < actions.length; i++) {
                this.RunAction( actions[i] );
            }
        },
        RunAction: function(action) {
            var fn = window.Actions[action.name];

            if (fn === null)
                throw "The action " + action.name + "doesn't exist.";

            fn.apply(window.Actions, action.args);
        }
    };

    if ($) {
        $('body').ajaxSuccess(function(e, xhr, settings) {
            xhr.success(function(response) {
                if (response.jsactionlist) {
                    JsAction.RunActions(response.jsactionlist);
                }
            });
        });
    }

})(jQuery);

