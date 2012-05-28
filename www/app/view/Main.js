Ext.define('App.view.Main', {
    extend: 'Ext.Container',
    xtype: 'mainpanel',

    requires: [],

    config: {
        header: false,
        navigationBar: {hidden: true},
        fullscreen: true,
        layout: 'card',
        items: [
            {
                html: '<h1>item a</h1>'
            },
            {
                html: '<h1>item a</h1>'
            },
            {
                html: '<h1>item a</h1>'
            }
        ]
    }
});
