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

    _transformBox: function(box, factor) {
        box.left *= factor;
        box.top *= factor;
        box.width *= factor;
        box.height *= factor;
    },

    createRegion: function(name, box) {
        var me = this;
        this.destroyRegion(name);

        this._transformBox(box, 0.5);
        var regionEl = this.element.createChild({
            tag: 'div',
            style: {
                'position': 'absolute',
                'cursor': 'pointer',
                'border': '1px solid greenyellow'
            }
        });
        regionEl.setBox(box);

        regionEl.on('tap', function() {
            Ext.getCmp('main').setActiveItem(box.goto);
        });

        this.regions[name] = {
            el: regionEl,
            box: box
        };
    }

});
