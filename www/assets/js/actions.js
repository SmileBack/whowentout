//= require lib/underscore.js
//= require lib/jsaction.js
//= require lib/jquery.spotlight.js
//= require lib/jquery.tip.js

Actions = {
    AddTempClass: function(target, cls, duration) {
        $(target).addClass(cls);
        function removeClass() {
            $(target).removeClass(cls);
        }

        setTimeout(removeClass, duration);
    },
    ReplaceHtml: function(selector, html) {
        var html = $(html);
        $(selector).replaceWith(html);
    },
    Alert: function(message) {
        alert(message);
    },
    ShowSpotlight: function(element, duration) {
        duration = duration || 2000;
        $(element).flashSpotlight(duration);
    },
    ShowPartyGalleryTip: function() {
        jQuery(function($) {
            $('.see_party_gallery:first').showTip({position: 'right', content: 'Click Here!', cls: 'see_party_gallery_tip'});

            $(window).bind('resize', _.debounce(function() {
                $('.see_party_gallery_tip').stop(true, true).refreshPosition();
            }, 250));

            function bounce() {
                var position = $('.see_party_gallery_tip').anchorPosition();

                var finalLeft = (position.left + 10) + 'px';
                var initialLeft = position.left + 'px';

                if ($('.see_party_gallery_tip').queue('fx').length < 2) {
                    $('.see_party_gallery_tip')
                    .animate({left: finalLeft}, 250)
                    .animate({left: initialLeft}, 250);
                }
            }

            var id = setInterval(bounce, 3000);
            bounce();
        });
    },
    ShowSmileHelpDialog: function() {
        $.when(app.load()).then(function() {
            app.showSmileHelp();
        });
    },
    ShowSiteHelp: function() {
        WhoWentOut.Dialog.Show({
            title: 'Welcome to WhoWentOut',
            buttons: 'continue',
            cls: 'site_help',
            url: '/help/site'
        });
    },
    SetText: function(selector, text) {
        $(selector).text(text);
    }
};
