whowentout = {};

whowentout.initDialog = function () {
    window.dialog = $.dialog.create({centerInViewport:true});
};
$(whowentout.initDialog);

whowentout.showDealDialog = function () {
    $(function () {
        dialog.title('Claim your Deal');
        dialog.loadContent('/events/deal', function () {
            dialog.showDialog('deal_dialog');
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

$(function () {

    $('.event_list :radio').live('click', function () {
        $(this).closest('form').submit();
    });

    function refresh_check_state(el) {
        if ($(el).is(':checked')) {
            $(el).closest('li').addClass('selected');
        }
        else {
            $(el).closest('li').removeClass('selected');
        }
    }

    $('.event_invite input[type=checkbox]').click(function () {
        refresh_check_state(this);
    });

    $('.event_invite input[type=checkbox]').each(function () {
        refresh_check_state(this);
    });

});
