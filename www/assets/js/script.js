//= require lib/jquery.js
//= require whowentout.application.js

$.when(app.load()).then(function() {

    $('.visibilitybar').entwine({
        onmatch: function() {
            var self = this;

            self.markSelectedOption(app.currentUser().visibleTo());
            
            app.channel('current_user').bind('user_changed_visibility', function(e) {
                app.currentUser().visibleTo(e.visibility);
                var api = app.getPresenceBeacon();
                if (app.currentUser().visibleTo() == 'online') {
                    api.goOnline();
                }
                else if (app.currentUser().visibleTo() == 'offline') {
                    api.goOffline();
                }
                self.markSelectedOption(app.currentUser().visibleTo());
            });
            
        },
        onunmatch: function() {
        },
        selectOption: function(k) {
            $.getJSON('/user/change_visibility/' + k, function(response) {});
            return this;
        },
        markSelectedOption: function(k) {
            this.find('input:radio:checked').prop('checked', false);
            this.find('input:radio[value=' + k + ']').prop('checked', true);
        },
        val: function(v) {
            if (v === undefined) {
                return this.find('input:radio:checked').val();
            }
            else {
                this.selectOption(v);
            }
        }
    });

    $('.visibilitybar input:radio').entwine({
        onchange: function() {
            this.closest('.visibilitybar').selectOption( this.val() );
        }
    });

});

jQuery(function($) {
    WWO.dialog = $.dialog.create({centerInViewport: true});
});

$('a.confirm').entwine({
    onclick: function(e) {
        var action = this.attr('action') || 'do this';
        var result = confirm("Are you sure you want to " + action + "?");
        if (!result) {
            e.preventDefault();
        }
    }
});
