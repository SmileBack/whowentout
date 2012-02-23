//= require jquery.js
//= require jquery.entwine.js
//= require jquery.ext.js

$('.scrollable').entwine({
    onmatch: function () {
        this.refreshScrollPosition();
    },
    onunmatch: function () {
    },
    markSelected: function(el, animate) {
        if (el instanceof $) {
            this.getSelected().removeClass('active');
            el.addClass('active');

            if (animate === false) {
                this._jumpToEl(el);
            }
            else {
                this._animateToEl(el);
            }
        }
        else if ($.isNumeric(el)) {
            el = this.find('a').eq(el);
            return this.markSelected(el);
        }
    },
    getSelected: function() {
        return this.find('.active');
    },
    getElByHref: function(href) {
        return this.find('a').filter(function() {
            return $(this).attr('href') == href;
        });
    },
    refreshScrollPosition: function() {
        if (this.getSelected().length == 0)
            return;

        this.markSelected(this.getSelected(), false);
    },
    _animateToEl: function(el, onComplete) {
        var self = this;
        onComplete = onComplete || function () {};

        var x = this._elToX(el);
        this._animateToX(x, function () {
            onComplete.call(self);
        });
    },
    _jumpToEl: function(el) {
        var x = this._elToX(el);
        this._setX(x);
    },
    _getX: function() {
        return -1 * parseInt(this.find('> .items').css('margin-left'));
    },
    _setX: function (x) {
        this.find('> .items').css({'margin-left':-x + 'px'});
    },
    _animateToX: function (x, onComplete) {
        var self = this;
        this.find('> .items').animate({'margin-left':-x + 'px'}, {
            duration: 300,
            complete: onComplete
        });
    },
    _elToX: function(el) {
        var width = 0;
        var elementsBefore = el.prevAll();
        elementsBefore.each(function () {
                    var dimensions = $(this).hiddenDimensions(true);
            width += dimensions.outerWidth;
        });

        return width - this.width() / 2 + $(el).outerWidth(true) / 2;
    }
});

$('#events_date_selector .items a').entwine({
    onclick: function (e) {
        e.preventDefault();
        var index = this.index();

        whowentout.router.navigate(this.attr('href'), true);
    }
});

$('#events_date_selector .prev').entwine({
    onclick: function (e) {
        e.preventDefault();
        this.closest('#events_date_selector').find('a.active').prev().click();
    }
});

$('#events_date_selector .next').entwine({
    onclick: function (e) {
        e.preventDefault();
        this.closest('#events_date_selector').find('a.active').next().click();
    }
});
