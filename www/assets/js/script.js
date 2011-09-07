jQuery(function($) {

    WWO.dialog = $.dialog.create();

    WWO.dialog.anchor('viewport', 'c'); //keeps the dialog box in the center
    $(window).bind('scroll', function() { //even when you scroll
        WWO.dialog.refreshPosition();
    });

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
