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
        $(element).flashSpotlight();
    }
};
