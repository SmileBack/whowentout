Ext.define('App.view.SlicedImage', {
    extend: 'Ext.Img',
    xtype: 'slicedimage',

    config: {},

    constructor: function(config) {
        config = config || {};
        config.style = {
            position: 'relative'
        };
        config.mode = 'image';
        config.width = 320;

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
        if (this.regions[name]) {
            this.regions[name].el.destroy();
            delete this.regions[name];
        }
    },

    _scaleBox: function(box, factor) {
        return {
            left: box.left * factor,
            top: box.top * factor,
            width: box.width * factor,
            height: box.height * factor
        };
    },

    createRegion: function(name, box) {
        var me = this;
        this.destroyRegion(name);

        box.name = name;

        var scaledBox = this._scaleBox(box, 0.5);
        var regionEl = this.element.createChild({
            tag: 'div',
            style: {
                'position': 'absolute',
                'cursor': 'pointer',
                'border': '1px solid greenyellow'
            }
        });
        regionEl.setBox(scaledBox);

        regionEl.on('tap', function() {
            var item = box.goto? Ext.getCmp(box.goto) : Ext.getCmp(box.name);
            Ext.getCmp('main').setActiveItem(item);
        });

        this.regions[name] = {
            el: regionEl,
            box: box
        };
    }

});
