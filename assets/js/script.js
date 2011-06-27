jQuery(function($) {
	var closesAt = get_closing_time();
	
	function get_closing_time() {
	  var unixTimestamp = parseInt( $('.closing_time').attr('time') );
	  return new Date(unixTimestamp * 1000);
	}
	
	function every(seconds, fn) {
		return setInterval(fn, seconds * 1000);
	}
	
	function time_until(target_time) {
		var now = new Date();
	    var totalMs = target_time - now;
		
		if (totalMs < 0)
			return null;
		
		var totalMinutes = Math.floor(totalMs / (60 * 1000));
		var h = Math.floor(totalMinutes / 60);
		var m = totalMinutes % 60;
		return {h: h, m: m};
	}
	
	function update_time_message() {
	  var time = time_until(closesAt);
	  var msg = [];
	  if (time.h > 0) {
	    msg.push(time.h + ' hr');	
	  }
	  if (time.m > 0) {
		msg.push(time.m + ' min');
	  }
	  
   	  $('.remaining_time').text(msg.join(' '));
	}
	
	every(1, update_time_message);
	update_time_message();
});
