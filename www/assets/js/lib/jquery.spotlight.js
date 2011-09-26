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

    $.fn.spotlight = function() {
        // Default settings
        var settings = {
            opacity: .5,
            speed: 400,
            color: '#333',
            animate: true
        };

        // Do a compatibility check
        if (!jQuery.support.opacity) return false;

        if ($('#spotlight').size() == 0) {
            // Add the overlay div


            // Get our elements
            var element = $(this);
            var spotlight = $('#spotlight');

            // Set the CSS styles
            spotlight.css({
                'position':'fixed',
                'background':settings.color,
                'opacity':'0',
                'top':'0px',
                'left':'0px',
                'height':'100%',
                'width':'100%',
                'z-index':'9998'
            });

            // Set element CSS
            var currentPos = element.css('position');
            if (currentPos == 'static') {
                element.css({'position':'relative', 'z-index':'99990'});
            } else {
                element.css('z-index', '99990');
            }

            // Fade in the spotlight
            if (settings.animate) {
                spotlight.animate({opacity: settings.opacity}, settings.speed, settings.easing, function() {
                    // Trigger the onShow callback
                    settings.onShow.call(this);
                });
            } else {
                spotlight.css('opacity', settings.opacity);
                // Trigger the onShow callback
                settings.onShow.call(this);
            }

            // Set up click to close
            spotlight.live(settings.exitEvent, function() {
                if (settings.animate) {
                    spotlight.animate({opacity: 0}, settings.speed, settings.easing, function() {
                        if (currentPos == 'static') element.css('position', 'static');
                        element.css('z-index', '1');
                        $(this).remove();
                        // Trigger the onHide callback
                        settings.onHide.call(this);
                    });
                } else {
                    spotlight.css('opacity', '0');
                    if (currentPos == 'static') element.css('position', 'static');
                    element.css('z-index', '1');
                    $(this).remove();
                    // Trigger the onHide callback
                    settings.onHide.call(this);
                }
            });
        }

        // Returns the jQuery object to allow for chainability.
        return this;
    };

})(jQuery);
