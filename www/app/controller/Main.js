Ext.define('App.controller.Main', {
    extend: 'Ext.app.Controller',

    config: {
        refs: {
            main: 'mainpanel'
        },
        control: {
            userlist: {
                disclose: 'showDetail'
            }
        }
    },

    showDetail: function(list, record) {
        this.getMain().push({
            xtype: 'userdetail',
            data: record.data,
            title: record.fullName()
        });
    }
});
