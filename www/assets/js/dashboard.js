$('#wwo').live('doorsclose', function() {
  window.location.reload(true);
});

$('.recent_attendees').entwine({
  onmatch: function() {
    var self = this;
    every(10, function() {
      self.update();
    });
  },
  onunmatch: function() {},
  update: function() {
    $.ajax({
      context: this,
      url: 'party/recent/' + this.partyID(),
      dataType: 'json',
      success: function(newThumbnails) {
        var currentIDs = this.thumbnailIDs();
        for (var i = newThumbnails.length - 1; i >= 0; i--) {
          if ( ! this._thumbnailExists(newThumbnails[i], currentIDs) ) {
            this.insertThumbnail( newThumbnails[i] );
          }
        }
      }
    });
  },
  _thumbnailExists: function(thumbnail, currentIDs) {
    for (var k in currentIDs) {
      if (currentIDs[k] == thumbnail.id)
        return true;
    }
    return false;
  },
  partyID: function() {
    return this.attr('data-party-id');
  },
  thumbnailIDs: function() {
    var ids = [];
    this.find('li').each(function() {
      ids.push( $(this).userID() );
    });
    return ids;
  },
  insertThumbnail: function(thumbnail) {
    var oldPics = $('.recent_attendees li:gt(2)');

    var t = this.createThumbnail(thumbnail);
    t.css('opacity', 0);
    t.css('position', 'fixed');
    $('.recent_attendees').prepend(t);

    var originalWidth = t.width(); originalWidth = 105;
    var originalMarginLeft = 10;
    var originalMarginRight = 10;

    var speed = 300;
    t.css({position: '', 'margin-left': '-' + originalWidth + 'px', 'margin-right': '0px'});

    if (oldPics.length > 0) {
      oldPics.fadeOut(speed, function() {
        $(this).remove();
        t.animate({
          'margin-left': originalMarginLeft + 'px',
          'margin-right': originalMarginRight + 'px'
        }, speed)
        .animate({opacity: 1}, speed);
      });
    }
    else {
      t
      .animate({
        'margin-left': originalMarginLeft + 'px'
      }, speed)
      .animate({opacity: 1}, speed);
    }

    return t;
  },
  createThumbnail: function(thumbnail) {
    var li = $('<li/>');
    li.attr('data-user-id', thumbnail.id);
    var img = $('<img/>');
    img.attr('src', thumbnail.path);
    li.append(img);
    return li;
  }
});

$('.recent_attendees li').entwine({
  userID: function() {
    return parseInt( this.attr('data-user-id') );
  }
});
