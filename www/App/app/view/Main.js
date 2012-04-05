Ext.define('App.view.Main', {
    extend: 'Ext.navigation.View',
    xtype: 'mainpanel',

    requires: [
        'App.view.UserList',
        'App.view.UserDetail'
    ],

    config: {
        items: [{
            xtype: 'homepanel'
        }]
    }
});