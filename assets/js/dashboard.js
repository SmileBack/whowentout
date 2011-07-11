jQuery(function($) {
  $('#wwo').live('doorsclose', function() {
    window.location.reload(true);
  });
});

jQuery(function($) {
  
  WWOClass.fn.getDisplayedThumbnailIds = function() {
  var ids = [];
    $('.recent_attendees li').each(function() {
      ids.push( parseInt($(this).attr('data-user-id')) );
    });
    return ids;
  }

  WWOClass.fn.updateRecentThumbnails = function() {
    var partyId = $('.recent_attendees').attr('data-party-id');
    $.ajax({
      url: 'party/recent/' + partyId,
      dataType: 'json',
      success: function(ids) {
        var displayedIds = WWO.getDisplayedThumbnailIds();
        for (var i = ids.length - 1; i >= 0; i--) {
          if ( i == 0 && $.inArray(ids[i], displayedIds) == -1 )
            WWO.insertThumbnail( ids[i] );
        }
      }
    });
  }
  WWOClass.fn.createThumbnailElement = function(id) {
    var li = $('<li/>');
    li.attr('data-user-id', id);
    var img = $('<img/>');
    img.attr('src', '/pics/thumb/' + id + '.jpg');
    li.append(img);
    return li
  }
  WWOClass.fn.insertThumbnail = function(id) {
    console.log('insert thumbnail ' + id);
    var oldPics = $('.recent_attendees li:gt(2)');

    var el = WWO.createThumbnailElement(id);
    el.css('opacity', 0);
    el.css('position', 'fixed');
    $('.recent_attendees').prepend(el);

    var originalWidth = el.width(); originalWidth = 105;
    var originalMarginLeft = 10;
    var originalMarginRight = 10;

    var speed = 300;
    el.css({position: '', 'margin-left': '-' + originalWidth + 'px', 'margin-right': '0px'});

    if (oldPics.length > 0) {
      oldPics.fadeOut(speed, function() {
        $(this).remove();
        el.animate({
          'margin-left': originalMarginLeft + 'px',
          'margin-right': originalMarginRight + 'px'
        }, speed)
        .animate({opacity: 1}, speed);
      });
    }
    else {
      el
      .animate({
        'margin-left': originalMarginLeft + 'px'
      }, speed)
      .animate({opacity: 1}, speed);
    }

    return el;
  }
  WWOClass.fn.stuff = function() {
    alert('stuff');
  }
  
});

jQuery(function($) {
  if ($('.recent_attendees').length > 0) {
    every(10, function() {
      WWO.updateRecentThumbnails();
    });
  }
});

