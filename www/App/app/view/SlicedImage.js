Ext.define('App.view.SlicedImage', {
    extend: 'Ext.Img',
    xtype: 'slicedimage',

    config: {},

    constructor: function(config) {
        config = config || {};
        config.src = '/app/resources/images/' + config.src;
        config.style = {
            position: 'relative'
        };
        this.callParent([config]);

        this.createAllRegions(config.regions);
    },

    createAllRegions: function(regions) {
        var me = this;
        if (!regions)
            return;

        this.regions = [];
        console.log(regions);
        Ext.Object.each(regions, function(key, value) {
            me.createRegion(key, value);
        });
    },

    destroyRegion: function(name) {
        if (this.regions[name])
            this.regions[name].destroy();
    },

    createRegion: function(name, box) {
        var me = this;
        this.destroyRegion(name);

        var region = this.element.createChild({
            tag: 'div',
            style: {
                'position': 'absolute',
                'cursor': 'pointer',
                'border': '1px solid greenyellow'
            }
        });
        region.setBox(box);

        region.on('tap', function() {
            Ext.getCmp('main').setActiveItem(box.goto);
        });

        this.regions[name] = region;
    }

});
