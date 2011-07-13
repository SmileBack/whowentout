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
      success: function(ids) {
        var currentIDs = this.thumbnailIDs();
        for (var i = ids.length - 1; i >= 0; i--) {
          if ( i == 0 && $.inArray(ids[i], currentIDs) == -1 )
            this.insertThumbnail( ids[i] );
        }
      }
    });
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
  insertThumbnail: function(id) {
    console.log('insert thumbnail ' + id);
    var oldPics = $('.recent_attendees li:gt(2)');

    var thumb = this.createThumbnail(id);
    thumb.css('opacity', 0);
    thumb.css('position', 'fixed');
    $('.recent_attendees').prepend(thumb);

    var originalWidth = thumb.width(); originalWidth = 105;
    var originalMarginLeft = 10;
    var originalMarginRight = 10;

    var speed = 300;
    thumb.css({position: '', 'margin-left': '-' + originalWidth + 'px', 'margin-right': '0px'});

    if (oldPics.length > 0) {
      oldPics.fadeOut(speed, function() {
        $(this).remove();
        thumb.animate({
          'margin-left': originalMarginLeft + 'px',
          'margin-right': originalMarginRight + 'px'
        }, speed)
        .animate({opacity: 1}, speed);
      });
    }
    else {
      thumb
      .animate({
        'margin-left': originalMarginLeft + 'px'
      }, speed)
      .animate({opacity: 1}, speed);
    }

    return thumb;
  },
  createThumbnail: function(id) {
    var li = $('<li/>');
    li.attr('data-user-id', id);
    var img = $('<img/>');
    img.attr('src', '/pics/thumb/' + id + '.jpg');
    li.append(img);
    return li
  }
});

$('.recent_attendees li').entwine({
  userID: function() {
    return parseInt( this.attr('data-user-id') );
  }
});
