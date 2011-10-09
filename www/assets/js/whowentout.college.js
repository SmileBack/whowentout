//= require whowentout.model.js

WhoWentOut.Model.extend('WhoWentOut.College', {
    FromJson: function(attrs) {
        return new WhoWentOut.College({
           id: attrs.id,
           doorsClosingTime: new Date(attrs.doorsClosingTime * 1000),
           doorsOpeningTime: new Date(attrs.doorsOpeningTime * 1000),
           doorsOpen: attrs.doorsOpen,
           tomorrowTime: new Date(attrs.tomorrowTime * 1000),
           yesterdayTime: new Date(attrs.yesterdayTime * 1000)
        });
    }
}, {
    init: function(attrs) {
        this._super(attrs);
    },
    doorsOpeningTime: function() {
        return this.get('doorsOpeningTime');
    },
    doorsClosingTime: function() {
        return this.get('doorsClosingTime');
    },
    doorsOpen: function() {
        return this.get('doorsOpen');
    },
    tomorrowTime: function() {
        return this.get('tomorrowTime');
    },
    yesterdayTime: function() {
        return this.get('yesterdayTime');
    }
});
