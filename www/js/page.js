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

head.css = function (path) {
    $("head").append("<link>");
    var css = $("head").children(":last");
    css.attr({
        rel:'stylesheet',
        type:'text/css',
        href:path
    });
};

var whowentout = window.whowentout = {};

whowentout.initDialog = function () {
    if (!window.dialog) {
        var options = {};
        window.dialog = $.dialog.create(options);
    }
};

whowentout.showDialog = function (title, url, cls, onComplete) {
    onComplete = onComplete || function () {};
    $(function () {
        var delay = whowentout.dialogDelay || 0;
        setTimeout(function () {
            whowentout.initDialog();
            dialog.title(title);
            dialog.showDialog(cls);
            dialog.loadContent(url, onComplete);
        }, delay);
    });
};

whowentout.showDealDialog = function (event_id) {
    whowentout.showDialog('Redeem the deal with your phone!',
    '/events/' + event_id + '/deal', 'deal_dialog',
    function () {
        head.js('/js/jquery.maskedinput.js', function () {
            $(".cell_phone_number").mask("(999) 999-9999").trigger('focus');
        });
    });
};

whowentout.showInviteDialog = function (event_id) {
    whowentout.showDialog('Invite your Friends', '/events/' + event_id + '/invite', 'invite_dialog');
};

whowentout.showEntourageRequestDialog = function () {
    whowentout.showDialog('Entourage Request', '/entourage/invite', 'invite_dialog');
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

whowentout.showDisabledOnPhoneDialog = function () {
    $(function () {
        whowentout.initDialog();
        dialog.title('Use Your Computer');
        dialog.message(
        '<img src="/images/laptop.png" />'
        + '<p>This feature is available on your computer.</p>'
        );
        dialog.setButtons('ok');
        dialog.showDialog('disabled_on_phone');
    });
};

$('.mobile .deal_preview').entwine({
    onmatch:function () {
        this.css('cursor', 'pointer');
    },
    onunmatch:function () {
        this.css('cursor', '');
    },
    onclick:function (e) {
        e.preventDefault();
        this.closest('form').submit();
    }
});

$('.dialog.deal_dialog').entwine({
    onmaskclick:function () {
        this.find('form').submit();
    }
});

$('.dialog.profile_edit_dialog').entwine({
    onmaskclick:function () {
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

$(function () {
    whowentout.router = Backbone.Router.extend({
        routes:{
            '':'index',
            'events/:id/deal':'showDealDialog',
            'events/:id/invite':'showInviteDialog',
            'day/:date':'displayDate',
            'profile/picture/edit':'showEditProfilePictureDialog',
            'entourage/invite':'showEntourageRequestDialog'
        },
        index:function () {
            $('.dialog').hideDialog();
        },
        displayDate:function (date) {
            $('.dialog').hideDialog();
            $('.event_day').updateDate(date);

            var href = '/day/' + date;
            var scrollable = $('#events_date_selector .scrollable');
            var link = scrollable.getElByHref(href);
            scrollable.markSelected(link);
        },
        showDealDialog:function (event_id) {
            whowentout.showDealDialog(event_id, 5000);
        },
        showInviteDialog:function (event_id) {
            whowentout.showInviteDialog(event_id, 5000);
        },
        showEditProfilePictureDialog:function () {
            whowentout.showProfileEditDialog();
        },
        showEntourageRequestDialog:function () {
            whowentout.showEntourageRequestDialog();
        },
        defaultRoute:function () {
        }
    });

    whowentout.router = new whowentout.router();

    Backbone.history.start({pushState:true});
});

$('.action').entwine({
    onclick:function (e) {
        e.preventDefault();
        var href = this.attr('href');
        whowentout.router.navigate(href, true);
    }
});

$('.mobile .entourage_request_link').entwine({
    onclick:function (e) {
        e.preventDefault();
        whowentout.showDisabledOnPhoneDialog();
    }
});

$('.mobile .show_deal_link').entwine({
    onclick:function (e) {
        e.preventDefault();
        window.location = this.attr('href');
    }
});

$('#flash_message').entwine({
    onmatch:function () {
        var flashMessage = this;
        setTimeout(function () {
            flashMessage.fadeOut();
        }, 5000);
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

$('.profile_pic_upload_form, .profile_pic_facebook_form').entwine({
    onsubmit:function (e) {
        this.closest('.dialog').showLoadingMessage();
    }
});

$('.profile_pic_upload_form input[type=file]').entwine({
    onchange:function (e) {
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
    applySearchFilter:_.debounce(function (keywords) {

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

        this.find('li').each(function () {
            var text = $(this).text();
            if (isMatch(text, keywords))
                $(this).show();
            else
                $(this).hide();
        });
    }, 200)
});

$('.event_invite .search').entwine({
    onkeyup:function (e) {
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
    applyFilter:function (type) {
        var eventList = this.closest('.event_list');

        function isMatch(el, type) {
            var parts = type.split(/\s+/);
            console.log(parts);
            for (var i = 0; i < parts.length; i++)
                if (el.hasClass(parts[i]))
                    return true;

            return false;
        }

        eventList.find('.event_option').each(function () {
            var el = $(this);
            if (isMatch(el, type))
                el.parent().show();
            else
                el.parent().hide();
        });
    }
});

$('.event_list .filter a').entwine({
    onclick:function (e) {
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

        this.showLoadingMessage();

        var pHtml = this.getUpdatedHtml(date);
        $.when(pHtml).then(function (html) {
            self.replaceHtml(html);
        });
    },
    showLoadingMessage:function () {
        this.find('.event_list_wrapper').addClass('loading');
        return this;
    },
    animateOutOfPage:function (direction) {
        var d = $.Deferred();

        var width = this.outerWidth();
        var exitMargin = direction == 'left' ? '-' + width + 'px' : width + 'px';

        this.animate({marginLeft:exitMargin}, {
            duration:250,
            complete:function () {
                d.resolve();
            }
        });

        return d.promise();
    },
    animateOntoPage:function (direction, html) {
        var d = $.Deferred();

        var nEl = $(html);
        var width = this.outerWidth();
        var enterMargin = direction == 'left' ? width + 'px' : '-' + width + 'px';

        nEl.css('margin-left', enterMargin);

        this.replaceWith(nEl);
        nEl.animate({marginLeft:0}, {
            duration:125,
            complete:function () {
                d.resolve();
            }
        });

        return d.promise();
    },
    replaceHtml:function (html) {
        this.replaceWith(html);
    },
    replaceHtmlAnimated:function (html) {
        var self = this;
        var d = $.Deferred();

        var width = this.outerWidth(true);
        var nEl = $(html);

        var oldDate = self.attr('data-date');
        var newDate = $(nEl).attr('data-date');

        var direction = newDate > oldDate ? 'left' : 'right';
        $.when(this.animateOutOfPage(direction)).then(function () {
            $.when(self.animateOntoPage(direction, html)).then(function () {
                d.resolve();
            });
        });

        return d.promise();
    },
    getCurrentDate:function () {
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

$('.desktop .tab_tip_wrapper').entwine({
    onmatch:function () {
        this.startBouncing();
    },
    onunmatch:function () {
        this.stopBouncing();
    },
    isBouncing:function () {
        return this.data('bounceID') != null;
    },
    startBouncing:function () {
        if (this.isBouncing())
            return;

        var self = this;
        var id = setInterval(function () {
            self.bounceOnce();
        }, 4000);
        this.data('bounceID', id);
    },
    stopBouncing:function () {
        var id = this.data('bounceID');
        clearInterval(id);
        this.removeData('bounceID');
    },
    bounceOnce:function () {
        var rightMarginStart = this.margin().right;
        var rightMarginEnd = rightMarginStart - 8;
        this.animate({marginRight:rightMarginEnd})
        .animate({marginRight:rightMarginStart});
        return this;
    }
});

$('.inline_label').entwine({
    onmatch:function () {
        this.showLabelText();
        var label = this;

        this.closest('form').bind('submit', function (e) {
            label.hideLabelText();
        });
    },
    onunmatch:function () {
    },
    isEmpty:function () {
        var val = this.val();
        return val == '' || val == this.attr('title');
    },
    showLabelText:function () {
        if (this.isEmpty()) {
            this.val(this.attr('title'));
            this.css('color', 'grey');
        }
    },
    hideLabelText:function () {
        if (this.isEmpty()) {
            this.val('');
            this.css('color', '');
        }
    },
    onfocusin:function () {
        this.hideLabelText();
    },
    onfocusout:function () {
        this.showLabelText();
    }
});

whowentout.refreshDateSelector = _.debounce(function () {
    $('#events_date_selector .scrollable').refreshScrollPosition();
}, 250);
$(window).resize(whowentout.refreshDateSelector);

function hideAddressBar() {
    if (!window.location.hash) {
        if (document.height < window.outerHeight) {
            document.body.style.height = (window.outerHeight + 50) + 'px';
        }

        setTimeout(function () {
            window.scrollTo(0, 1);
        }, 50);
    }
}
window.addEventListener("load", function () {
    if (!window.pageYOffset) {
        hideAddressBar();
    }
});
window.addEventListener("orientationchange", hideAddressBar);

$('.debug_panel').entwine({
    onmatch:function () {
        this.collapse();
    },
    onunmatch:function () {
    },
    isExpanded:function () {
        return this.hasClass('expanded');
    },
    toggleExpand:function () {
        if (this.isExpanded())
            this.collapse();
        else
            this.expand();

        return this;
    },
    expand:function () {
        this.removeClass('collapsed').addClass('expanded');
        return this;
    },
    collapse:function () {
        this.removeClass('expanded').addClass('collapsed');
        return this;
    }
});

$('.debug_panel table').entwine({
    onmatch:function () {
        var self = this;
        head.css('/css/jquery.datatables.css');
        head.js('/js/jquery.datatables.js', function () {
            self.dataTable();
        });
        this.collapse();
    },
    onunmatch:function () {
    }
});

$('.debug_panel .expand').entwine({
    onclick:function (e) {
        e.preventDefault();
        this.closest('.debug_panel').expand();
    }
});

$('.debug_panel .collapse').entwine({
    onclick:function (e) {
        e.preventDefault();
        this.closest('.debug_panel').collapse();
    }
});

$('.tab_panel').entwine({
    onmatch:function () {
        var key = this.find('.tabs a:first').tabKey();
        this.selectTab(key);
    },
    onunmatch:function () {
    },
    selectTab:function (key) {
        this.find('.pane').hide();
        this.find('.pane').filter('.' + key).show();
    }
});

$('.tab_panel .tabs a').entwine({
    onclick:function (e) {
        e.preventDefault();
        this.closest('.tab_panel').selectTab(this.tabKey());
    },
    tabKey:function () {
        return this.attr('href').replace(/#/g, '');
    }
});

$('.expandable').entwine({
    onmatch:function () {
        if (this.find('> li').length < 2)
            return;

        this.restOfItems().hide();
        this.find('> li:first').append('<a href="#view_more" class="view_more">view more</a>');
        this.find('> li:last').append('<a href="#view_less" class="view_less">view less</a>');
    },
    onunmatch:function () {
    },
    firstItem:function () {
        return this.find('> li:first');
    },
    restOfItems:function () {
        return this.firstItem().nextAll();
    },
    viewMoreLink:function () {
        return this.find('> li > .view_more');
    },
    viewLessLink:function () {
        return this.find('> li > .view_less');
    },
    viewMore:function () {
        this.viewMoreLink().hide();
        this.viewLessLink().show();
        this.restOfItems().fadeIn();
    },
    viewLess:function () {
        this.viewLessLink().hide();
        this.viewMoreLink().show();
        this.restOfItems().fadeOut();
    }
});

$('.expandable .view_more').entwine({
    onclick:function (e) {
        e.preventDefault();
        this.closest('.expandable').viewMore();
    }
});

$('.expandable .view_less').entwine({
    onclick:function (e) {
        e.preventDefault();
        this.closest('.expandable').viewLess();
    }
});

