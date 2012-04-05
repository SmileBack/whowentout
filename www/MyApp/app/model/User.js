Ext.define('MyApp.model.User', {
    extend: 'Ext.data.Model',
    requires: 'MyApp.model.Network',
    config: {
        fields: [
            {name: 'id', type: 'int'},
            {name: 'firstName', type: 'string'},
            {name: 'lastName', type: 'string'},
            {name: 'middleInitial', type: 'string'},
            {name: 'facebookId', type: 'string'},
            {name: 'age', type: 'int'}
        ],
        hasMany: {name: 'networks', model: 'MyApp.model.Network'}
    }
});