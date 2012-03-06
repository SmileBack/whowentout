//= require jquery.js
//= require jquery.entwine.js
//= require head.load.min.js

var format = (function()
{
    var replacer = function(context)
    {
        return function(s, name)
        {
            return context[name];
        };
    };

    return function(input, context)
    {
        return input.replace(/:(\w+)/g, replacer(context));
    };
})();

window.head = window.head || {};
window.head.css = function (path) {
    $("head").append("<link>");
    var css = $("head").children(":last");
    css.attr({
        rel:'stylesheet',
        type:'text/css',
        href:path
    });
};

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

(function($) {
	var scrollbarWidth = 0;
	$.getScrollbarWidth = function() {
		if ( !scrollbarWidth ) {
			if ( $.browser.msie ) {
				var $textarea1 = $('<textarea cols="10" rows="2"></textarea>')
						.css({ position: 'absolute', top: -1000, left: -1000 }).appendTo('body'),
					$textarea2 = $('<textarea cols="10" rows="2" style="overflow: hidden;"></textarea>')
						.css({ position: 'absolute', top: -1000, left: -1000 }).appendTo('body');
				scrollbarWidth = $textarea1.width() - $textarea2.width();
				$textarea1.add($textarea2).remove();
			} else {
				var $div = $('<div />')
					.css({ width: 100, height: 100, overflow: 'auto', position: 'absolute', top: -1000, left: -1000 })
					.prependTo('body').append('<div />').find('div')
						.css({ width: '100%', height: 200 });
				scrollbarWidth = 100 - $div.width();
				$div.parent().remove();
			}
		}
		return scrollbarWidth;
	};
})(jQuery);

$.fn.collect = function(fn) {
    var values = [];

    if (typeof fn == 'string') {
        var prop = fn;
        fn = function() { return this.attr(prop); };
    }

    $(this).each(function() {
        var val = fn.call($(this));
        values.push(val);
    });
    return values;
};

(function($) {
    $.fn.allCss = function(){
        var dom = this.get(0);
        var style;
        var returns = {};
        if(window.getComputedStyle){
            var camelize = function(a,b){
                return b.toUpperCase();
            };
            style = window.getComputedStyle(dom, null);
            for(var i = 0, l = style.length; i < l; i++){
                var prop = style[i];
                var camel = prop.replace(/\-([a-z])/g, camelize);
                var val = style.getPropertyValue(prop);
                returns[camel] = val;
            };
            return returns;
        };
        if(style = dom.currentStyle){
            for(var prop in style){
                returns[prop] = style[prop];
            };
            return returns;
        };
        return this.css();
    }
})(jQuery);


$.fn.createPlaceholder = function() {
    if (this.data('__placeholder'))
        return this.data('__placeholder');

    var node = $('<div/>');
    node.css(this.allCss());
    node.css('opacity', 0.5);
    node.addClass('placeholder');

    this.after(node);
    this.data('__placeholder', node);

    return node;
};

$.fn.destroyPlaceholder = function() {
    var node = this.data('__placeholder');

    if (node)
        node.remove();

    this.removeData('__placeholder');

    return this;
};

$('.load').entwine({
    onmatch: function() {
        var self = this;
        this.trigger('loadstart');
        $.when(this.fetchContent()).then(function(html) {
            var newEl = $(html);
            self.replaceWith(newEl);
            newEl.trigger('loadend');
        });
    },
    onunmatch: function() {},
    fetchContent: function() {
        return $.ajax({
            url: this.data('url'),
            type: 'get',
            dataType: 'html'
        });
    }
});


