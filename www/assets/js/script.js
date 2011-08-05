$('#checkin_form').entwine({
  selectedPlace: function() {
    return {
      id: this.find('option:selected').attr('value'),
      name: this.find('option:selected').text()
    };
  }
});

$('#checkin_form :submit').entwine({
  form: function() {
    return this.closest('form');
  },
  onclick: function(e) {
    e.preventDefault();
    
    var doorsOpen = $('#wwo').doorsOpen();
    var place = this.form().selectedPlace();
    
    if (doorsOpen) {
      WWO.dialog.title('Confirm Checkin')
         .message('Checkin to ' + place.name + '?')
         .setButtons('yesno')
         .refreshPosition()
         .show('confirm_checkin');
    }
    else {
      WWO.dialog.title("Can't Checkin")
         .message("You can't checkin because the doors have closed")
         .setButtons('ok')
         .refreshPosition()
         .show('cant_checkin');
    }
  }
});

$('.confirm_checkin.dialog').live('button_click', function(e, button) {
  if (button.hasClass('y')) {
    $('#checkin_form').submit();
  }
});

jQuery(function($) {
  
  WWO.dialog = dialog.create();
  
  WWO.dialog.anchor('viewport', 'c'); //keeps the dialog box in the center
  $(window).bind('scroll', function() { //even when you scroll
    WWO.dialog.refreshPosition();
  });
  
});

$('a.confirm').entwine({
  onclick: function(e) {
    var action = this.attr('action') || 'do this';
    var result = confirm("Are you sure you want to " + action + "?");
    if (!result) {
      e.preventDefault();
    }
  }
});

$('path').entwine({
  select: function() {
    this.attr('stroke', '#000000');
  }
});

$(function() {
  var stroke = {fill: '#36C', stroke: '#D90000', strokeWidth: 1};
  
  function draw_point(svg, x, y) {
    svg.circle(x, y, 2, stroke);
  }
  
  function draw_pie_slice(svg, radius, centerX, centerY, angleStart, angleEnd, color) {
    //build offset of 10px
    if (color == '#ff9900') {
      centerX += 6 * Math.cos(angleStart + (angleEnd - angleStart) / 2);
      centerY += 6 * Math.sin(angleStart + (angleEnd - angleStart) / 2);
    }
    
    var stroke = {fill: color, stroke: '#ffffff', strokeWidth: 1, data: 'heressomedata'};
    var path = svg.createPath();
    var startX = centerX + radius * Math.cos(angleStart);
    var startY = centerY + radius * Math.sin(angleStart);
    var endX = centerX + radius * Math.cos(angleEnd);
    var endY = centerY + radius * Math.sin(angleEnd);
    var dAngle = angleEnd - angleStart;
    var drawBiggerArc = dAngle > Math.PI;
    path.move(startX, startY)
        .arc(radius, radius, 0, drawBiggerArc, true, endX, endY)
        .line(centerX, centerY)
        .line(startX, startY)
        .close();
    svg.path(null, path, stroke);
  }
  
  function draw_pie_chart(svg, radius, values) {
    var colors = ['#3366cc', '#dc3912', '#ff9900', '#109618', '#990099'];
    
    var total = 0;
    for (var k in values) {
      total += values[k];
    }
    
    var normalizedValues = [];
    for (var k in values) {
      normalizedValues.push( values[k] * 2 * Math.PI / total );
    }
    
    var curAngle = - Math.PI / 2;
    for (var i = 0; i < normalizedValues.length; i++) {
      draw_pie_slice(svg, radius, radius, radius, curAngle, curAngle + normalizedValues[i], colors[i % colors.length]);
      curAngle = curAngle + normalizedValues[i];
    }
    
  }
  
  function draw(svg) {
    var values = [3, 2, 5, 3, 3, 3, 5];
    draw_pie_chart(svg, 75, values);
  }
  
  $('.chart').svg({onLoad: draw});
  
  $('path').bind('click', function() {
    $(this).closest('svg').find('path').attr('stroke', '#ffffff');
    $(this).attr('stroke', '#000');
  });
});

$('.friendselect').entwine({
  onmatch: function() {
    this.initAutoSuggest();
  },
  onunmatch: function() {},
  initAutoSuggest: function() {
    var self = this;
    this.autoSuggest('/user/friends', {
      selectedItemProp: "name",
      searchObjProps: "name",
      minChars: 3,
      resultsHighlight: false,
      formatList: function(data, el){
        el.append(self.getFacebookImage(data.value));
        el.append('<span>&nbsp;&nbsp;' + data.name + '</span>');
        return el;
      }
    });
  },
  getFacebookImage: function(facebookId) {
    return $('<img src="https://graph.facebook.com/' + facebookId + '/picture">');
  }
});
