WhoWentOut.Model.extend('WhoWentOut.Application', {}, {
    init: function() {
        this._super();

        if (!window.console)
            window.console = { log: function(){} };

        this.bind('load', this.callback('onload'));
        this.load();
        
        $.when(this.load()).then(this.callback('onload'));
    },
    onload: function() {
        $('body').append('<div id="chatbar" />');
    },
    load: function() {
        var self = this;

        if (this._loadDfd)
            return this._loadDfd;

        this._loadDfd = $.Deferred();

        $.ajax({
            url: '/js/app',
            type: 'post',
            dataType: 'json',
            data: { user_ids: this.userIdsOnPage() },
            success: function(response) {
                console.log(response);

                _.each(response.application, function(v, k) {
                    self.set(k, v);
                });

                self._college = WhoWentOut.College.FromJson(response.college);

                if (response.users) {
                    _.each(response.users, function(userJson) {
                        WhoWentOut.User.add( userJson  );
                    });
                }

                self._loadDfd.resolve();
            }
        });
        return this._loadDfd.promise();
    },
    userIdsOnPage: function() {
        var ids = [];
        $('.user').each(function() {
            ids.push( $(this).attr('data-user-id') );
        });
        console.log('ids');console.log(ids);
        return _.uniq(ids);
    },
    college: function() {
        return this._college;
    },
    currentUserID: function() {
        return this.get('currentUserID');
    },
    currentUser: function() {
        return WhoWentOut.User.get( this.currentUserID() );
    }
});

window.app = new WhoWentOut.Application();
