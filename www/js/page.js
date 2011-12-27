whowentout = {};

whowentout.initDialog = function () {
    window.dialog = $.dialog.create({centerInViewport:true});
};
$(whowentout.initDialog);

whowentout.showDealDialog = function () {
    $(function () {
        dialog.title('Claim your Deal');
        dialog.showDialog();
        dialog.loadContent('/events/deal', function () {
        });
    });
};

whowentout.showInviteDialog = function(event_id) {
    $(function() {
        dialog.title('');
        dialog.showDialog();
        dialog.loadContent('/events/invite/' + event_id, function() {
        });
    });
};

$('a').entwine({
    onmousedown:function () {
        this._super();
        this.addClass('mousedown');
    },
    onmouseup:function () {
        this.removeClass('mousedown');
    }
});

$('.edit_cell_phone_number').entwine({
    onclick: function(e) {
        e.preventDefault();
        $('.cell_phone_number').removeClass('inline').focus().select();
    }
});

$('.event_invite_link').entwine({
    onclick: function(e) {
        e.preventDefault();
        var eventID = this.eventID();
        whowentout.showInviteDialog(eventID);
    },
    eventID: function() {
        return this.data('event-id');
    }
});

$('.event_invite input[type=checkbox]').entwine({
    onmatch: function(e) {
        this._super(e);
        this.refreshCheckState();
    },
    onunmatch: function(e) {
        this._super(e);
    },
    onclick: function(e) {
        this.refreshCheckState();
    },
    refreshCheckState: function() {
        if (this.is(':checked')) {
            this.closest('li').addClass('selected');
        }
        else {
            this.closest('li').removeClass('selected');
        }
    }
});

$('.event_list :radio').entwine({
    onclick: function() {
        this.closest('form').submit();
    }
});
