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
    views: ['Main', 'Home', 'UserList', 'UserDetail', 'SlicedImage', 'PhoneScreen'],
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

        var top = {
            filters: {left: 4, top: 5, width: 68, height: 67},
            maps: {left: 81, top: 5, width: 68, height: 67},
            messages: {left: 566, top: 5, width: 68, height: 67},
            back: {left: 4, top: 5, width: 150, height: 67, goto: 'nearby'}
        };

        var bottom = {
            nearby: {left: 0, top: 795, width: 131, height: 125},
            smiles: {left: 125, top: 795, width: 131, height: 125},
            checkin: {left: 253, top: 785, width: 138, height: 132},
            crew: {left: 389, top: 795, width: 131, height: 125},
            myprofile: {left: 515, top: 795, width: 131, height: 125}
        };

        var pages = [
            {
                id: 'nearby',
                regions: {
                    filters: top.filters, maps: top.maps, messages: top.messages,
                    nearby: bottom.nearby, smiles: bottom.smiles, checkin: bottom.checkin, crew: bottom.crew, myprofile: bottom.myprofile
                }
            },

            {
                id: 'checkin',
                regions: {
                    back: top.back
                }
            },

            {
                id: 'messages',
                regions: {
                    back: top.back
                }
            },

            {
                id: 'crew',
                regions: {
                    filters: top.filters, maps: top.maps, messages: top.messages,
                    nearby: bottom.nearby, smiles: bottom.smiles, checkin: bottom.checkin, crew: bottom.crew, myprofile: bottom.myprofile,

                    addtocrew: {left: 105, top: 250, width: 420, height: 70}
                }
            },

            {
                id: 'addtocrew',
                regions: {
                    back: {left: 4, top: 5, width: 150, height: 67, goto: 'crew'}
                }
            },

            {
                id: 'myprofile',
                regions: {
                    filters: top.filters, maps: top.maps, messages: top.messages,
                    nearby: bottom.nearby, smiles: bottom.smiles, checkin: bottom.checkin, crew: bottom.crew, myprofile: bottom.myprofile
                }
            },

            {
                id: 'filters',
                regions: {
                    back: top.back
                }
            }
        ];

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
            defaultType: 'phonescreen',
            items: pages
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
