$.when(app.load()).then(function() {

    app.channel('current_user').bind('user_changed_visibility', function(e) {
        $('.visibilitybar').markSelectedOption(e.visibility);
    });

    $('.visibilitybar').entwine({
        onmatch: function() {
            var self = this;
            this.data('isLoaded', false);
            $.when(app.currentUser()).then(function(u) {
                self.markSelectedOption(u.visibleTo());
                self.data('isLoaded', true);
            });
        },
        selectOption: function(k) {
            var self = this;
            if (!this.isLoaded())
                return this;

            $.getJSON('/user/change_visibility/' + k, function(response) {
                app.refreshOnlineStatuses();
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
        },
        isLoaded: function() {
            return this.data('isLoaded');
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

    WWO.dialog = $.dialog.create();

    WWO.dialog.anchor('viewport', 'c'); //keeps the dialog box in the center
    $(window).bind('scroll resize', _.debounce(function() {
        WWO.dialog.refreshPosition();
    }, 250));

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
