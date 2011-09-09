jQuery(function($) {

    WWO.dialog = $.dialog.create();

    WWO.dialog.anchor('viewport', 'c'); //keeps the dialog box in the center
    $(window).bind('scroll resize',
            $.debounce(250, function() {
                WWO.dialog.refreshPosition();
            })
    );

});

$('a.help').entwine({
    onmouseenter: function(e) {
        this.notice('Smile is something you can do', ['bl', 'tr']);
    },
    onmouseleave: function(e) {
        $('#notice').hide();
    }
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

$('#current_user').live('user_changed_visibility', function(e) {
    $('.visibilitybar').selectOption(e.visibility);
});

$('.visibilitybar').entwine({
    onmatch: function() {
        var self = this;
        this.data('isLoaded', false);
        $.when( user('current') ).then(function(u) {
            self.selectOption(u.visible_to);
            self.data('isLoaded', true);
        });
    },
    selectOption: function(k) {
        this.find('.selected').removeClass('selected');
        this.getOption(k).addClass('selected');
        return this;
    },
    getOption: function(k) {
        return this.find('a').attrEq('href', k);
    },
    val: function(v) {
        if (v === undefined) {
            return this.find('.selected').attr('href');
        }
        else {
            this.selectOption(k);
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
        if (this.closest('.visibilitybar').isLoaded()) {
            $.getJSON('/user/change_visibility/' + this.attr('href'), function() {
            });
        }
    }
});
