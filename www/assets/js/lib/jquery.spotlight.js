/**
 * jQuery Spotlight
 *
 * Project Page: http://dev7studios.com/portfolio/jquery-spotlight/
 * Copyright (c) 2009 Gilbert Pellegrom, http://www.gilbertpellegrom.co.uk
 * Licensed under the GPL license (http://www.gnu.org/licenses/gpl-3.0.html)
 * Version 1.0 (12/06/2009)
 */

(function($) {

    function create_spotlight_element() {
        if ($('#spotlight').size() > 0)
            return;

        $('body').append('<div id="spotlight"></div>');
        var spotlight = $('#spotlight');
        spotlight.css({
            'position': 'fixed',
            'background': '#333',
            'opacity': 0,
            'top': '0px',
            'left': '0px',
            'width': '100%',
            'height': '100%',
            'width': '100%',
            'z-index': 9998
        });
    }

    function save_styles(els) {
        $(els).each(function() {
            $(this).data('prevStyle', $(this).attr('style') || '<<none>>');
        });
    }

    function restore_styles(els) {
        $(els).each(function() {
            var prevStyle = $(this).data('prevStyle');
            if (prevStyle == '<<none>>') {
                //$(this).removeAttr('style');
                $(this).attr('style', '');
            }
            else if (typeof prevStyle == 'string') {
                $(this).attr('style', prevStyle);
            }
            $(this).removeData('prevStyle');
        });
    }

    var settings = {
        opacity: .5,
        speed: 400,
        color: '#333',
        animate: true,
        easing: 'swing'
    };

    $.fn.flashSpotlight = function(delay, onComplete) {
        delay = delay || 400;
        onComplete = onComplete || function() {
        };

        var self = $(this);
        $(this).showSpotlight(function() {
            setTimeout(function() {
                self.hideSpotlight(function() {
                   onComplete.call(self);
                });
            }, delay);
        });
    }

    $.fn.showSpotlight = function(onComplete) {
        onComplete = onComplete || function() {
        };

        // Compatibility check
        if (!jQuery.support.opacity) return false;

        create_spotlight_element();

        // Get our elements
        var elements = $(this);
        var spotlight = $('#spotlight');

        save_styles(elements);

        // Set element CSS
        var currentPos = elements.css('position');
        elements.each(function() {
            if ($(this).css('position') == 'static') {
                $(this).css({'position':'relative', 'z-index':'99990'});
            }
            else {
                $(this).css('z-index', '99990');
            }
        });
        
        spotlight.data('element', elements);

        spotlight.animate({opacity: settings.opacity}, settings.speed, settings.easing, function() {
            onComplete.call(elements);
        });

        // Returns the jQuery object to allow for chainability.
        return this;
    };

    $.fn.hideSpotlight = function(onComplete) {
        onComplete = onComplete || function() {
        };

        var self = $(this);
        var spotlight = $('#spotlight');
        var element = $('#spotlight').data('element');

        spotlight.animate({opacity: 0}, settings.speed, settings.easing, function() {
            restore_styles(element);
            spotlight.removeData('element');
            spotlight.remove();
            onComplete.call(this);
        });
    }

    $('a.show_spotlight').live('click', function(e) {
        e.preventDefault();
        var target = $(this).attr('data-target');
        var delay = parseInt( $(this).attr('data-delay') );
        $(target).flashSpotlight(delay);
    });

})(jQuery);
