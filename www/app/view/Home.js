Ext.define('App.view.Home', {
    extend: 'Ext.tab.Panel',
    requires: ['Ext.TitleBar'],
    xtype: 'homepanel',

    config: {
        tabBarPosition: 'bottom',
        items: [
            {
                title: 'Neighborhood',
                iconCls: 'home',
                layout: 'vbox',
                items: [
                    {
                        docked: 'top',
                        xtype: 'titlebar',
                        title: 'My Neighborhood'
                    },
                    {
                        xtype: 'panel',
                        padding: '15 15 15 15',
                        html: '<h1>Welcome to the West Village crowd!</h1>'
                    },
                    {
                        xclass: 'App.view.UserList',
                        flex: 1
                    }
                ]
            },
            {
                title: 'Meet',
                iconCls: 'search',
                scrollable: true,

                items: [
                    {
                        docked: 'top',
                        xtype: 'titlebar',
                        title: 'Meet'
                    },
                    {
                        xtype: 'panel',
                        html: '<h1>meet</h1>'
                    }
                ]
            },
            {
                title: 'Checkin',
                iconCls: 'marker',
                scrollable: true,

                items: [
                    {
                        docked: 'top',
                        xtype: 'titlebar',
                        title: 'Checkin'
                    },
                    {
                        xtype: 'panel',
                        html: '<h1>Checkin</h1>'
                    }
                ]
            },
            {
                title: 'My Circle',
                iconCls: 'circle',
                scrollable: true,

                items: [
                    {
                        docked: 'top',
                        xtype: 'titlebar',
                        title: 'My Circle'
                    },
                    {
                        xtype: 'panel',
                        html: '<h1>My Circle</h1>'
                    }
                ]
            },
            {
                title: 'Profile',
                iconCls: 'user',
                scrollable: true,

                items: [
                    {
                        docked: 'top',
                        xtype: 'titlebar',
                        title: 'My Profile'
                    },
                    {
                        xtype: 'panel',
                        html: '<h1>My Profile</h1>'
                    }
                ]
            }
        ]
    }
});
