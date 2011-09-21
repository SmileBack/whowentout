window.Actions = {};
window.JsAction = {
    RunActions: function() {
        if (window._JsActionList) {
            var action;
            for (var i = 0; i < window._JsActionList.length; i++) {
                action = window._JsActionList[i];
                this.RunAction(action);
            }
        }
    },
    RunAction: function(action) {
        var fn = window.Actions[action.name];
        
        if (fn === null)
            throw "The action " + action.name + "doesn't exist.";

        fn.apply(window.Actions, action.args);
    }
};
