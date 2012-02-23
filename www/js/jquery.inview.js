//= require jquery.js
//= require jquery.position.js

/**
 * author Christopher Blum
 *    - based on the idea of Remy Sharp, http://remysharp.com/2009/01/26/element-in-view-event-plugin/
 *    - forked from http://github.com/zuk/jquery.inview/
 */
(function ($) {
    var inviewObjects = {}, expando = $.expando;

    function updateInViewForEl(el, viewportBox, triggerEvent) {
        var wasInView = el.data('inView') || false;
        var isInView = el.getBox().overlaps(viewportBox);

        el.data('inView', isInView);

        if (triggerEvent && isInView != wasInView) {
            el.trigger({
                type: 'inview',
                inView: el.data('inView'),
                isAbove: el.getBox().isAbove(viewportBox),
                isBelow: el.getBox().isBelow(viewportBox)
            });
        }
    }

    function updateInViewPropeties() {
        var $elements = $();

        $.each(inviewObjects, function (i, inviewObject) {
            var selector = inviewObject.data.selector,
            $element = inviewObject.$element;
            $elements = $elements.add(selector ? $element.find(selector) : $element);
        });

        var viewportBox = $('body').getBox();
        $elements.each(function() {
            updateInViewForEl($(this), viewportBox, true);
        });
    }

    var intervalId;

    $.event.special.inview = {
        setup: function( data, namespaces, eventHandle ) {
            intervalId = setInterval(updateInViewPropeties, 250);
        },

        teardown: function( namespaces ) {
            clearInterval(intervalId);
        },

        add: function (data) {
            inviewObjects[data.guid + "-" + this[expando]] = { data:data, $element:$(this) };
        },

        remove: function (data) {
            try {
                delete inviewObjects[data.guid + "-" + this[expando]];
            } catch (e) {
            }
        }
    };

})(jQuery);
