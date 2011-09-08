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

$('#current_user').entwine({
    onuser_came_online: function(e) {
        $('#party_attendee_' + e.user.id).addClass('online');
    },
    onuser_went_offline: function(e) {
        $('#party_attendee_' + e.user.id).removeClass('online');
    }
});
