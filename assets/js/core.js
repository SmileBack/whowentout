var WWOClass = jQuery.subclass();
var WWO = null;

jQuery(function() {
  WWO = WWOClass('#wwo');
});

function every(seconds, fn) {
  return setInterval(fn, seconds * 1000);
}

function current_time() {
  var delta = parseInt( $('body').attr('data-time-delta') );
  var time = new Date();
  time.setSeconds(time.getSeconds() + delta);
  return time;
}
