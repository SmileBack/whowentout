var WWO = null;
jQuery(function() {
  WWO = $('#wwo');
});

$('#wwo').entwine({
  timeDelta: function() {
    return parseInt( this.attr('date-time-delta') );
  },
  showMutualFriendsDialog: function(path) {
    WWO.dialog.title('Mutual Friends').message('loading...')
              .setButtons('close').show('friends_popup');
    WWO.dialog.find('.dialog_body').load(path, function() {
      WWO.dialog.refreshPosition();
    });
  }
});

function every(seconds, fn) {
  return setInterval(fn, seconds * 1000);
}

function current_time() {
  var delta = $('#wwo').timeDelta();
  var time = new Date();
  time.setSeconds(time.getSeconds() + delta);
  return time;
}

function doors_closing_time() {
  var unixTs = parseInt( $('#wwo').attr('doors-closing-time') );
  //Unix timestamp uses seconds while JS Date uses milliseconds
  return new Date(unixTs * 1000);
}
