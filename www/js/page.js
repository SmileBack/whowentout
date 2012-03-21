//= require underscore.js
//= require jquery.js
//= require handlebars.js
//= require backbone.js
//= require head.load.min.js
//= require jquery.dialog.js
//= require dateselector.js
//= require jquery.easing.js
//= require jquery.inview.js

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

var User = Backbone.Model.extend({});

(function() {
    var Template = function(html) {
        this.html = html;
        this.fn = Handlebars.compile(this.html);
    };
    Template.prototype.render = function(data) {
        return this.fn(data);
    };
    Template.prototype.getSubtemplateNames = function() {
        var regex = /\{\{\s*include\s*"([^"]+)/g, matches, names = [];
        while (matches = regex.exec(this.html)) {
            names.push(matches[1]);
        }
        return _.uniq(names);
    };

    var uniqueID = function() {
        var date = new Date();
        return date.getTime();
    };

    var templateHtml = {};
    $.templateHtml = function(name) {
        if (templateHtml[name])
            return templateHtml[name];

        var pHtml = $.Deferred();

        var container = $('script#' + name);
        if (container.length > 0) {
            templateHtml[name] = container.html();
            pHtml.resolve(templateHtml[name]);
        }
        else {
            $.get('/templates/' + name + '.html?version=' + uniqueID(), function(html) {
                templateHtml[name] = html;
                pHtml.resolve(templateHtml[name]);
            });
        }

        return pHtml.promise();
    };

    var templates = {};
    $.template = function(name) {
        if (templates[name])
            return templates[name];

        var pTemplate = $.Deferred();

        $.when($.templateHtml(name)).then(function(html) {
            var template = templates[name] = new Template(html);
            Handlebars.registerPartial(name, template.fn);

            var pSubtemplates = _.map(template.getSubtemplateNames(), $.template);
            $.when.apply(this, pSubtemplates).then(function() {
                pTemplate.resolve(templates[name]);
            });
        });

        return pTemplate.promise();
    };

    var getTemplate = function(el) {
        return $(el).data('__template');
    };

    var setTemplate = function(el, template) {
        $(el).data('__template', template);
    };

    var applyTemplate = function(el, name, data) {
        var pTemplate = $.template(name);

        $.when(pTemplate).then(function(template) {
            var nEl = $(template.render(data));
            nEl.data(data);
            setTemplate(nEl, template);

            $(el).replaceWith(nEl);
        });

        return pTemplate;
    };

    $.fn.template = function(name, data) {
        if (arguments.length == 0)
            return getTemplate(this);
        else
            return applyTemplate(this, name, data);
    };

})();

var HandlebarsHelpers = {
    include: function(partialName, options) {
        // Find the partial in question.
        var partial = Handlebars.partials[partialName];

        // Build the new context; if we don't include `this` we get functionality
        // similar to {% include ... with ... only %} in Django.
        var context = _.extend({}, this, options.hash);

        // Render, marked as safe so it isn't escaped.
        return new Handlebars.SafeString(partial(context));
    },
    debug: function(value) {
        console.log("Current Context");
        console.log("====================");
        console.log(this);

        if (value) {
            console.log("Value");
            console.log("====================");
            console.log(value);
        }
    },
    formatDate: function(date, block) {
        format = block.hash['format'] || 'l M jS';
        return date.format(format);
    },
    nightOf: function(date) {
        return date.format('l') + ' night';
    }
};

_.each(HandlebarsHelpers, function(fn, helper) {
    Handlebars.registerHelper(helper, fn);
});

var whowentout = window.whowentout = {};
_(whowentout).extend(Backbone.Events);

if (window.settings.currentUser)
    whowentout.currentUser = new User(window.settings.currentUser);

whowentout.initDialog = function () {
    if (!window.dialog) {
        var options = {
            refreshPosition: $('body').hasClass('desktop')
        };
        window.dialog = $.dialog.create(options);
    }
};

whowentout.showDialog = function (options) {
    var defaults = {
        title: '',
        subtitle: '',
        message: false,
        url: '',
        cls: '',
        onComplete: function() {},
        buttons: 'none'
    };
    var options = $.extend({}, defaults, options);

    $(function () {
        whowentout.initDialog();

        dialog.title(options.title);
        dialog.subtitle(options.subtitle);
        dialog.setButtons(options.buttons);

        if (options.message !== false)
            dialog.message(options.message);
        else
            dialog.loadContent(options.url, options.onComplete);

        dialog.showDialog(options.cls);
    });
};

whowentout.showCheckinExplanationDialog = function(event_id) {
    whowentout.showDialog({
        title: 'Checked In',
        url: '/checkin/explanation/' + event_id,
        cls: 'checkin_explanation_dialog'
    });
};

whowentout.showDealDialog = function (event_id) {
    whowentout.showDialog({
        title: 'Claim Your Deal',
        url: '/events/' + event_id + '/deal',
        cls: 'deal_dialog',
        onComplete: function() {
            head.js('/js/jquery.maskedinput.js', function () {
                $(".cell_phone_number").mask("(999) 999-9999").trigger('focus');
            });
        }
    });
};

whowentout.showInviteDialog = function (event_id) {
    whowentout.showDialog({
        title: 'Invite Your Friends',
        url: '/events/' + event_id + '/invite',
        cls: 'invite_dialog'
    });
};

whowentout.showEntourageRequestDialog = function () {
    whowentout.showDialog({
        title: 'Entourage Requests',
        url: '/entourage/invite',
        cls: 'invite_dialog'
    });
};

whowentout.showNetworkRequiredDialog = function () {
    whowentout.showDialog({
        title: 'Required Network',
        cls: 'network_required_dialog',
        buttons: 'ok',
        url: '/networks_required'
    });
};

whowentout.showProfileEditDialog = function () {
    whowentout.showDialog({
        title: 'Your Profile Pic',
        cls: 'profile_edit_dialog',
        url: '/profile/picture/edit'
    });
};

whowentout.showDisabledOnPhoneDialog = function () {
    whowentout.showDialog({
        title: 'Use Your Computer',
        message: '<img src="/images/laptop.png" />'
                + '<p>This feature is available on your computer.</p>',
        buttons: 'ok',
        cls: 'disabled_on_phone'
    });
};

whowentout.showFlashMessage = function(text) {
    $('#flash_message').remove();
    $('body').append('<div id="flash_message"></div>');
    $('#flash_message').html(text);
    return $('#flash_message');
};

whowentout.getProfilePictureUrl = {
    localhost: function(user_id) {
        return '/pics/' + user_id + '.normal.jpg';
    },
    whowasout: function(user_id) {
        return 'http://s3.amazonaws.com/whowasout_pics/' + user_id + '.normal.jpg';
    },
    whowentout: function(user_id) {
        return 'http://s3.amazonaws.com/whowentout_pics/' + user_id + '.normal.jpg'
    }
};

whowentout.getProfilePictureUrls = function(user_ids) {
    var fn = whowentout.getProfilePictureUrl[window.settings.environment];
    var urls = {};
    _(user_ids).each(function(id) {
        urls[id] = fn(id);
    });
    return urls;
};

$('a.coming_soon').entwine({
    onclick: function(e) {
        e.preventDefault();
        whowentout.initDialog();
        dialog.title('Coming Soon');
        dialog.message('<p>Coming soon, along with other features!</p>'
        + '<br/>'
        + '<p>To suggest features, click the feedback button.</p>'
        + '<br/>'
        );
        dialog.setButtons('ok');
        dialog.showDialog('coming_soon');
    }
});

$('.login_button').entwine({
    onclick: function(e) {
        if (this.hasClass('clicked')) {
            e.preventDefault();
        }
        else {
            this.addClass('clicked')
            whowentout.showFlashMessage('Logging In...');
        }
    }
});

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

$('.dialog.deal_dialog, .dialog.checkin_explanation_dialog').entwine({
    onmaskclick: function () {
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
            'checkin/explanation/:id': 'showCheckinExplanationDialog',
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
            scrollable.markSelected(link, false);
        },
        showDealDialog:function (event_id) {
            whowentout.showDealDialog(event_id);
        },
        showInviteDialog:function (event_id) {
            whowentout.showInviteDialog(event_id);
        },
        showEditProfilePictureDialog:function () {
            whowentout.showProfileEditDialog();
        },
        showEntourageRequestDialog:function () {
            whowentout.showEntourageRequestDialog();
        },
        showCheckinExplanationDialog: function(event_id) {
            whowentout.showCheckinExplanationDialog(event_id);
        },
        defaultRoute:function () {
        }
    });

    whowentout.router = new whowentout.router();

    Backbone.history.start({pushState:true});
});

$('a.view_leaderboard').entwine({
    onclick: function(e) {
        e.preventDefault();
        var href = this.attr('href');
        whowentout.showDialog({
            title: 'Leaderboard',
            url: href,
            buttons: 'close'
        });
    }
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
        }, 7000);
    },
    onunmatch:function () {
    }
});

$('.profile_pic_crop_form').entwine({
    onmatch:function () {
        this.initCropper();
    },
    onunmatch:function () {},
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
                self.setCropBox(coords.x, coords.year, coords.w, coords.h);
            }

            var box = self.getCropBox();
            var options = {
                aspectRatio:0.75,
                boxWith:400,
                boxHeight:400,
                setSelect:[box.x, box.year, box.x + box.width, box.year + box.height],
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
    applyFilters:_.debounce(function (keywords) {

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
        this.closest('.event_invite').applyFilters(keywords);
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

$('.event_picker').entwine({
    onmatch: function() {
        if (this.isEventSelected()) {
            this.find('.pre_event_selection').hide();
        }
    },
    onunmatch: function() {
    },
    isEventSelected: function() {
        return this.find('.event_selection').length > 0;
    }
});

$('.event_selection .switch').entwine({
    onclick: function(e) {
        e.preventDefault();
        this.closest('.event_selection').hide();
        this.closest('.event_picker').find('.pre_event_selection')
                                     .removeClass('hidden').show();
    }
});

$('.event_list :radio').entwine({
    onclick: function (e) {
        if (this.val() != 'new') {
            this.closest('.event_option').find('.checkin_badge').text('loading...');
            this.closest('form').submit();
        }
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

        var pData = this.getUpdatedData(date);
        $.when(pData).then(function (data) {
            self.replaceHtml(data.event_day);
            var date = new Date(data.date * 1000);

            $('.event_day').data(data);
            $('.event_day').data('date', date);
            $('.event_day').trigger({type: 'datechange', date: date, event: data.event});
        });
    },
    showLoadingMessage:function () {
        this.find('.event_picker').addClass('loading');
        return this;
    },
    replaceHtml:function (html) {
        this.replaceWith(html);
    },
    getCurrentDate:function () {
        return this.data('date');
    },
    getUpdatedData:function (date) {
        return $.ajax({
            url: '/day/' + date,
            type:'post',
            dataType: 'json'
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
        return this.val() == '';
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
    },
    val: function(value, original) {
        var labelText = this._super();
        var actualValue = labelText;

        if (labelText == this.attr('title'))
            actualValue = '';

        if (arguments.length == 0)
            return actualValue;
        else if (arguments.length == 1)
            return this._super(value);
        else if (arguments.length == 3)
            return labelText;
        else
            throw new Exception('Unsupported');
    }
});

whowentout.refreshDateSelector = _.debounce(function () {
    $('#events_date_selector').updateWidth();
    $('#events_date_selector .scrollable').refreshScrollPosition();
}, 250);
$(window).resize(whowentout.refreshDateSelector);
$(whowentout.refreshDateSelector);

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
$(window).bind('load', function() {
    if (!window.pageYOffset) {
        hideAddressBar();
    }
});
$(window).bind('orientationchange', hideAddressBar);

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
            self.dataTable({
                aaSorting: [[1, 'desc']]
            });
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
        this._super();
        this.selectTab(this.getSelectedTab().tabKey());
    },
    onunmatch:function () {
    },
    getSelectedTab: function() {
        return this.find('.tabs a.selected');
    },
    getTab: function(key) {
        return this.find('.tabs a[href=#' + key + ']');
    },
    selectTab:function (key) {
        this.find('.pane').hide();
        this.find('.pane').filter('.' + key).show();

        this.getSelectedTab().unmarkSelected();
        this.getTab(key).markSelected();
    }
});

$('.tab_panel .tabs a').entwine({
    onclick:function (e) {
        e.preventDefault();
        this.closest('.tab_panel').selectTab(this.tabKey());
    },
    tabKey:function () {
        return this.attr('href').replace(/#/g, '');
    },
    markSelected: function() {
        this.addClass('selected');
        this.parent().addClass('selected_container');
    },
    unmarkSelected: function() {
        this.removeClass('selected');
        this.parent().removeClass('selected_container');
    }
});

$('.expandable').entwine({
    onmatch:function () {
        if (this.items().length < 2)
            return;

        this.restOfItems().hide();
        this.firstItem().append('<a href="#view_more" class="view_more">view more</a>');
        this.lastItem().append('<a href="#view_less" class="view_less">view less</a>');
    },
    onunmatch:function () {
    },
    items: function() {
        return this.find('> li');
    },
    firstItem:function () {
        return this.find('> li:first');
    },
    lastItem: function() {
        return this.find('> li:last');
    },
    restOfItems:function () {
        return this.firstItem().nextAll();
    },
    viewMoreLink:function () {
        return this.find('.view_more');
    },
    viewLessLink:function () {
        return this.find('.view_less');
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

$('table.expandable').entwine({
    firstItem: function() {
        return this.find('tr:first');
    },
    lastItem: function() {
        return this.find('tr:last');
    },
    items: function() {
        return this.find('tr');
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

$('.event_links a').entwine({
    onclick: function(e) {
        e.preventDefault();
        var event_id = this.attr('href');
        var gallery = this.closest('.event_day_summary').find('.gallery');
        gallery.scrollToEvent(event_id, function() {
            gallery.selectEvent(event_id);
        });
    }
});

$('.invite_leaderboard .score').entwine({
    onclick: function(e) {
        e.preventDefault();
        this.closest('li').find('.people').slideToggle();
    }
});

$('.desktop .event_gallery_toolbar').entwine({
    oninview: function(e) {
        if (e.isAbove)
            this.stick();
    },
    onstick: function(e) {
        var self = this;
        e.placeholder.bind('inview', function(e) {
            if (e.inView)
                self.unstick();
        });
    }
});

$('#events_date_selector').entwine({
    oninview: function(e) {
        if ($('.event_gallery_toolbar').isStuck())
            $('.event_gallery_toolbar').unstick();
    }
});

(function() {

    var matchers = {};

    matchers.keywords = function(el, keywords) {
        var text = $(el).find('.profile_name').text().toLowerCase();
        var parts = keywords.toLowerCase().split(/\W+/);

        for (var i = 0; i < parts.length; i++)
            if (text.indexOf(parts[i]) == -1)
                return false;

        // match every keyword
        return true;
    };

    matchers.connection = function(el, c) {
        var profile = $(el).find('.profile_small');

        if (c == 'members')
            return true;
        else if (c == 'friend')
            return profile.hasClass('friend') || profile.hasClass('entourage');
        else if (c == 'entourage')
            return profile.hasClass('entourage');
        else
            return false;
    };

    var isMatch = function(el, filters) {
        var match = true;
        $.each(filters, function(filterName, filterValue) {
            var fn = matchers[filterName];
            match = fn(el, filterValue);
            return match;
        });
        return match;
    };

    var executeFilter = function(gallery, filters) {
        gallery = $(gallery);
        gallery.find('> li').each(function () {
            var match = isMatch(this, filters);
            if (match)
                $(this).removeClass('hidden');
            else
                $(this).addClass('hidden');
        });

        gallery.recalculateGalleryBoxes();
        $(window).trigger('scroll');
    };
    executeFilter = _.debounce(executeFilter, 500);

    $('.gallery').entwine({
        onmatch: function() {
            this.bindWindowScrollListener();
            this.bindWindowResizeListener();
            $(window).trigger('scroll');
        },
        onunmatch: function() {
        },
        clearFilters: function() {
            this.applyFilters({});
        },
        applyFilters: function (filters) {
            executeFilter(this, filters);
        },
        onitemsenteredview: function(e) {
            e.items.find('.profile_small').addClass('seen');
            this.loadProfilePictures(e.items);
        },
        loadProfilePictures: function(items) {
            var userIds = items.find('.img_load')
                               .removeClass('img_load')
                               .collect(function() {
                return this.data('user_id');
            });

            var pUrls = whowentout.getProfilePictureUrls(userIds);
            $.when(pUrls).then(function(urls) {
                $.each(urls, function(user_id, url) {
                    var profilePicture = $('.profile_picture_' + user_id);
                    var version = profilePicture.data('version');console.log(version);
                    profilePicture.attr('src', url + '?version=' + version);
                });
            });
        },
        bindWindowScrollListener: function() {
            var self = this;
            var onscroll = _.debounce(function() {
                if (!self.is(':visible'))
                    return;
                self.trigger({
                    type: 'itemsenteredview',
                    items: self.getItemsInView()
                });
            }, 250);
            $(window).bind('scroll', onscroll);
        },
        unbindWindowScrollListener: function() {
            // @todo
        },
        bindWindowResizeListener: function() {
            var self = this;
            var onresize = _.debounce(function() {
                self.recalculateGalleryBoxes();
                $(window).trigger('scroll');
            }, 500);
            $(window).bind('resize', onresize);
        },
        getItemsInView: function() {
            var indices = this.getVisibleItemIndices();
            var start = _(indices).first();
            var end = _(indices).last();

            if (indices.length == 0)
                return $();

            var extra = (end - start + 1);

            return this.find('> li').slice(start, end + extra * 2);
        },
        getVisibleItemIndices: function() {
            var boxes = this.galleryBoxes();
            var viewport = $(window).getBox();
            var indices = [];

            for (var i = 0; i < boxes.length; i++)
                if (boxes[i].overlaps(viewport))
                    indices.push(i);

            return indices;
        },
        galleryBoxes: function(val) {
            if (arguments.length == 0) {
                if (!this.data('galleryBoxes')) {
                    this.galleryBoxes(this.fetchGalleryBoxes());
                }
                return this.data('galleryBoxes');
            }
            else {
                this.data('galleryBoxes', val);

//                $('#view').clearBoxes();
//                if (val)
//                    $('#view').addBoxes(val);

                return this;
            }
        },
        recalculateGalleryBoxes: function() {
            this.galleryBoxes(this);
        },
        fetchGalleryBoxes: function() {
            return this.find('> li').filter(':visible').collect(function() {
                            return this.getBox();
                        });
        }
    });

})();

$('.gallery_filter :input').entwine({
    onchange: function(e) {
        var self = this;
        _.defer(function() {
            self.updateGalleryFilter();
        });
    },
    updateGalleryFilter: function() {
        var filters = this.closest('form').val();
        this.closest('.event_day_summary').find('.gallery').applyFilters(filters);
    },
    onkeyup: function(e) {
        this.trigger('change');
    }
});

$('.invite_to_form').entwine({
    onsubmit: function(e) {
        e.preventDefault();
        var data = this.serialize();
        var self = this;
        $.ajax({
            type: this.attr('method'),
            dataType: 'json',
            url: this.attr('action'),
            data: this.serialize(),
            success: function(response) {
                self.replaceWith(response.invite_to_form);
            }
        });
    }
});

$('#right .switch').live('click', function(e) {
    e.preventDefault();
    $('#content').scrollTo(function() {
        $('.event_picker .switch').click();
    });
});

$('#right').entwine({
    onmatch: function() {
        var left = $('#content').getBox().right + 10;
        this.css({
            display: 'block',
            left: left + 'px'
        });
    },
    onunmatch: function() {}
});

$('.event_day').live('datechange', function(e) {
    var data = $(this).data();
    data.user = whowentout.currentUser.toJSON();
    $('#right').template('right', data);
});
