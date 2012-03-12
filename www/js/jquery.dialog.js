//= require jquery.js
//= require jquery.entwine.js
//= require underscore.js

$('#mask').entwine({
    onclick:function () {
        $('.dialog').trigger({
            type: 'maskclick',
            mask: this
        });
    }
});

jQuery(function ($) {
    if ($('#mask').length == 0) {
        var mask = $('<div id="mask"/>').css({
            display:'none',
            position:'fixed',
            top:'0px',
            left:'0px',
            background:'black',
            opacity:0.4,
            width:'100%',
            height:'100%',
            'z-index':9000
        });
        $('body').append(mask);
    }
});

$.dialog = {
    create:function (options) {
        _.defaults(options, {
            refreshPosition: true
        });

        var d = $('<div class="dialog"> '
                + '<h1></h1>'
                + '<h2></h2>'
                + '<div class="dialog_body"></div>'
                + '<div class="dialog_buttons"></div>'
                + '</div>');
        $('body').append(d);

        d.data('dialog.options', options);

        if (d.data('dialog.options').refreshPosition)
            d.beginRefreshPosition();

        return d;
    },
    buttonSets:{
        none:{},
        yesno:[
            {key:'year', title:'Yes'},
            {key:'n', title:'No'}
        ],
        confirmcancel:[
            {key:'confirm', title:'Confirm'},
            {key:'cancel', title:'Cancel'}
        ],
        ok:[
            {key:'ok', title:'OK'}
        ],
        close:[
            {key:'close', title:'Close'}
        ],
        nextprevclose:[
            {key:'prev', title:'Prev'},
            {key:'close', title:'Close'},
            {key:'next', title:'Next'}
        ],
        'continue':[
            {key:'continue', title:'Continue'}
        ]
    }
};

$('.dialog').entwine({
    onmaskclick: function() {
        this.hideDialog();
    },
    backgroundMask: function() {
        return $('#mask');
    },
    onshow: function() {
        $('body').addClass('dialog_visible');
    },
    onhide: function() {
        $('body').removeClass('dialog_visible');
    },
    title: function (text) {
        if (text === undefined) {
            return this.find('h1').text();
        }
        else {
            this.find('h1').text(text);
            this.refreshDialogPosition();
            return this;
        }
    },
    subtitle: function (text) {
        if (text === undefined) {
            return this.find('h2').text();
        }
        else {
            this.find('h2').text(text);
            this.refreshDialogPosition();
            return this;
        }
    },
    message:function (text) {
        if (text === undefined) {
            return this.find('.dialog_body').text();
        }
        else {
            this.find('.dialog_body').html(text);
            this.refreshDialogPosition();
            return this;
        }
    },
    showLoadingMessage: function() {
        this.message('<div class="dialog_loading">Loading...</div>');
    },
    loadContent:function (path, complete) {
        var self = this;
        complete = complete || function () {};

        this.showLoadingMessage();
        this.find('.dialog_body').load(path, function () {
            self.refreshDialogPosition();
            $(this).bind('imageload', function (e) {
                self.refreshDialogPosition();
            });
            complete.call(self);
        });
    },
    setButtons:function (buttons) {
        var self = this;
        if (typeof buttons == 'string')
            buttons = $.dialog.buttonSets[buttons];

        this.removeAllButtons();
        $.each(buttons, function (k, button) {
            self.addButton(button.key, button.title, button.properties);
        });

        return this;
    },
    addButton:function (key, title, attributes) {
        attributes = $.extend({}, {href:'#'}, attributes);
        var button = $('<a/>');
        button.addClass('button').html(title);
        for (var prop in attributes) {
            button.attr(prop, attributes[prop]);
        }

        button.attr('data-key', key);
        button.addClass(key);

        this.find('.dialog_buttons').append(button);

        this.refreshDialogPosition();

        return button;
    },
    removeButton:function (key) {
        this.find('.button[data-key=' + key + ']');
        this.refreshDialogPosition();
    },
    removeAllButtons:function () {
        this.find('.dialog_buttons').empty();
        this.refreshDialogPosition();
    },
    showDialog:function (cls, data) {
        var self = this;

        if (cls != null) {
            this.attr('class', 'dialog');
            this.addClass(cls);
        }
        if (data != null) {
            this.data('dialog_data', data);
        }
        this.backgroundMask().fadeIn(300);
        this.fadeIn(300, function() {
            self.trigger('show');
        });
        return this;
    },
    hideDialog:function () {
        var self = this;
        this.backgroundMask().fadeOut(300);
        this.fadeOut(300, function() {
            self.trigger('hide');
        });
        this.clearActions();
        return this;
    },
    getActions:function () {
        return this.data('dialogActions') || {};
    },
    setActions:function (actions) {
        actions = actions || {};
        this.data('dialogActions', actions);
    },
    clearActions:function () {
        this.setActions({});
    },
    runAction:function (actionName) {
        var actions = this.getActions(actionName);
        var actionCallback = actions[actionName] || function () {};
        var dialogData = this.data('dialog_data');

        return actionCallback.call(this, dialogData);
    },
    refreshDialogPosition: function() {
        console.log(this.data('dialog.options'));
        if (!this.data('dialog.options').refreshPosition)
            return this;

        this.css({
            marginLeft: '-' + (this.outerWidth() / 2) + 'px',
            marginTop: '-' + (this.outerHeight() / 2) + 'px'
        });
        return this;
    },
    beginRefreshPosition: function() {
        var self = this;
        var refresh_position = function() {
            self.refreshDialogPosition();
        };
        refresh_position = _.debounce(refresh_position, 100);
        setInterval(refresh_position, 250);

        $(window).bind('resize', function() {
            setTimeout(refresh_position, 250);
        });
    }
});

$('.dialog .button').entwine({
    onclick:function (e) {
        e.preventDefault();

        var dialog = this.closest('.dialog');
        var result = dialog.runAction(this.key());

        if (result !== false)
            dialog.hideDialog();
    },
    key:function () {
        return this.attr('data-key');
    }
});
