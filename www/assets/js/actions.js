Actions = {
    AddTempClass: function(target, cls, duration) {
        $(target).addClass(cls);
        function removeClass() {
            $(target).removeClass(cls);
        }

        setTimeout(removeClass, duration);
    },
    HighlightSmilesLeft: function(duration) {
        duration = duration || 3000;
        this.AddTempClass('.smiles_left', 'attention', duration);
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
    ShowSmileHelpTip: function() {
        jQuery(function($) {
            $('#logo').bind('imageload', function() {
                $('.whats_a_smile a').showTip({position: 'right', content: 'Click Here!', cls: 'see_smile_help_tip'});

                $(window).bind('resize', _.debounce(function() {
                    $('.see_smile_help_tip').stop(true, true).refreshPosition();
                }, 250));

                function bounce() {
                    var position = $('.see_smile_help_tip').anchorPosition();

                    var finalLeft = (position.left + 10) + 'px';
                    var initialLeft = position.left + 'px';

                    if ($('.see_smile_help_tip').queue('fx').length < 2) {
                        $('.see_smile_help_tip')
                        .animate({left: finalLeft}, 250)
                        .animate({left: initialLeft}, 250);
                    }
                }

                var id = setInterval(bounce, 3000);
                bounce();
            });
        });
    },
    ShowSiteHelp: function() {
        $.when(app.load()).then(function() {
            var path = '/dashboard/site_help';
            WWO.dialog.title('Help').setButtons('continue').showDialog('site_help');
            WWO.dialog.loadContent('/dashboard/site_help');
        });
    }
};
