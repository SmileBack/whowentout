//= require lib/jquery.js
//= require lib/jquery.class.js

$.Class.extend('WhoWentOut.Component', {
    init: function() {},
    bind: function(eventType, callback) {
        var eventBeacon = this._getEventBeacon();
        
        // Check to see this event type has a pre-trigger
        // interceptor yet. Since event handlers are triggered
        // in the order in which they were bound, we can be sure
        // that our preTrigger goes first.
        if ( ! eventBeacon.data("_preTrigger")[ eventType ]) {

            // We need to bind the pre-trigger first so it can
            // change the target appropriatly before any other
            // event handlers get triggered.
            eventBeacon.bind(eventType, jQuery.proxy(this._preTrigger, this) );

            // Keep track fo the event type so we don't re-bind
            // this prehandler.
            eventBeacon.data("_preTrigger")[ eventType ] = true;
        }

        // Replace the callback function with a proxied callback
        // that will execute in the context of this Girl object.
        arguments[ arguments.length - 1 ] = jQuery.proxy(
            arguments[ arguments.length - 1 ],
            this
        );

        // Now, when passing the execution off to bind(), we will
        // apply the arguments; this way, we can use the optional
        // data argument if it is provided.
        jQuery.fn.bind.apply(eventBeacon, arguments);

        // Return this object reference for method chaining.
        return this;
    },
    unbind: function(eventType, callback) {
        var eventBeacon = this._getEventBeacon();
        
        // Pass the unbind() request onto the event beacon.
        jQuery.fn.unbind.apply(eventBeacon, arguments);
        return this;
    },
    trigger: function(eventType, data) {
        var eventBeacon = this._getEventBeacon();
        
        // Pass the trigger() request onto the event beacon.
        jQuery.fn.trigger.apply(eventBeacon, arguments);
        return this;
    },
    _preTrigger: function(event) {
        // Mutate the event to point to the right target.
        event.target = this;
    },
    _getEventBeacon: function() {
        if ( ! this._eventBeacon) {
            // Internally, we are going to keep a free-standing DOM
            // node to power our publish / subscribe event mechanism
            // using jQuery's bind/trigger functionality.
            //
            // NOTE: We are using a custom node type here so that we
            // don't have any unexpected event behavior based on the
            // node type.
            this._eventBeacon = $(document.createElement("beacon"));
            this._eventBeacon.data("_preTrigger", {});
        }
        return this._eventBeacon;
    }
});
