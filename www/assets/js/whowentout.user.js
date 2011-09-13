WhoWentOut.Model = WhoWentOut.Component.extend({
    properties: {}
}, {
    init: function(attrs) {
        this.attributes = {};
        _.each(attrs, function(v, k) {
            this.set(k, v);
        }, this);
        this.initProperties();
    },
    initProperties: function() {
        var self = this;
        _.each(this.Class.properties, function(options, prop) {
            this[prop] = function(v) {
                return this.val.call(self, prop, v);
            }
        }, this);
    },
    stuff: function() {
        return 'yeaa';
    },
    get: function(k) {
        return this.attributes[k];
    },
    set: function(k, v) {
        this.attributes[k] = v;
    },
    val: function(k, v) {
        if (v === undefined) {
            return this.get(k);
        }
        else {
            return this.set(k, v);
        }
    }
});

WhoWentOut.User = WhoWentOut.Model.extend({
    properties: {
        first_name: true,
        last_name: true
    }
}, {
    init: function(attrs) {
        this._super(attrs);
    }
});

WhoWentOut.Party = WhoWentOut.Model.extend({}, {
    init: function(attrs) {
        this._super(attrs);
    }
});
