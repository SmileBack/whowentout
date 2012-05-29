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

        this.add({
            xtype: 'slicedimage',
            src: config.id + '.jpg',
            regions: config.regions
        });
    }

});
