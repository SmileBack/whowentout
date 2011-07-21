jQuery.fn.percentWidth = function() {
  return Math.round(jQuery(this).width() / jQuery(this).parent().width() * 100);
}

jQuery.fn.percentHeight = function() {
  return Math.round(jQuery(this).height() / jQuery(this).parent().height() * 100);
}

jQuery.subclass = function(){
  function jQuerySubclass( selector, context ) {
    return new jQuerySubclass.fn.init( selector, context );
  }
  jQuery.extend(true, jQuerySubclass, this);
  jQuerySubclass.superclass = this;
  jQuerySubclass.fn = jQuerySubclass.prototype = this();
  jQuerySubclass.fn.constructor = jQuerySubclass;
  jQuerySubclass.fn.init = function init( selector, context ) {
    if (context && context instanceof jQuery && !(context instanceof jQuerySubclass)){
      context = jQuerySubclass(context);
    }
    return jQuery.fn.init.call( this, selector, context, rootjQuerySubclass );
  };
  jQuerySubclass.fn.init.prototype = jQuerySubclass.fn;
  var rootjQuerySubclass = jQuerySubclass(document);
  return jQuerySubclass;
};

jQuery.fn.container = function(className) {
  return window[className]( $(this).closest('.' + className) );
}

jQuery.fn.element = function(className, selector) {
  return window[className]( $(this).find(selector) );
}

var Element = jQuery.subclass();
