//= require whowentout.component.js

WhoWentOut.Component.extend('WhoWentOut.Hash', {}, {
    init: function() {
        this._super();
        this._hash = {};
    },
    clear: function() {
        this._hash = {};
    },
    get: function(key) {
        return this._hash[ key ];
    },
    set: function(k, obj) {
        this._hash[ k ] = obj;

        this._attachItemEvents(obj);

        this.trigger({
            type: 'add',
            key: k,
            item: obj
        });
    },
    remove: function(k) {
        var obj = this._hash[k];
        delete this._hash[ obj.hash() ];

        this._detachItemEvents(obj);

        this.trigger({type: 'remove', item: obj});
    },
    contains: function(k) {
        return this._hash[ k ] !== undefined;
    },
    each: function(callback) {
        var self = this;
        _.each(this._hash, function(v, k) {
            callback.call(self, v, k);
        });
    },
    values: function() {
        return _.clone(this._hash);
    },
    _attachItemEvents: function(obj) {
        if (!_.isFunction(obj.bind)) return;

        obj.bind('change', this.callback('onitemchange'));
    },
    _detachItemEvents: function(obj) {
        if (!_.isFunction(obj.bind)) return;

        obj.unbind('change', this.callback('onitemchange'));
    },
    onitemchange: function(e) {
        this.trigger({type: 'itemchange', item: e.target, key: e.key, value: e.value, prevValue: e.prevValue});
    }
});