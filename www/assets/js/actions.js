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
        $('.see_party_gallery:first').showTip({position: 'right', content: 'Click Here!', cls: 'see_party_gallery_tip'});

        function bounce() {
            $('.see_party_gallery_tip')
            .animate({left: '+=10px'}, 250)
            .animate({left: '-=10px'}, 250);
        }

        var id = setInterval(bounce, 3000);
        bounce();
    },
    ShowSmileHelpTip: function() {
        $('.whats_a_smile a').showTip({position: 'right', content: 'Click Here!', cls: 'see_smile_help_tip'});

        function bounce() {
            $('.see_smile_help_tip')
            .animate({left: '+=10px'}, 250)
            .animate({left: '-=10px'}, 250);
        }

        var id = setInterval(bounce, 3000);
        bounce();
    }
};
