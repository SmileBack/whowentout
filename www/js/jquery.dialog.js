//= require jquery.js
//= require jquery.entwine.js
//= require jquery.position.js
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
        var defaults = {
            expandToViewport: false
        };
        options = $.extend({}, defaults, options);

        var d = $('<div class="dialog"> '
                + '<h1></h1>'
                + '<div class="dialog_body"></div>'
                + '<div class="dialog_buttons"></div>'
                + '</div>');
        $('body').append(d);

        d.anchor('viewport', 'c'); //keeps the dialog box in the center

        var refresh_position = function() {
                d.refreshPosition();
        };
        refresh_position = _.debounce(refresh_position, 100);
        setInterval(refresh_position, 250);
        $(window).bind('resize', function() {
            setTimeout(refresh_position, 250);
        });

        return d;
    },
    buttonSets:{
        none:{},
        yesno:[
            {key:'y', title:'Yes'},
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
    title:function (text) {
        if (text === undefined) {
            return this.find('h1').text();
        }
        else {
            this.find('h1').text(text);
            this.refreshPosition();
            return this;
        }
    },
    message:function (text) {
        if (text === undefined) {
            return this.find('.dialog_body').text();
        }
        else {
            this.find('.dialog_body').html(text);
            this.refreshPosition();
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
            self.refreshPosition();
            $(this).bind('imageload', function (e) {
                self.refreshPosition();
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

        this.refreshPosition();

        return button;
    },
    removeButton:function (key) {
        this.find('.button[data-key=' + key + ']');
        this.refreshPosition();
    },
    removeAllButtons:function () {
        this.find('.dialog_buttons').empty();
        this.refreshPosition();
    },
    showDialog:function (cls, data) {
        if (cls != null) {
            this.attr('class', 'dialog');
            this.addClass(cls);
        }
        if (data != null) {
            this.data('dialog_data', data);
        }
        this.backgroundMask().fadeIn(300);
        this.fadeIn(300);
        return this;
    },
    hideDialog:function () {
        this.backgroundMask().fadeOut(300);
        this.fadeOut(300);
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
        var actionCallback = actions[actionName] || function () {
        };
        var dialogData = this.data('dialog_data');

        return actionCallback.call(this, dialogData);
    },
    expandToViewport: function() {
        var box = $('body').getViewportBox();
        this.css({
            left: 0,
            top: 0,
            width: box.width,
            height: box.height
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
