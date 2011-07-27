var WWO = null;
jQuery(function() {
  WWO = $('#wwo');
});

$('#wwo').entwine({
  onmatch: function() {
    this._calculateTimeDelta();
  },
  onunmatch: function() {},
  timeDelta: function() {
    return this.data('timedelta');
  },
  doorsOpen: function() {
    return this.attr('doors-open') == 'true';
  },
  doorsClosed: function() {
    return ! this.doorsOpen();
  },
  showMutualFriendsDialog: function(path) {
    WWO.dialog.title('Mutual Friends').message('loading...')
              .setButtons('close').show('friends_popup');
    WWO.dialog.refreshPosition();
    WWO.dialog.find('.dialog_body').load(path, function() {
      WWO.dialog.refreshPosition();
    });
  },
  whereFriendsWentData: function() {
    return $.parseJSON( this.find('.where-friends-went-data').text() );
  },
  _calculateTimeDelta: function() {
    var serverUnixTs = parseInt( $('#wwo').attr('current-time') );
    //Unix timestamp uses seconds while JS Date uses milliseconds
    var serverTime = new Date(serverUnixTs * 1000);
    var browserTime = new Date();
    var delta = (serverTime - browserTime);
    this.data('timedelta', delta);
  }
});

function every(seconds, fn) {
  return setInterval(fn, seconds * 1000);
}

function current_time() {
  var time = new Date();
  time.setMilliseconds( time.getMilliseconds() + $('#wwo').timeDelta() );
  return time;
}

function doors_closing_time() {
  var unixTs = parseInt( $('#wwo').attr('doors-closing-time') );
  //Unix timestamp uses seconds while JS Date uses milliseconds
  return new Date(unixTs * 1000);
}

function doors_opening_time() {
  var unixTs = parseInt( $('#wwo').attr('doors-opening-time') );
  //Unix timestamp uses seconds while JS Date uses milliseconds
  return new Date(unixTs * 1000);
}

$.fn.imagesLoaded = function(callback) {
  var elems = this.filter('img'),
      len   = elems.length;
      
  elems.bind('load',function(){
      if ( --len <= 0 ) { callback.call(elems, this); }
  }).each(function(){
     // cached images don't fire load sometimes, so we reset src.
     if (this.complete || this.complete === undefined){
        var src = this.src;
        // webkit hack from http://groups.google.com/group/jquery-dev/browse_thread/thread/eee6ab7b2da50e1f
        // data uri bypasses webkit log warning (thx doug jones)
        this.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
        this.src = src;
     }  
  }); 

  return this;
};
