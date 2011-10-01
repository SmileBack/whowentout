(function($) {
    
    $.fn.attrEq = function(attr, val) {
        attr = attr.toString();
        val = val.toString();
        return $(this).filter(function() {
            return $(this).attr(attr) == val;
        });
    };

})(jQuery);

(function ($) {

    function img_has_loaded(imgEl) {
        var dfd = $.Deferred();

        var img = new Image();
        img.onload = function() {
            if (!dfd.isResolved())
                dfd.resolve();
        }
        img.src = $(imgEl).attr('src');

        if (img.complete && !dfd.isResolved()) {
            dfd.resolve();
        }

        return dfd.promise();
    };

    $.event.special.imageload = {
        add: function(details) {
            var self = this;
            var images = $(this).is('img') ? $(this) : $(this).find('img');
            var dfds = [];
            images.each(function() {
                dfds.push(img_has_loaded(this));
            });
            $.when.apply(this, dfds).then(function() {
                details.handler.call(self, {type: 'imageload'});
            });
        },
        remove: function(details) {
        }
    };

})(jQuery);

(function($) {
    $.fn.whenShown = function(fn) {
        var props = { position: 'absolute', visibility: 'hidden', display: 'block' },
        hiddenParents = $(this).parents().andSelf().not(':visible');

        //set style for hidden elements that allows computing
        var oldProps = [];
        hiddenParents.each(function() {
            var old = {};

            for (var name in props) {
                old[ name ] = this.style[ name ];
                this.style[ name ] = props[ name ];
            }

            oldProps.push(old);
        });

        var result = fn.call($(this));

        //reset styles
        hiddenParents.each(function(i) {
            var old = oldProps[i];
            for (var name in props) {
                this.style[ name ] = old[ name ];
            }
        });

        return result;
    };

    $.fn.textWidth = function(text) {
        return $(this).textSize(text).width;
    };

    $.fn.textHeight = function(text) {
        return $(this).textSize(text).height;
    };

    $.fn.textSize = function(text) {
        var el = $(this);
        var h = 0, w = 0;

        var div = document.createElement('div');
        document.body.appendChild(div);
        $(div).css({
            position: 'absolute',
            left: -1000,
            top: -1000,
            margin: 0,
            padding: 0,
            display: 'none'
        });

        $(div).html(text);
        var styles = ['font-size','font-style', 'font-weight', 'font-family','line-height', 'text-transform', 'letter-spacing'];
        for (var k = 0; k < styles.length; k++)
            $(div).css(styles[k], el.css(styles[k]));

        h = $(div).outerHeight(false);
        w = $(div).outerWidth(false);

        $(div).remove();

        return {height: h, width: w};
    }

    $.fn.truncateText = function(maxWidth) {
        var text = $.trim($(this).text());
        var truncatedText = text;
        var truncatedTextWidth;

        for (var i = text.length - 1; i > 3; i--) {
            truncatedText = text.substring(0, i);
            truncatedTextWidth = $(this).textWidth(truncatedText);
            if (truncatedTextWidth < maxWidth)
                break;
        }
        truncatedText += '&hellip;';
        this.html(truncatedText);
    };

    $.expr[':'].wraps = function(obj, index, meta, stack) {

        // dummy element to calculate height
        var el = $(obj).clone();
        el.css({
            position: 'absolute',
            left: '-1000px' // position far off-screen
        });
        el.text('A');
        $('body').append(el);

        var height = el.height();
        el.remove();
        return $(obj).height() > height;
    };

    //Optional parameter includeMargin is used when calculating outer dimensions
    $.fn.hiddenDimensions = function(includeMargin) {
        return this.whenShown(function() {
            return {
                width: this.width(),
                outerWidth: this.outerWidth(),
                innerWidth: this.innerWidth(),
                height: this.height(),
                innerHeight: this.innerHeight(),
                outerHeight: this.outerHeight(),
                margin: $.fn.margin ? this.margin() : null,
                padding: $.fn.padding ? this.padding() : null,
                border: $.fn.border ? this.border() : null
            };
        });
    };

    $.fn.scrollTo = function(flashSpotlight) {
        var self = this;
        var onCompleteFired = false;

        function onComplete() {
            if (onCompleteFired)
                return;

            onCompleteFired = true;
            if (flashSpotlight) {
                self.flashSpotlight();
            }
        }

        $('html, body').animate({scrollTop: $(this).offset().top}, 'slow', 'swing', onComplete);

        return this;
    };

})(jQuery);
