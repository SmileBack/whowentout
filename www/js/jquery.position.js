//= require jquery.js

function Rectangle(left, top, width, height) {
    this.left = left;
    this.top = top;
    this.width = width;
    this.height = height;
    this._computeCorners();
}

Rectangle.prototype._computeCorners = function() {
    this.bottom = this.top + this.height;
    this.right = this.left + this.width;

    this.lt = this.tl = {left:this.left, top:this.top};
    this.ct = this.tc = {left:this.left + this.width / 2, top:this.top};
    this.rt = this.tr = {left:this.left + this.width, top:this.top};

    this.lc = this.cl = {left:this.left, top:this.top + this.height / 2};
    this.c = this.cc = {left:this.left + this.width / 2, top:this.top + this.height / 2};
    this.rc = this.cr = {left:this.left + this.width, top:this.top + this.height / 2};

    this.lb = this.bl = {left:this.left, top:this.top + this.height};
    this.cb = this.bc = {left:this.left + this.width / 2, top:this.top + this.height};
    this.rb = this.br = {left:this.left + this.width, top:this.top + this.height};
};

Rectangle.prototype.isAbove = function(rect) {
    return this.bottom < rect.top;
};

Rectangle.prototype.isBelow = function(rect) {
    return this.top > rect.bottom;
};

Rectangle.prototype.isRight = function(rect) {
    return this.left > rect.right;
};

Rectangle.prototype.isLeft = function(rect) {
    return this.right < rect.left;
};

Rectangle.prototype.overlaps = function(rect) {
    var noOverlap = this.isAbove(rect) || this.isBelow(rect)
                || this.isRight(rect) || this.isLeft(rect);
    return !noOverlap;
};

Rectangle.prototype.translate = function(deltaX, deltaY) {
    return new Rectangle(this.left + deltaX, this.top + deltaY, this.width, this.height);
};

Rectangle.prototype.translatePoint = function(pointName, thatRect, thatPoint) {
    var thisPoint = this[pointName];
    var thatPoint = thatRect[thatPoint];

    //without any further translation, the top-left of the source will get aligned to target
    var translate = {
        left: this.left - thisPoint.left,
        top: this.top - thisPoint.top
    };
    return new Rectangle(thatPoint.left + translate.left, thatPoint.top + translate.top, this.width, this.height);
};

(function() {

  var sb_windowTools = {
    scrollBarPadding: 17, // padding to assume for scroll bars

    // INFORMATION GETTERS
    // load the page size, view port position and vertical scroll offset
    updateDimensions: function() {
            this.updatePageSize();
            this.updateWindowSize();
            this.updateScrollOffset();
    },

    // load page size information
    updatePageSize: function() {
            // document dimensions
            var viewportWidth, viewportHeight;
            if (window.innerHeight && window.scrollMaxY) {
                    viewportWidth = document.body.scrollWidth;
                    viewportHeight = window.innerHeight + window.scrollMaxY;
            } else if (document.body.scrollHeight > document.body.offsetHeight) {
                    // all but explorer mac
                    viewportWidth = document.body.scrollWidth;
                    viewportHeight = document.body.scrollHeight;
            } else {
                    // explorer mac...would also work in explorer 6 strict, mozilla and safari
                    viewportWidth = document.body.offsetWidth;
                    viewportHeight = document.body.offsetHeight;
            };
            this.pageSize = {
                    viewportWidth: viewportWidth,
                    viewportHeight: viewportHeight
            };
    },

    // load window size information
    updateWindowSize: function() {
            // view port dimensions
            var windowWidth, windowHeight;
            if (self.innerHeight) {
                    // all except explorer
                    windowWidth = self.innerWidth;
                    windowHeight = self.innerHeight;
            } else if (document.documentElement && document.documentElement.clientHeight) {
                    // explorer 6 strict mode
                    windowWidth = document.documentElement.clientWidth;
                    windowHeight = document.documentElement.clientHeight;
            } else if (document.body) {
                    // other explorers
                    windowWidth = document.body.clientWidth;
                    windowHeight = document.body.clientHeight;
            };
            this.windowSize = {
                    windowWidth: windowWidth,
                    windowHeight: windowHeight
            };
    },

    // load scroll offset information
    updateScrollOffset: function() {
            // viewport vertical scroll offset
            var horizontalOffset, verticalOffset;
            if (self.pageYOffset) {
                    horizontalOffset = self.pageXOffset;
                    verticalOffset = self.pageYOffset;
            } else if (document.documentElement && document.documentElement.scrollTop) {
                    // Explorer 6 Strict
                    horizontalOffset = document.documentElement.scrollLeft;
                    verticalOffset = document.documentElement.scrollTop;
            } else if (document.body) {
                    // all other Explorers
                    horizontalOffset = document.body.scrollLeft;
                    verticalOffset = document.body.scrollTop;
            };
            this.scrollOffset = {
                    horizontalOffset: horizontalOffset,
                    verticalOffset: verticalOffset
            };
    },

    // INFORMATION CONTAINERS

    // raw data containers
    pageSize: {},
    windowSize: {},
    scrollOffset: {},

    // combined dimensions object with bounding logic
    pageDimensions: {
        pageWidth: function() {
            return sb_windowTools.pageSize.viewportWidth > sb_windowTools.windowSize.windowWidth ?
                    sb_windowTools.pageSize.viewportWidth :
                    sb_windowTools.windowSize.windowWidth;
        },
        pageHeight: function() {
            return sb_windowTools.pageSize.viewportHeight > sb_windowTools.windowSize.windowHeight ?
                    sb_windowTools.pageSize.viewportHeight :
                    sb_windowTools.windowSize.windowHeight;
        },
        windowWidth: function() {
            return sb_windowTools.windowSize.windowWidth;
        },
        windowHeight: function() {
            return sb_windowTools.windowSize.windowHeight;
        },
        horizontalOffset: function() {
            return sb_windowTools.scrollOffset.horizontalOffset;
        },
        verticalOffset: function() {
            return sb_windowTools.scrollOffset.verticalOffset;
        }
    }
  };

    $.fn.getBox = function () {
        if (this.get(0).tl !== undefined)
            return this.get(0);

        var box;

        if (this.is('body')) {
            sb_windowTools.updateDimensions();
            box = new Rectangle(
              sb_windowTools.pageDimensions.horizontalOffset(),
              sb_windowTools.pageDimensions.verticalOffset(),
              sb_windowTools.pageDimensions.windowWidth(),
              sb_windowTools.pageDimensions.windowHeight()
            );
        }
        else {
            box = new Rectangle(
                        $(this).offset().left,
                        $(this).offset().top,
                        $(this).outerWidth(true),
                        $(this).outerHeight(true)
                    );
        }

        return box;
    };

})();


(function ($) {
    var num = function (value) {
        return parseInt(value, 10) || 0;
    };

    /**
     * Sets or gets the values for min-width, min-height, max-width
     * and max-height.
     */
    $.each(['min', 'max'], function (i, name) {
        $.fn[name + 'Size'] = function (value) {
            var width, height;
            if (value) {
                if (value.width !== undefined) {
                    this.css(name + '-width', value.width);
                }
                if (value.height !== undefined) {
                    this.css(name + '-height', value.height);
                }
                return this;
            }
            else {
                width = this.css(name + '-width');
                height = this.css(name + '-height');
                // Apparently:
                //  * Opera returns -1px instead of none
                //  * IE6 returns undefined instead of none
                return {'width':(name === 'max' && (width === undefined || width === 'none' || num(width) === -1) && Number.MAX_VALUE) || num(width),
                    'height':(name === 'max' && (height === undefined || height === 'none' || num(height) === -1) && Number.MAX_VALUE) || num(height)};
            }
        };
    });

    /**
     * Sets or gets the values for border, margin and padding.
     */
    $.each(['border', 'margin', 'padding'], function (i, name) {
        $.fn[name] = function (value) {
            if (value) {
                if (value.top !== undefined) {
                    this.css(name + '-top' + (name === 'border' ? '-width' : ''), value.top);
                }
                if (value.bottom !== undefined) {
                    this.css(name + '-bottom' + (name === 'border' ? '-width' : ''), value.bottom);
                }
                if (value.left !== undefined) {
                    this.css(name + '-left' + (name === 'border' ? '-width' : ''), value.left);
                }
                if (value.right !== undefined) {
                    this.css(name + '-right' + (name === 'border' ? '-width' : ''), value.right);
                }
                return this;
            }
            else {
                return {
                    top:num(this.css(name + '-top' + (name === 'border' ? '-width' : ''))),
                    bottom:num(this.css(name + '-bottom' + (name === 'border' ? '-width' : ''))),
                    left:num(this.css(name + '-left' + (name === 'border' ? '-width' : ''))),
                    right:num(this.css(name + '-right' + (name === 'border' ? '-width' : '')))
                };
            }
        };
    });
})(jQuery);

$.fn.getPosition = function (target, options) {
    var defaults = {
        anchor:['tl', 'tl'],
        offset:[0, 0],
        animate:false
    };

    if (typeof options == 'string') {
        options = {anchor:[options, options]};
    }

    options = $.extend(defaults, options);

    if (typeof options.anchor == 'string') {
        options.anchor = [options.anchor, options.anchor];
    }
    if (options.anchor.length == 1) {
        options.anchor[1] = options.anchor[0]
    }

    if (target == 'viewport')
        target = $('body').getBox();

    var sourceBox = $(this).getBox();
    var targetBox = $(target).getBox();

    var finalBox = sourceBox.translatePoint(options.anchor[0], targetBox, options.anchor[1]);

    if (this.css('position') == 'fixed') {
        var viewportBoxCorner = $('body').getBox().tl;
        finalBox = finalBox.translate(-viewportBoxCorner.left, -viewportBoxCorner.top);
    }

    return finalBox.tl;
};

$.fn.isAbove = function(that) {
    var thisBox = $(this).getBox();
    var thatBox = $(that).getBox();
    return thisBox.isAbove(thatBox);
};

$.fn.isBelow = function(that) {
    var thisBox = $(this).getBox();
    var thatBox = $(that).getBox();
    return thisBox.isBelow(thatBox);
};

$.fn.applyPosition = function (target, options) {
    var position = $(this).getPosition(target, options);
    if (options.animate) {
        $(this).animate({left:position.left + 'px', top:position.top + 'px'}, 500);
    }
    else {
        $(this).css({left:position.left + 'px', top:position.top + 'px'});
    }
    return this;
};

$.fn.anchor = function (target, points) {
    if (target === undefined && points === undefined) {
        if (this.data('anchor') == null)
            this.data('anchor', {
                target:'body',
                anchor:'c'
            });
        return this.data('anchor');
    }
    else {
        this.data('anchor', {
            target:target,
            anchor:points
        });
        this.refreshPosition();
        return this;
    }
};

$.fn.pinDown = function () {
    this.css('position', 'fixed').refreshPosition();
    return this;
};

$.fn.refreshPosition = function () {
    var options = this.anchor();
    this.applyPosition(options.target, options);
    return this;
};

$.fn.anchorPosition = function () {
    var options = this.anchor();
    return this.getPosition(options.target, options);
};
