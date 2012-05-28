Ext.define('App.view.UserDetail', {
    extend: 'Ext.Panel',
    xtype: 'userdetail',

    config: {
        title: 'User Details',
        scrollable: 'vertical',
        tpl: '{firstName} profile'
    }
});
