Ext.define('App.model.Network', {
    extend: 'Ext.data.Model',
    config: {
        fields: [
            {name: 'id', type: 'int'},
            {name: 'name', type: 'string'},
            {name: 'type', type: 'string'}
        ],
        belongsTo: {name: 'user', model: 'App.model.User'}
    }
});
