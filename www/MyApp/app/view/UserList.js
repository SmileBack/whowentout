Ext.define('MyApp.view.UserList', {
    extend: 'Ext.List',
    requires: ['MyApp.store.Users'],

    config: {
        title: 'People in Your Neighborhood',
        itemTpl: new Ext.XTemplate('{[this.facebookThumb(values.facebookId)]} ' +
            '{firstName} {lastName}, {age}'
            + '<tpl for="networks"><div class="network">{name}</div></tpl>', {
            facebookThumb: function(facebookId) {
                var link = 'https://graph.facebook.com/' + facebookId + '/picture';
                return '<img src="' + link + '" />';
            }
        }),
        store: 'Users'
    }
});
