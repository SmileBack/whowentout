//= require underscore.js
//= require jquery.js
//= require backbone.js
//= require head.load.min.js
//= require jquery.dialog.js
//= require dateselector.js

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

var whowentout = window.whowentout = {};

whowentout.showColorOptions = function() {
    whowentout.initDialog();
    $(function() {
        $('#mask').css('opacity', '0');
        dialog.title('Pick your Colors');
        dialog.showDialog('color_options_dialog');
        dialog.setButtons('close');
        dialog.loadContent('/color/options');
    });
}

$('.color_options :submit').entwine({
    onclick: function(e) {
        e.preventDefault();

        var values = this.closest('form').serializeArray();
        var vars = {};
        for (var i = 0; i < values.length; i++) {
            vars['@' + values[i].name] = values[i].value;
        }

        less.modifyVars(vars);
    }
});

$('.show_color_option_link').entwine({
    onclick: function(e) {
        e.preventDefault();
        whowentout.showColorOptions();
    }
});

whowentout.initDialog = function () {
    if (!window.dialog)
        window.dialog = $.dialog.create({centerInViewport:true});
};

whowentout.showDealDialog = function (event_id) {
    $(function () {
        whowentout.initDialog();
        dialog.title('');
        dialog.showDialog('deal_dialog');
        dialog.loadContent('/events/deal/' + event_id, function () {
            head.js('/js/jquery.maskedinput.js', function () {
                $(".cell_phone_number").mask("(999) 999-9999").trigger('focus');
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
    onmaskclick:function () {
        this.find('form').submit();
    }
});

whowentout.showNetworkRequiredDialog = function () {
    $(function () {
        whowentout.initDialog();
        dialog.title('Required Network');
        dialog.showDialog('network_required_dialog');
        dialog.setButtons('ok');
        dialog.loadContent('/home/network_required');
    });
};

$('.dialog.invite_dialog').entwine({
    onmaskclick:function () {
        var link = this.find('.cancel_link').attr('href');
        window.location = link;
    }
});

whowentout.showProfileEditDialog = function () {
    $(function () {
        whowentout.initDialog();
        dialog.title('');
        dialog.showDialog();
        dialog.loadContent('/profile/dialog/picture', function () {
            $('.profile_pic_crop_form').initCropper();
        });
    });
};

$(function () {
    whowentout.router = Backbone.Router.extend({
        routes:{
            '':'index',
            'events/index/:date/deal/:id': 'showDealDialog',
            'events/index/:date/invite/:id': 'showInviteDialog',
            'events/index/:date': 'displayDate',
            'profile/edit/picture': 'showEditProfilePictureDialog'
        },
        index: function() {
            $('.dialog').hideDialog();
        },
        displayDate: function(date) {
            $('.event_day').updateDate(date);
        },
        showDealDialog: function(date, event_id) {
            whowentout.showDealDialog(event_id);
        },
        showInviteDialog: function(date, event_id) {
            whowentout.showInviteDialog(event_id);
        },
        showEditProfilePictureDialog: function() {
            whowentout.showProfileEditDialog();
        },
        defaultRoute:function () {
        }
    });

    whowentout.router = new whowentout.router();

    Backbone.history.start({pushState:true});
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

$('.profile_edit_picture_link').entwine({
    onclick: function(e) {
        e.preventDefault();
        whowentout.router.navigate('/profile/edit/picture', true);
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

$('.event_day').entwine({
    updateDate:function (date) {
        var self = this;

        if (this.getCurrentDate() == date) // no update necessary
            return;

        var pHtml = this.getUpdatedHtml(date);
        $.when(pHtml).then(function(html) {
            var nEl = $(html);
            var date = $(nEl).attr('data-date');
            self.replaceWith(html);
        });
    },
    getCurrentDate: function() {
        return this.attr('data-date');
    },
    getUpdatedHtml:function (date) {
        var url = '/events/index_ajax/' + date;
        return $.ajax({
            url:url,
            type:'post',
            success:function (html) {
            }
        });
    }
});
