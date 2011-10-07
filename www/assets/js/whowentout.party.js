//= require lib/jquery.entwine.js
//= require WhoWentOut.Model.js

WhoWentOut.Model.extend('WhoWentOut.Party', {}, {
    init: function(attrs) {
        this._super(attrs);
    }
});

$('.party').entwine({
   partyID: function() {
       return parseInt( this.attr('data-party-id') );
   }
});
