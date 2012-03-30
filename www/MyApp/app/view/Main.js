Ext.define("MyApp.view.Main", {
    extend: 'Ext.tab.Panel',
    requires: ['Ext.TitleBar'],
    
    config: {
        tabBarPosition: 'bottom',
        
        items: [
            {
                title: 'Neighborhood',
                iconCls: 'team',
                scrollable: true,

                items: [
                    {
                        docked: 'top',
                        xtype: 'titlebar',
                        title: 'My Neighborhood'
                    },
                    {
                        xtype: 'panel',
                        html: '<h1>my neighborhood</h1>'
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
                iconCls: 'download',
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
                iconCls: 'time',
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