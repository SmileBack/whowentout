//= require lib/jquery.js
//= require lib/jquery.entwine.js
//= require widgets/jquery.dialog.js
//= require whowentout.component.js

WhoWentOut.Component.extend('WhoWentOut.Dialog', {
    _dialog: null,
    Get: function() {
        if (!this._dialog)
            this._dialog = $.dialog.create({centerInViewport: true});
        
        return this._dialog;
    },
    Show: function(options) {
        var dialog = this.Get();

        var defaults = {
            title: 'Message',
            buttons: 'ok',
            body: '',
            url: null,
            cls: 'whowentout_dialog',
            onload: function() {},
            data: {},
            actions: {}
        };

        var options = $.extend({}, defaults, options);

        dialog.title(options.title).setButtons(options.buttons);

        if (options.url) {
            dialog.message('Loading ...');
            dialog.loadContent(options.url, options.onload);
        }
        else {
            dialog.message(options.body);
            options.onload();
        }

        dialog.setActions(options.actions);

        dialog.showDialog(options.cls, options.data);
    }
}, {});
