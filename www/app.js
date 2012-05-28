//<debug>
Ext.Loader.setPath({
    'Ext': 'sdk/src'
});
//</debug>

Ext.application({
    name: 'App',

    requires: [
        'Ext.MessageBox'
    ],

    models: ['User'],
    views: ['Main', 'Home', 'UserList', 'UserDetail', 'SlicedImage'],
    controllers: ['Main'],
    stores: ['Users'],

    icon: {
        57: 'resources/icons/Icon.png',
        72: 'resources/icons/Icon~ipad.png',
        114: 'resources/icons/Icon@2x.png',
        144: 'resources/icons/Icon~ipad@2x.png'
    },
    
    phoneStartupScreen: 'resources/loading/Homescreen.jpg',
    tabletStartupScreen: 'resources/loading/Homescreen~ipad.jpg',

    launch: function() {
        // Destroy the #appLoadingIndicator element
        Ext.fly('appLoadingIndicator').destroy();

        var checkinPage = Ext.create('Ext.Panel', {
            scrollable: {
                direction: 'vertical',
                directionLock: true
            },
            items: [{
                xtype: 'slicedimage',
                docked: 'top',
                src: 'screen-1-top.png',
                regions: {
                    back: {top: 50, left: 10, width: 125, height: 60, goto: 1}
                }
            },{
                xtype: 'slicedimage',
                src: 'screen-1-middle.png'
            },{
                xtype: 'slicedimage',
                docked: 'bottom',
                src: 'screen-1-bottom.png'
            }]
        });

        var mainPage = Ext.create('Ext.Panel', {
            scrollable: {
                direction: 'vertical',
                directionLock: true
            },
            items: [{
                xtype: 'slicedimage',
                docked: 'top',
                src: 'screen-2-top.png'
            },{
                xtype: 'slicedimage',
                src: 'screen-2-middle.png'
            },{
                xtype: 'slicedimage',
                docked: 'bottom',
                src: 'screen-2-bottom.png',
                regions: {
                    checkin: {top: 5, left: 250, width: 130, height: 100, goto: 0}
                }
            }]
        });

        App.container = Ext.create('Ext.Container', {
            id: 'main',
            fullscreen: true,
            layout: {
                type: 'card',
                animation: {
                    type: 'slide',
                    direction: 'left'
                }
            },
            items: [checkinPage, mainPage]
        });
    },

    onUpdated: function() {
        Ext.Msg.confirm(
            "Application Update",
            "This application has just successfully been updated to the latest version. Reload now?",
            function() {
                window.location.reload();
            }
        );
    },

    updateLocation: function() {
        var geo = Ext.create('Ext.util.Geolocation', {
            autoUpdate: false,
            listeners: {
                locationupdate: function(geo) {
                    console.log(geo.getLatitude());
                    console.log(geo.getLongitude());
                },
                locationerror: function(geo, bTimeout, bPermissionDenied, bLocationUnavailable, message) {
                    if(bTimeout){
                        alert('Timeout occurred.');
                    } else {
                        alert('Error occurred.');
                    }
                }
            }
        });
        console.log('geo update location');
        geo.updateLocation();
    }
});
