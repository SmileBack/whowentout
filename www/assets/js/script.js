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

            $.getJSON('/user/change_visibility/' + k, function(response) {
                var api = app.getPresenceBeacon();
                if (response.visibility == 'offline') {
                    api.goOffline();
                }
                else {
                    api.goOnline();
                }
            });

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

//smile help dialog behavior
(function($) {
    $('.smile_help_container .nav_button').entwine({
        onclick: function(e) {
            this._super();
            e.preventDefault();
            var href = this.attr('href');
            var dialog = this.closest('.dialog');
            this.closest('.help_container').find('> *').animate({opacity: 0}, function() {
                $(this).hide();
                $(href).css('opacity', 0).show().animate({opacity: 1}, function() {
                    dialog.refreshPosition();
                });
            });
        }
    });
})(jQuery);

$('a.confirm').entwine({
    onclick: function(e) {
        var action = this.attr('action') || 'do this';
        var result = confirm("Are you sure you want to " + action + "?");
        if (!result) {
            e.preventDefault();
        }
    }
});
