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
    //returning doors closed
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
  testAjax: function() {
    $.ajax({
      url: '/welcome/ajax',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        console.log(response);
      }
    });
  },
  gender: function() {
    return this.attr('gender');
  },
  otherGender: function() {
    return this.attr('other-gender');
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

function yesterday_time() {
  var unixTs = parseInt( $('#wwo').attr('yesterday-time') );
  //Unix timestamp uses seconds while JS Date uses milliseconds
  return new Date(unixTs * 1000);
}

function tomorrow_time() {
  var unixTs = parseInt( $('#wwo').attr('tomorrow-time') );
  //Unix timestamp uses seconds while JS Date uses milliseconds
  return new Date(unixTs * 1000);
}

jQuery.event.special.imageload = {
  setup: function(data, namespaces) {
    var self = $(this);
    var images = $(this).is('img') ? $(this) : $(this).find('img');
    var numImages = images.length;
    var numLoaded = 0;

    images.each(function() {
      var src = $(this).attr('src');
      var img = new Image();
      img.onload = function() {
        numLoaded++;
        if (numLoaded >= numImages)
          self.trigger('imageload');
      };
      img.src = src;
    });
  },
  teardown: function(namespaces) {},
  handler: function(event) {}
};

$.fn.whenShown = function(fn) {
  var props = { position: 'absolute', visibility: 'hidden', display: 'block' },
      hiddenParents = this.parents().andSelf().not(':visible');
      
  //set style for hidden elements that allows computing
  var oldProps = [];
  hiddenParents.each(function() {
      var old = {};

      for ( var name in props ) {
          old[ name ] = this.style[ name ];
          this.style[ name ] = props[ name ];
      }

      oldProps.push(old);
  });
  
  var result = fn.call( $(this) );
  
  //reset styles
  hiddenParents.each(function(i) {
      var old = oldProps[i];
      for ( var name in props ) {
          this.style[ name ] = old[ name ];
      }
  });
  
  return result;
}

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
}

function getParameterByName(name) {
  var match = RegExp('[?&]' + name + '=([^&]*)')
                  .exec(window.location.search);
  return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}

