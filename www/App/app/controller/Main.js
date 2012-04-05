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

    showDetail: function() {
        this.getMain().push({
            xtype: 'userdetail'
        });
    }
});
