$.fn.whenShown = function (fn) {
    var props = { position:'absolute', visibility:'hidden', display:'block' },
    hiddenParents = $(this).parents().andSelf().not(':visible');

    //set style for hidden elements that allows computing
    var oldProps = [];
    hiddenParents.each(function () {
        var old = {};

        for (var name in props) {
            old[ name ] = this.style[ name ];
            this.style[ name ] = props[ name ];
        }

        oldProps.push(old);
    });

    var result = fn.call($(this));

    //reset styles
    hiddenParents.each(function (i) {
        var old = oldProps[i];
        for (var name in props) {
            this.style[ name ] = old[ name ];
        }
    });

    return result;
};

$.fn.hiddenDimensions = function (includeMargin) {
    return this.whenShown(function () {
        return {
            width:this.width(),
            outerWidth:this.outerWidth(includeMargin),
            innerWidth:this.innerWidth(),
            height:this.height(),
            innerHeight:this.innerHeight(),
            outerHeight:this.outerHeight(includeMargin),
            margin:$.fn.margin ? this.margin() : null,
            padding:$.fn.padding ? this.padding() : null,
            border:$.fn.border ? this.border() : null
        };
    });
};

(function ($) {
    if ($.browser.msie == false)
        return;

    //fileinputs in satans browser require a blur to trigger a change event
    $('input[type=file]').live('click', function (e) {
        var self = this;
        var blur = function () {
            $(self).blur();
        };
        setTimeout(blur, 0);
    });


})(jQuery);

$('.scrollable').entwine({
    onmatch:function () {
        var index = this.getIndex() || 0;
        this.setIndex(index);
    },
    onunmatch:function () {
    },
    animateToIndex:function (index, onComplete) {
        var self = this;
        onComplete = onComplete || function () {
        };

        var x = this._indexToX(index);
        this._animateToX(x, function () {
            self.data('index', index);
            onComplete.call(self);
        });
    },
    animateOffset:function (offset, onComplete) {
        var index = this.getIndex() + offset;
        this.animateToIndex(index, onComplete);
    },
    setIndex:function (index) {
        var x = this._indexToX(index);
        this._setX(x);
    },
    getIndex:function () {
        return this.data('index');
    },
    getCenterEl:function () {
        var index = this.getIndex();
        return this.getElAtIndex(index + 3);
    },
    _setX:function (x) {
        this.find('> .items').css({'margin-left':-x + 'px'});
    },
    _animateToX:function (x, onComplete) {
        var self = this;
        this.find('> .items').animate({'margin-left':-x + 'px'}, {
            duration:300,
            complete:onComplete
        });
    },
    getElAtIndex:function (index) {
        return this.find('> .items > *').eq(index);
    },
    _indexToX:function (index) {
        var width = 0;
        var elementsBefore = this.getElAtIndex(index).prevAll();
        elementsBefore.each(function () {
            var dimensions = $(this).hiddenDimensions(true);
            width += dimensions.outerWidth;
        });
        return width;
    }
});

$('#events_date_selector .items a').entwine({
    onclick:function (e) {
        e.preventDefault();
        var index = this.index();
        var href = this.attr('href');
        this.closest('#events_date_selector')
        .find('.scrollable').animateToIndex(index - 3, function () {
            window.location = href;
        });
    }
});

$('#events_date_selector .prev').entwine({
    onclick:function (e) {
        e.preventDefault();
        var scrollable = this.scrollableEl();
        scrollable.animateOffset(-1, function () {
            window.location = scrollable.getCenterEl().attr('href');
        });
    },
    scrollableEl:function () {
        return this.closest('#events_date_selector').find('.scrollable');
    }
});

$('#events_date_selector .next').entwine({
    onclick:function (e) {
        e.preventDefault();
        var scrollable = this.scrollableEl();
        scrollable.animateOffset(+1, function () {
            window.location = scrollable.getCenterEl().attr('href');
        });
    },
    scrollableEl:function () {
        return this.closest('#events_date_selector').find('.scrollable');
    }
});

var whowentout = window.whowentout = {};

whowentout.initDialog = function () {
    if (!window.dialog)
        window.dialog = $.dialog.create({centerInViewport:true});
};

whowentout.showDealDialog = function (event_id) {
    $(function () {
        whowentout.initDialog();
        dialog.title('Claim your Deal');
        dialog.showDialog('deal_dialog');
        dialog.loadContent('/events/deal/' + event_id, function () {
            head.js('/js/jquery.maskedinput.js', function() {
                $(".cell_phone_number").backgroundMask("(999) 999-9999");
            });
        });
    });
};

whowentout.showInviteDialog = function (event_id) {
    $(function () {
        whowentout.initDialog();
        dialog.title('');
        dialog.showDialog('invite_dialog');
        dialog.loadContent('/events/invite/' + event_id);
    });
};
$('.dialog.deal_dialog').entwine({
    onmaskclick: function() {
        this.find('form').submit();
    }
});

$('.dialog.invite_dialog').entwine({
    onmaskclick: function() {
        var link = this.find('.cancel_link').attr('href');
        window.location = link;
    }
});

whowentout.showProfileEditDialog = function () {
    $(function () {
        whowentout.initDialog();
        dialog.title('');
        dialog.showDialog();
        dialog.loadContent('/profile/edit', function () {
            $('.profile_pic_crop_form').initCropper();
        });
    });
};

$(function () {
    whowentout.router = Backbone.Router.extend({
        routes:{
            '': 'index',
            'deal/:id':'showDealDialog',
            'invite/:id':'showInviteDialog'
        },
        index: function () {
            $('.dialog').hideDialog();
        },
        showDealDialog:function (event_id) {
            whowentout.showDealDialog(event_id);
        },
        showInviteDialog:function (event_id) {
            whowentout.showInviteDialog(event_id);
        },
        defaultRoute:function () {
        }
    });
    whowentout.router = new whowentout.router();
    
    Backbone.history.start();
});

$('#flash_message').entwine({
    onmatch:function () {
        var flashMessage = this;
        setTimeout(function () {
            flashMessage.fadeOut();
        }, 3000);
    },
    onunmatch:function () {
    }
});

$('.profile_pic_crop_form').entwine({
    onmatch:function () {
//        this.initCropper();
    },
    onunmatch:function () {
    },
    getCropBox:function () {
        var vals = this.serializeArray();
        var box = {};
        for (var i = 0; i < vals.length; i++) {
            box[ vals[i].name ] = parseInt(vals[i].value);
        }
        return box;
    },
    setCropBox:function (x, y, width, height) {
        this.find('input[name=x]').val(x);
        this.find('input[name=y]').val(y);
        this.find('input[name=width]').val(width);
        this.find('input[name=height]').val(height);
    },
    initCropper:function () {
        var self = this;
        head.js('/js/jquery.jcrop.js', function () {
            function onInit() {
                var api = this;
            }

            function onCoordsChange(coords) {
                self.setCropBox(coords.x, coords.y, coords.w, coords.h);
            }

            var box = self.getCropBox();
            var options = {
                aspectRatio:0.75,
                boxWith:400,
                boxHeight:400,
                setSelect:[box.x, box.y, box.x + box.width, box.y + box.height],
                onChange:onCoordsChange,
                onSelect:onCoordsChange
            };

            $('.profile_pic_source').Jcrop(options, onInit);
        });
    }
});

$('.edit_profile_link').entwine({
    onclick:function (e) {
        e.preventDefault();
        whowentout.showProfileEditDialog();
    }
});

$('.profile_pic_upload_form input[type=file]').entwine({
    onchange:function () {
        this.closest('form').submit();
    }
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
