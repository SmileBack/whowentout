Ext.define('App.view.PhoneScreen', {
    extend: 'Ext.Panel',
    xtype: 'phonescreen',

    config: {
        scrollable: {
            direction: 'vertical',
            directionLock: true
        }
    },

    constructor: function(config) {
        config = config || {};
        this.callParent([config]);

        var top = this.topFilePath(config.id),
            middle = this.middleFilePath(config.id),
            bottom = this.bottomFilePath(config.id);

        this.add({
            xtype: 'slicedimage',
            src: middle,
            regions: config.regions
        });

        if (top) {
            this.add({
                xtype: 'slicedimage',
                docked: 'top',
                src: top,
                regions: config.topRegions
            });
        }

        if (bottom) {
            this.add({
                xtype: 'slicedimage',
                docked: 'bottom',
                src: bottom,
                regions: config.bottomRegions
            });
        }
    },

    topFilePath: function(id) {
        return this.filePath(id + '-top');
    },

    middleFilePath: function(id) {
        return this.filePath(id);
    },

    bottomFilePath: function(id) {
        return this.filePath(id + '-bottom');
    },

    filePath: function(fileName) {
        var extensions = ['.png', '.jpg'];
        for (var i = 0; i < extensions.length; i++)
            if (this.imageExists(fileName + extensions[i]))
                return 'resources/images/' + fileName + extensions[i];
    },

    imageExists: function(fileName) {
        return window.resources.images.indexOf(fileName) != -1;
    }

});
