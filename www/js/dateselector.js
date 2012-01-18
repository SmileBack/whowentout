//= require jquery.js
//= require jquery.entwine.js

$.fn.whenShown = function (fn) {
    var props = { position:'absolute', visibility:'hidden', display:'block' },
    hiddenParents = $(this).parents().andSelf().not(':visible');

    //set style for hidden elements that allows computing
    var oldProps = [];
    hiddenParents.each(function () {
        var old = {};

        for (var name in props) {
            old[ name ] = this.style[ name ];
            this.style[ name ] = props[ name ];
        }

        oldProps.push(old);
    });

    var result = fn.call($(this));

    //reset styles
    hiddenParents.each(function (i) {
        var old = oldProps[i];
        for (var name in props) {
            this.style[ name ] = old[ name ];
        }
    });

    return result;
};

$.fn.hiddenDimensions = function (includeMargin) {
    return this.whenShown(function () {
        return {
            width:this.width(),
            outerWidth:this.outerWidth(includeMargin),
            innerWidth:this.innerWidth(),
            height:this.height(),
            innerHeight:this.innerHeight(),
            outerHeight:this.outerHeight(includeMargin),
            margin:$.fn.margin ? this.margin() : null,
            padding:$.fn.padding ? this.padding() : null,
            border:$.fn.border ? this.border() : null
        };
    });
};

$('.scrollable').entwine({
    onmatch:function () {
        var index = this.getIndex() || 0;
        this.setIndex(index);
    },
    onunmatch:function () {
    },
    animateToIndex:function (index, onComplete) {
        var self = this;
        onComplete = onComplete || function () {
        };

        var x = this._indexToX(index);
        this._animateToX(x, function () {
            self.data('index', index);
            onComplete.call(self);
        });
    },
    animateOffset:function (offset, onComplete) {
        var index = this.getIndex() + offset;
        this.animateToIndex(index, onComplete);
    },
    setIndex:function (index) {
        var x = this._indexToX(index);
        this._setX(x);
    },
    getIndex:function () {
        return this.data('index');
    },
    getCenterEl:function () {
        var index = this.getIndex();
        return this.indexToEl(index + 3);
    },
    _setX:function (x) {
        this.find('> .items').css({'margin-left':-x + 'px'});
    },
    _animateToX:function (x, onComplete) {
        var self = this;
        this.find('> .items').animate({'margin-left':-x + 'px'}, {
            duration:300,
            complete:onComplete
        });
    },
    indexToEl:function (index) {
        return this.find('> .items > *').eq(index);
    },
    _indexToX:function (index) {
        var width = 0;
        var elementsBefore = this.indexToEl(index).prevAll();
        elementsBefore.each(function () {
            var dimensions = $(this).hiddenDimensions(true);
            width += dimensions.outerWidth;
        });
        return width;
    }
});

$('#events_date_selector').entwine({
    setActiveLink: function(link) {
        this.find('.active').removeClass('active');
        link.addClass('active');
        return this;
    }
});

$('#events_date_selector .items a').entwine({
    onclick:function (e) {
        e.preventDefault();
        var index = this.index();

        whowentout.router.navigate(this.attr('href'), true);
        this.closest('#events_date_selector')
            .setActiveLink(this)
            .find('.scrollable').animateToIndex(index - 3);
    }
});

$('#events_date_selector .prev').entwine({
    onclick:function (e) {
        e.preventDefault();
        this.closest('#events_date_selector').find('a.active').prev().click();
    }
});

$('#events_date_selector .next').entwine({
    onclick:function (e) {
        e.preventDefault();
        this.closest('#events_date_selector').find('a.active').next().click();
    }
});


