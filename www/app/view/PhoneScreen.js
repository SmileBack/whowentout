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
            if (this.fileExists('resources/images/' + fileName + extensions[i]))
                return 'resources/images/' + fileName + extensions[i];
    },

    fileExists: function(url) {
        this.exists = this.exists || {};

        if (this.exists[url] === undefined) {
            var http = new XMLHttpRequest();
            http.open('HEAD', url, false);
            http.send();
            this.exists[url] = (http.status != 404);
        }

        return this.exists[url];
    }

});
