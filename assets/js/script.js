jQuery.fn.reloadStylesheet = function() {
  var queryString = '?reload=' + new Date().getTime();
  jQuery(this).each(function() {
	this.href = this.href.replace(/\?.*|$/, queryString);
  });
  return this;
}

$(function() {
  
  setInterval(function() {
	$('link').reloadStylesheet();
  }, 1000);

});
