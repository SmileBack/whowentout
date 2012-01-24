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
        dialog.loadContent('/events/' + event_id + '/deal', function () {
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
        dialog.loadContent('/events/' + event_id + '/invite');
    });
};

$('.dialog.deal_dialog').entwine({
    onmaskclick: function() {
        this.find('form').submit();
    }
});

$('.dialog.profile_edit_dialog').entwine({
    onmaskclick: function() {
        this.find('.profile_pic_crop_form').submit();
    }
});

whowentout.showNetworkRequiredDialog = function () {
    $(function () {
        whowentout.initDialog();
        dialog.title('Required Network');
        dialog.showDialog('network_required_dialog');
        dialog.setButtons('ok');
        dialog.loadContent('/networks_required');
    });
};

whowentout.showProfileEditDialog = function () {
    $(function () {
        whowentout.initDialog();
        dialog.title('');
        dialog.showDialog('profile_edit_dialog');
        dialog.loadContent('/profile/picture/edit', function () {
            $('.profile_pic_crop_form').initCropper();
        });
    });
};

$('.dialog.invite_dialog').entwine({
    onmaskclick:function () {
        var link = this.find('.cancel_link').attr('href');
        window.location = link;
    }
});

$(function () {
    whowentout.router = Backbone.Router.extend({
        routes:{
            '':'index',
            'events/:id/deal': 'showDealDialog',
            'events/:id/invite': 'showInviteDialog',
            'day/:date': 'displayDate',
            'profile/picture/edit': 'showEditProfilePictureDialog'
        },
        index: function() {
            $('.dialog').hideDialog();
        },
        displayDate: function(date) {
            $('.dialog').hideDialog();
            $('.event_day').updateDate(date);
        },
        showDealDialog: function(event_id) {
            whowentout.showDealDialog(event_id);
        },
        showInviteDialog: function(event_id) {
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

$('.action').entwine({
    onclick: function(e) {
        e.preventDefault();
        var href = this.attr('href');
        whowentout.router.navigate(href, true);
    }
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
    onchange: function (e) {
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
        var url = '/day/' + date;
        return $.ajax({
            url:url,
            type:'post',
            success:function (html) {
            }
        });
    }
});

whowentout.refreshDateSelector = _.debounce(function() {
    $('#events_date_selector .scrollable').refreshScrollPosition();
}, 250);
$(window).resize(whowentout.refreshDateSelector);

function hideAddressBar()
{
  if(!window.location.hash)
  {
      if(document.height < window.outerHeight)
      {
          document.body.style.height = (window.outerHeight + 50) + 'px';
      }

      setTimeout( function(){ window.scrollTo(0, 1); }, 50 );
  }
}
window.addEventListener("load", function(){ if(!window.pageYOffset){ hideAddressBar(); } } );
window.addEventListener("orientationchange", hideAddressBar );
