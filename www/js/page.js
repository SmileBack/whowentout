$.fn.whenShown = function(fn) {
    var props = { position: 'absolute', visibility: 'hidden', display: 'block' },
    hiddenParents = $(this).parents().andSelf().not(':visible');

    //set style for hidden elements that allows computing
    var oldProps = [];
    hiddenParents.each(function() {
        var old = {};

        for (var name in props) {
            old[ name ] = this.style[ name ];
            this.style[ name ] = props[ name ];
        }

        oldProps.push(old);
    });

    var result = fn.call($(this));

    //reset styles
    hiddenParents.each(function(i) {
        var old = oldProps[i];
        for (var name in props) {
            this.style[ name ] = old[ name ];
        }
    });

    return result;
};

$.fn.hiddenDimensions = function(includeMargin) {
    return this.whenShown(function() {
        return {
            width: this.width(),
            outerWidth: this.outerWidth(includeMargin),
            innerWidth: this.innerWidth(),
            height: this.height(),
            innerHeight: this.innerHeight(),
            outerHeight: this.outerHeight(includeMargin),
            margin: $.fn.margin ? this.margin() : null,
            padding: $.fn.padding ? this.padding() : null,
            border: $.fn.border ? this.border() : null
        };
    });
};

$('.scrollable').entwine({
    onmatch: function() {
        var index = this.getIndex() || 0;
        this.setIndex(index);
    },
    onunmatch: function() {},
    animateToIndex: function(index, onComplete) {
        var self = this;
        onComplete = onComplete || function() {};
        var x = this._indexToX(index);
        this._animateToX(x, function() {
            self.data('index', index);
            onComplete.call(self);
        });
    },
    animateOffset: function(offset) {
        var index = this.getIndex() + offset;
        this.animateToIndex(index);
    },
    setIndex: function(index) {
        var x = this._indexToX(index);
        this._setX(x);
    },
    getIndex: function() {
        return this.data('index');
    },
    _setX: function(x) {
        this.find('> .items').css({'margin-left': -x + 'px'});
    },
    _animateToX: function(x, onComplete) {
        var self = this;
        this.find('> .items').animate({'margin-left': -x + 'px'}, {
            duration: 300,
            complete: onComplete
        });
    },
    _getElAtIndex: function(index) {
        return this.find('> .items > *').eq(index);
    },
    _indexToX: function(index) {
        var width = 0;
        var elementsBefore = this._getElAtIndex(index).prevAll();
        elementsBefore.each(function() {
            var dimensions = $(this).hiddenDimensions(true);
            width += dimensions.outerWidth;
        });
        return width;
    }
});

$('#events_date_selector .items a').entwine({
    onclick: function(e) {
        e.preventDefault();
        var index = this.index();
        var href = this.attr('href');
        this.closest('#events_date_selector')
            .find('.scrollable').animateToIndex(index - 3, function() {
            window.location = href;
        });
    }
});

$('#events_date_selector .prev').entwine({
    onclick: function(e) {
        e.preventDefault();
        this.scrollableEl().animateOffset(-1);
    },
    scrollableEl: function() {
        return this.closest('#events_date_selector').find('.scrollable');
    }
});

$('#events_date_selector .next').entwine({
    onclick: function(e) {
        e.preventDefault();
        this.scrollableEl().animateOffset(+1);
    },
    scrollableEl: function() {
        return this.closest('#events_date_selector').find('.scrollable');
    }
});

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

$('#flash_message').entwine({
    onmatch: function() {
        var flashMessage = this;
        setTimeout(function() {
            flashMessage.fadeOut();
        }, 3000);
    },
    onunmatch: function() {
    }
});

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
        aspectRatio:0.75,
        boxWith:250,
        boxHeight:250,
        setSelect:[box.x, box.y, box.x + box.width, box.y + box.height],
        onChange:on_jcrop_coords_change,
        onSelect:on_jcrop_coords_change,
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
