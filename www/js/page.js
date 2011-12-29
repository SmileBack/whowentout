var whowentout = window.whowentout = {};

whowentout.initDialog = function () {
    if (!window.dialog)
        window.dialog = $.dialog.create({centerInViewport:true});
};

whowentout.showDealDialog = function () {
    $(function () {
        whowentout.initDialog();
        dialog.title('Claim your Deal');
        dialog.showDialog();
        dialog.loadContent('/events/deal');
    });
};

whowentout.showInviteDialog = function (event_id) {
    $(function () {
        whowentout.initDialog();
        dialog.title('');
        dialog.showDialog();
        dialog.loadContent('/events/invite/' + event_id);
    });
};

$(function () {
    var api = null;

    var crop_form = $('.profile_pic_crop_form');

    function get_crop_box() {
        var vals = crop_form.serializeArray();
        var box = {};
        for (var i = 0; i < vals.length; i++) {
            box[ vals[i].name ] = parseInt(vals[i].value);
        }
        return box;
    }

    function on_jcrop_init() {
        api = this;
    }

    function on_jcrop_coords_change(coords) {
        crop_form.find('input[name=x]').val(coords.x);
        crop_form.find('input[name=y]').val(coords.y);
        crop_form.find('input[name=width]').val(coords.w);
        crop_form.find('input[name=height]').val(coords.h);
    }

    var box = get_crop_box();
    var jcrop_options = {
        aspectRatio: 0.75,
        boxWith: 250,
        boxHeight: 250,
        setSelect: [box.x, box.y, box.x + box.width, box.y + box.height],
        onChange: on_jcrop_coords_change,
        onSelect: on_jcrop_coords_change,
    };

    $('.profile_pic_source').Jcrop(jcrop_options, on_jcrop_init);

});

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
    onclick:function (e) {
        e.preventDefault();
        $('.cell_phone_number').removeClass('inline').focus().select();
    }
});

$('.event_invite_link').entwine({
    onclick:function (e) {
        e.preventDefault();
        var eventID = this.eventID();
        whowentout.showInviteDialog(eventID);
    },
    eventID:function () {
        return this.data('event-id');
    }
});

$('.event_invite input[type=checkbox]').entwine({
    onmatch:function (e) {
        this._super(e);
        this.refreshCheckState();
    },
    onunmatch:function (e) {
        this._super(e);
    },
    onclick:function (e) {
        this.refreshCheckState();
    },
    refreshCheckState:function () {
        if (this.is(':checked')) {
            this.closest('li').addClass('selected');
        }
        else {
            this.closest('li').removeClass('selected');
        }
    }
});

$('.event_list :radio').entwine({
    onclick:function () {
        this.closest('form').submit();
    }
});
