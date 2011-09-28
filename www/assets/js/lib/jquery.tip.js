(function($) {

    function get_defaults() {
        var defaults = {
            position: "bottom",
            delay: 400,
            fadeIn: 0,
            fadeOut: 0,
            css: {},
            cls: false,
            content: false // HTML or String to fill TipTIp with
        };
        return defaults;
    }

    function get_target(el) {
        el = $(el);
        // if we are providing the tip element itself
        if (el.is('.tiptip_holder'))
            return $(el.data('tip_el'));
        else
            return el;
    }

    function get_tip(el) {
        el = $(el);
        if (el.is('.tiptip_holder'))
            return el;
        else if (el.data('tip_el'))
            return $(el.data('tip_el'));
        else
            return null;
    }

    function get_config(el) {
        return $(el).data('tip_config');
    }

    function position_tip(el) {
        target = get_target(el);
        tip = get_tip(el);
        cfg = get_config(tip);

        var tip = get_tip(el),
        cfg = get_config(el),
        content = tip.find('.tiptip_content'),
        arrow = tip.find('.tiptip_arrow');

        tip.css('visibility', 'hidden').css('display', '');

        var tipBox = content.getBox(),
        anchor = null;

        if (cfg.position == 'top') {
            anchor = ['bc', 'tc'];
            arrow.margin({
                left: tipBox.width / 2 - arrow.outerWidth() / 2,
                top: tipBox.height
            });
        }
        else if (cfg.position == 'right') {
            anchor = ['cl', 'cr'];
            arrow.margin({
                left: -1 * arrow.outerWidth(),
                top: tipBox.height / 2 - arrow.outerHeight() / 2
            });
        }
        else if (cfg.position == 'bottom') {
            anchor = ['tc', 'bc'];
            arrow.margin({
                left: tipBox.width / 2 - arrow.outerWidth() / 2,
                top: -1 * arrow.outerHeight()
            });
        }
        else if (cfg.position == 'left') {
            anchor = ['cr', 'cl'];
            arrow.margin({
                left: tipBox.width,
                top: tipBox.height / 2 - arrow.outerHeight() / 2
            });
        }

        tip.css('visibility', '').css('display', 'none');

        tip.applyPosition(target, {
            anchor: anchor
        });
    }

    function has_tip(el) {
        el = get_target(el);
        return el && el.data('tip_el') != null;
    }

    function build_tip(target, cfg) {
        target = $(target);

        if (!cfg && has_tip(target)) //tip has already been built, no config specified, so tip is already there.
            return get_tip(target);
        else if (has_tip(target)) //config is given so we want to replace the existing tip
            remove_tip(target);

        cfg = $.extend(get_defaults(), cfg);
        // Setup tip tip elements and render them to the DOM
        var tip = $('<div class="tiptip_holder"></div>'),
        content = $('<div class="tiptip_content"></div>'),
        arrow = $('<div class="tiptip_arrow"><div class="tiptip_arrow_inner"></div></div>');
        content.css(cfg.css);
        tip.append(arrow).append(content).css('visibility', 'hidden');
        $('body').append(tip);

        target.data('tip_el', $(tip));
        target.data('tip_config', cfg);
        tip.data('tip_target_el', $(target));
        tip.data('tip_config', cfg);

        if (cfg.cls) {
            tip.addClass(cfg.cls);
        }

        content.html(cfg.content);
        tip.addClass('tip_' + cfg.position);

        position_tip(target);
        target.addClass('has-tip');

        tip.hide().css('visibility', '');

        return tip;
    }

    function show_tip(el, fn) {
        var tip = get_tip(el),
        cfg = get_config(el);

        fn = fn || function() {
        };

        tip.stop().css('opacity', '').delay(cfg.delay).fadeIn(cfg.fadeIn, fn);
    }

    function hide_tip(el, fn) {
        var tip = get_tip(el),
        cfg = get_config(el);

        fn = fn || function() {
        };

        tip.stop().css('opacity', '').fadeOut(cfg.fadeOut, fn);
    }

    function remove_tip(el) {
        var target = get_target(el),
        tip = get_tip(el);

        if (!tip)
            return;

        if (tip.is(':visible')) {
            hide_tip(el, function() {
                target.removeData('tip_el').removeData('tip_config');
            });
        }
        else {
            target.removeData('tip_el').removeData('tip_config');
        }

        tip.remove();
    }


    $.fn.showTip = function(options) {
        $(this).each(function() {
            build_tip(this, options);
            position_tip(this);
            show_tip(this);
        });
        return this;
    }

    $.fn.removeTip = function() {
        $(this).each(function() {
            remove_tip(this);
        });
    }

    $.fn.hideTip = function() {
        $(this).each(function() {
            hide_tip(this);
        });

        return this;
    }

    $.fn.tip = function(cfg) {

        $(this).each(function() {
            build_tip(this, cfg);

            $(this).bind({
                mouseenter: function() {
                    $(this).showTip();
                },
                mouseleave: function() {
                    $(this).hideTip();
                }
            });

        });

        return this;
    }

})(jQuery);
