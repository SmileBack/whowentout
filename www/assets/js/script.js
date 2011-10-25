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
        selectOption: function(k) {
            var self = this;

            $.getJSON('/user/change_visibility/' + k, function(response) {});

            return this;
        },
        markSelectedOption: function(k) {
            this.find('.selected').removeClass('selected');
            this.getOption(k).addClass('selected');
        },
        getOption: function(k) {
            return this.find('a').attrEq('href', k);
        },
        val: function(v) {
            if (v === undefined) {
                return this.find('.selected').attr('href');
            }
            else {
                this.selectOption(v);
            }
        },
        onunmatch: function() {
        }
    });

    $('.visibilitybar a').entwine({
        onclick: function(e) {
            e.preventDefault();
            this.closest('.visibilitybar').selectOption(this.attr('href'));
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
