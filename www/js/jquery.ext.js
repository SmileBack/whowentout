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

/**
 * Inspired by http://jacwright.com/projects/javascript/date_format/
 */
(function() {

    var compose = function(value, callbacks) {
        if (typeof callbacks == 'function')
            callbacks = [callbacks];

        for (var i = 0; i < callbacks.length; i++)
            value = callbacks[i](value);

        return value;
    };

    var months = ['January', 'February', 'March', 'April',
                  'May', 'June', 'July', 'August',
                  'September', 'October', 'November', 'December'];

    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday',
                'Thursday', 'Friday', 'Saturday'];

    var callbacks = {
        year: function(d) { return d.getFullYear() },
        month: function(d) { return d.getMonth() },
        monthWord: function(d) { return months[d.getMonth()] },
        date: function(d) { return d.getDate() },
        day: function(d) { return d.getDay() },
        dayWord: function(d) { return days[d.getDay()] },
        hour: function(d) { return d.getHours() },
        twelveHour: function(d) {
             return d.getHours() % 12 || 12;
        },
        minute: function(d) {
            return d.getMinutes();
        },
        second: function(d) {
            return d.getSeconds();
        },
        am: function(d) {
            return d.getHours() < 12 ? 'am' : 'pm';
        },
        ord: function(v) {
            return (v > 10 && v < 20) ? 'th'
                 : ['th', 'st', 'nd', 'rd'][v % 10] || 'th';
        },
        pad2: function(v) {
            return v.toString().length > 1 ? v : '0' + v;
        },
        trim3: function(v) {
            return v.substring(0, 3);
        },
        upper: function(v) {
            return v.toUpperCase();
        }
    };

    var c = callbacks;
    // initial function,
    var formats = {
        // Day
        d: [c.day, c.pad2],
        D: [c.dayWord, c.trim3],
        j: [c.date],
        l: [c.dayWord],
        N: [c.day, function(v) { return v + 1; }],
        S: [c.date, c.ord],
        w: [c.day],
        // Month
        F: [c.monthWord],
        m: [c.month, c.pad2],
        M: [c.monthWord, c.trim3],
        n: [c.month],
        // Year
        Y: [c.year],
        y: [c.year, function(v) { return v.substring(2, 4) }],
        a: [c.am],
        A: [c.am, c.upper],
        g: [c.twelveHour],
        G: [c.hour],
        h: [c.twelveHour, c.pad2],
        H: [c.hour, c.pad2],
        i: [c.minute, c.pad2],
        s: [c.second, c.pad2]
    };

    Date.prototype.format = function(format) {
        var date = this;
        return format.replace(/\w/g, function(pat) {
            return formats[pat] ? compose(date, formats[pat]) : pat;
        });
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

$.fn.stick = function() {
    var ph = this.createPlaceholder();
    var left = ph.offset().left;
    this.css({
        position: 'fixed',
        top: 0,
        left: left,
        zIndex: 100
    });
    this.margin({top: 0, right: 0, bottom: 0, left: 0});
    this.width(ph.width());

    this.addClass('stuck');

    this.trigger({
        type: 'stick',
        placeholder: ph
    });

    return this;
};

$.fn.unstick = function() {
    this.css({
        position: '',
        top: '',
        left: '',
        zIndex: '',
        margin: '',
        width: ''
    });

    this.removeClass('stuck');

    this.destroyPlaceholder();
};

$.fn.isStuck = function() {
    return this.hasClass('stuck');
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


$.fn.scrollTo = function(complete) {
    var options = {
        duration: 1000,
        complete: complete || function() {}
    };

    $('html, body').animate({scrollTop: $(this).offset().top}, options);
};


$('form').entwine({
    val: function() {
        var val = {};
        var pairs = this.serializeArray();
        for (var i = 0; i < pairs.length; i++) {
            val[pairs[i].name] = pairs[i].value;
        }
        return val;
    }
});


(function($) {

    var event = $.event,
        resizeTimeout;

    event.special[ "smartresize" ] = {
        setup: function() {
            $( this ).bind( "resize", event.special.smartresize.handler );
        },
        teardown: function() {
            $( this ).unbind( "resize", event.special.smartresize.handler );
        },
        handler: function( event, execAsap ) {
            // Save the context
            var context = this,
                args = arguments;

            // set correct event type
            event.type = "smartresize";

            if(resizeTimeout)
                clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                jQuery.event.handle.apply( context, args );
            }, execAsap === "execAsap"? 0 : 100);
        }
    }

    $.fn.smartresize = function( fn ) {
        return fn ? this.bind( "smartresize", fn ) : this.trigger( "smartresize", ["execAsap"] );
    };

})(jQuery);

$('#view').entwine({
    setBox: function(box) {
        this.css({
            left: box.left + 'px',
            top: box.top + 'px',
            width: box.width + 'px',
            height: box.height + 'px'
        });
    }
});

