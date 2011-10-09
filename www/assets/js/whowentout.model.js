//= require whowentout.component.js

WhoWentOut.Component.extend('WhoWentOut.Model', {
    properties: {}
}, {
    init: function(props) {
        this._super();
        this.properties = {};
        _.each(props, function(v, k) {
            this.set(k, v);
        }, this);
        this.initProperties();
    },
    initProperties: function() {
    },
    get: function(k) {
        return this.properties[k];
    },
    set: function(key, value) {
        var prevValue = this.get(key);
        this.properties[key] = value;
        this.trigger({type: 'change', key: key, value: value, prevValue: prevValue});
    },
    val: function(k, v) {
        if (v === undefined) {
            return this.get(k);
        }
        else {
            return this.set(k, v);
        }
    },
    id: function() {
        return this.get('id');
    },
    toObject: function() {
        return _.clone(this.properties);
    }
});
