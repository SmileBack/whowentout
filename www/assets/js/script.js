$.when(app.load()).then(function() {

    /*
    var pusher = new Pusher('23a32666914116c9b891', {});
    var channel = pusher.subscribe('user_159');
    channel.bind('datareceived', function(data) {
        console.log(data);
    });
    */

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
                window.location.reload(true);
                //self.markSelectedOption(response.visibility);
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
