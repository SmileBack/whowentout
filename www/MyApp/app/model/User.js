Ext.define('MyApp.model.User', {
    extend: 'Ext.data.Model',
    config: {
        fields: [
            {name: 'id', type: 'int'},
            {name: 'firstName', type: 'string'},
            {name: 'lastName', type: 'string'},
            {name: 'middleInitial', type: 'string'},
            {name: 'facebookId', type: 'string'}
        ]
    }
});
