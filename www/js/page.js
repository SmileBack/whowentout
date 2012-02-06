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

whowentout.initDialog = function () {
    if (!window.dialog) {
        var options = {};
        window.dialog = $.dialog.create(options);
    }
}

whowentout.showDealDialog = function (event_id) {
    $(function () {
        whowentout.initDialog();
        dialog.title('Redeem the deal with your phone!');
        dialog.showDialog('deal_dialog');
        dialog.loadContent('/events/' + event_id + '/deal', function () {
            head.js('/js/jquery.maskedinput.js', function () {
                $(".cell_phone_number").mask("(999) 999-9999").trigger('focus');
            });
        });
    });
};

$('.mobile .deal_preview').entwine({
    onmatch: function() {
        this.css('cursor', 'pointer');
    },
    onunmatch: function() {
        this.css('cursor', '');
    },
    onclick: function(e) {
        e.preventDefault();
        this.closest('form').submit();
    }
});

whowentout.showInviteDialog = function (event_id) {
    $(function () {
        whowentout.initDialog();
        dialog.title('Invite your Friends');
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

$('.dialog.invite_dialog').entwine({
    onmaskclick:function () {
        var link = this.find('.cancel_link').attr('href');
        if (link.length == 0)
            return;
        
        window.location = link;
    }
});

whowentout.showEntourageRequestDialog = function() {
    $(function() {
        whowentout.initDialog();

        dialog.title('Entourage Request');
        dialog.showDialog('invite_dialog');
        dialog.loadContent('/entourage/invite');
    });
};

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
        dialog.title('Your Profile Pic');
        dialog.showDialog('profile_edit_dialog');
        dialog.loadContent('/profile/picture/edit', function () {
            $('.profile_pic_crop_form').initCropper();
        });
    });
};

$(function () {
    whowentout.router = Backbone.Router.extend({
        routes:{
            '':'index',
            'events/:id/deal': 'showDealDialog',
            'events/:id/invite': 'showInviteDialog',
            'day/:date': 'displayDate',
            'profile/picture/edit': 'showEditProfilePictureDialog',
            'entourage/invite': 'showEntourageRequestDialog'
        },
        index: function() {
            $('.dialog').hideDialog();
        },
        displayDate: function(date) {
            $('.dialog').hideDialog();
            $('.event_day').updateDate(date);

            var href = '/day/' + date;
            var scrollable = $('#events_date_selector .scrollable');
            var link = scrollable.getElByHref(href);
            scrollable.markSelected(link);
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
        showEntourageRequestDialog: function() {
            whowentout.showEntourageRequestDialog();
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

$('.mobile .show_deal_link').entwine({
    onclick: function(e) {
        e.preventDefault();
        window.location = this.attr('href');
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

$('.event_invite').entwine({
    applySearchFilter: _.debounce(function(keywords) {

        function isMatch(text, keywords) {
            text = text.toLowerCase();
            var parts = keywords.toLowerCase().split(/\W+/);
            for (var i = 0; i < parts.length; i++) {
                if (text.indexOf(parts[i]) == -1) {
                    return false;
                }
            }
            // match every keyword
            return true;
        }

        this.find('li').each(function() {
            var text = $(this).text();
            if (isMatch(text, keywords))
                $(this).show();
            else
                $(this).hide();
        });
    }, 200)
});

$('.event_invite .search').entwine({
    onkeyup: function(e) {
        var keywords = this.val();
        this.closest('.event_invite').applySearchFilter(keywords);
    }
});

$('.event_invite :checkbox').entwine({
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

$('.event_list .filter').entwine({
    applyFilter: function(type) {
        var eventList = this.closest('.event_list');

        function isMatch(el, type) {
            var parts = type.split(/\s+/);
            console.log(parts);
            for (var i = 0; i < parts.length; i++)
                if (el.hasClass(parts[i]))
                    return true;

            return false;
        }

        eventList.find('.event_option').each(function() {
            var el = $(this);
            if (isMatch(el, type))
                el.parent().show();
            else
                el.parent().hide();
        });
    }
});

$('.event_list .filter a').entwine({
    onclick: function(e) {
        e.preventDefault();
        var type = this.attr('href');
        this.closest('.event_list').find('.filter').applyFilter(type);
        this.closest('.filter').find('.selected').removeClass('selected');
        this.addClass('selected');
    }
});

$('.event_list :radio').entwine({
    onclick:function () {
        if (this.val() != 'new')
            this.closest('form').submit();
        else {
            this.closest('form').find('ul .selected').removeClass('selected');
            this.closest('li').addClass('selected');
        }
    }
});

$('.event_day').entwine({
    updateDate:function (date) {
        var self = this;

        if (this.getCurrentDate() == date) // no update necessary
            return;

        var pHtml = this.getUpdatedHtml(date);
        $.when(pHtml).then(function(html) {
            self.replaceHtml(html);
        });
    },
    animateOutOfPage: function(direction) {
        var d = $.Deferred();

        var width = this.outerWidth();
        var exitMargin = direction == 'left' ? '-' + width + 'px' : width + 'px';

        this.animate({marginLeft: exitMargin}, {
            duration: 250,
            complete: function() {
                d.resolve();
            }
        });

        return d.promise();
    },
    animateOntoPage: function(direction, html) {
        var d = $.Deferred();

        var nEl = $(html);
        var width = this.outerWidth();
        var enterMargin = direction == 'left' ? width + 'px' : '-' + width + 'px';

        nEl.css('margin-left', enterMargin);

        this.replaceWith(nEl);
        nEl.animate({marginLeft: 0}, {
            duration: 125,
            complete: function() {
                d.resolve();
            }
        });

        return d.promise();
    },
    replaceHtml: function(html) {
        this.replaceWith(html);
    },
    replaceHtmlAnimated: function(html) {
        var self = this;
        var d = $.Deferred();

        var width = this.outerWidth(true);
        var nEl = $(html);

        var oldDate = self.attr('data-date');
        var newDate = $(nEl).attr('data-date');

        var direction = newDate > oldDate ? 'left' : 'right';
        $.when(this.animateOutOfPage(direction)).then(function() {
            $.when(self.animateOntoPage(direction, html)).then(function() {
                d.resolve();
            });
        });

        return d.promise();
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

$('.tab_tip_wrapper').entwine({
    onmatch: function() {
        this.startBouncing();
    },
    onunmatch: function() {
        this.stopBouncing();
    },
    isBouncing: function() {
        return this.data('bounceID') != null;
    },
    startBouncing: function() {
        if (this.isBouncing())
            return;

        var self = this;
        var id = setInterval(function() {
            self.bounceOnce();
        }, 4000);
        this.data('bounceID', id);
    },
    stopBouncing: function() {
        var id = this.data('bounceID');
        clearInterval(id);
        this.removeData('bounceID');
    },
    bounceOnce: function() {
        var rightMarginStart = this.margin().right;
        var rightMarginEnd = rightMarginStart - 8;
        this.animate({marginRight: rightMarginEnd})
            .animate({marginRight: rightMarginStart});
        return this;
    }
});

$('.inline_label').entwine({
    onmatch: function() {
        this.showLabelText();
        var label = this;

        this.closest('form').bind('submit', function(e) {
            label.hideLabelText();
        });
    },
    onunmatch: function() {
    },
    isEmpty: function() {
        var val = this.val();
        return val == '' || val == this.attr('title');
    },
    showLabelText: function() {
        if (this.isEmpty()) {
            this.val(this.attr('title'));
            this.css('color', 'grey');
        }
    },
    hideLabelText: function() {
        if (this.isEmpty()) {
            this.val('');
            this.css('color', '');
        }
    },
    onfocusin: function() {
        this.hideLabelText();
    },
    onfocusout: function() {
        this.showLabelText();
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
