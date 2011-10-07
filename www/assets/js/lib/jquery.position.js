//= require lib/jquery.js
//= require lib/jquery.body.js

/*!
 * JSizes - JQuery plugin v0.33
 *
 * Licensed under the revised BSD License.
 * Copyright 2008-2010 Bram Stein
 * All rights reserved.
 */
/*global jQuery*/
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
      return {'width': (name === 'max' && (width === undefined || width === 'none' || num(width) === -1) && Number.MAX_VALUE) || num(width),
              'height': (name === 'max' && (height === undefined || height === 'none' || num(height) === -1) && Number.MAX_VALUE) || num(height)};
    }
    };
  });

 /**
  * Returns whether or not an element is visible.
  */
  $.fn.isVisible = function () {
    return this.is(':visible');
  };

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
          top: num(this.css(name + '-top' + (name === 'border' ? '-width' : ''))),
          bottom: num(this.css(name + '-bottom' + (name === 'border' ? '-width' : ''))),
          left: num(this.css(name + '-left' + (name === 'border' ? '-width' : ''))),
          right: num(this.css(name + '-right' + (name === 'border' ? '-width' : '')))
        };
      }
    };
  });
})(jQuery);

$.fn.getBox = function() {
  if (this.get(0).tl !== undefined)
    return this.get(0);
  
  var box = {
    left: $(this).offset().left,
    top: $(this).offset().top,
    width: $(this).outerWidth(true),
    height: $(this).outerHeight(true)
  };

  box.lt = box.tl = {left: box.left, top: box.top};
  box.ct = box.tc = {left: box.left + box.width / 2, top: box.top};
  box.rt = box.tr = {left: box.left + box.width, top: box.top};

  box.lc = box.cl = {left: box.left, top: box.top + box.height / 2};
  box.c  = box.cc = {left: box.left + box.width / 2, top: box.top + box.height / 2};
  box.rc = box.cr = {left: box.left + box.width, top: box.top + box.height / 2};

  box.lb = box.bl = {left: box.left, top: box.top + box.height};
  box.cb = box.bc = {left: box.left + box.width / 2, top: box.top + box.height};
  box.rb = box.br = {left: box.left + box.width, top: box.top + box.height};

  return box;
}

$.fn.getPosition = function(target, options) {
  var defaults = {
    anchor: ['tl', 'tl'],
    offset: [0, 0],
    animate: false
  };
  
  if (typeof options == 'string') {
    options = {anchor: [options, options]};
  }
  
  options = $.extend(defaults, options);

  if (typeof options.anchor == 'string') {
    options.anchor = [options.anchor, options.anchor];
  }
  if (options.anchor.length == 1) {
    options.anchor[1] = options.anchor[0]
  }
  
  if (target == 'viewport')
    target = $('body').getViewportBox();
  
  var targetBox = $(target).getBox();
  var sourceBox = $(this).getBox();

  //the point on the target element that the source needs to anchor to
  var pt = targetBox[options.anchor[1]];

  //without any further translation, the top-left of the source will get aligned to target
  var translate = {
    left: sourceBox.tl.left - sourceBox[options.anchor[0]].left,
    top: sourceBox.tl.top - sourceBox[options.anchor[0]].top
  };

  var position = {
    left: pt.left + translate.left + options.offset[0],
    top: pt.top + translate.top + options.offset[1]
  };
  
  if (this.css('position') == 'fixed') {
    var viewportBoxCorner = $('body').getViewportBox().tl;
    position.left -= viewportBoxCorner.left;
    position.top -= viewportBoxCorner.top;
  }
  
  return position;
}

$.fn.applyPosition = function(target, options) {
  var position = $(this).getPosition(target, options);
  if (options.animate) {
    $(this).animate({left: position.left + 'px', top: position.top + 'px'}, 500);
  }
  else {
    $(this).css({left: position.left + 'px', top: position.top + 'px'});
  }
  return this;
}

$.fn.anchor = function(target, points) {
  if (target === undefined && points === undefined) {
    if (this.data('anchor') == null)
      this.data('anchor', {
        target: 'body',
        anchor: 'c'
      });
    return this.data('anchor');
  }
  else {
    this.data('anchor', {
      target: target,
      anchor: points
    });
    this.refreshPosition();
    return this;
  }
}

$.fn.pinDown = function() {
  this.css('position', 'fixed').refreshPosition();
  return this;
}

$.fn.refreshPosition = function() {
  var options = this.anchor();
  this.applyPosition(options.target, options);
  return this;
}

$.fn.anchorPosition = function() {
    var options = this.anchor();
    return this.getPosition(options.target, options);
}
